<?php

namespace App\EdiAlpegaTransfer\Application\Command;

use App\EdiAlpegaTransfer\Application\DTO\EdiAlpegaOrdersReadDTO;
use App\EdiAlpegaTransfer\Domain\Factory\RWFactory;
use App\EdiAlpegaTransfer\Infrastructure\Parser\Parser;
use App\EdiAlpegaTransfer\Infrastructure\Reader\Reader;
use App\Entity\EdiAlpegaFilesRead;
use App\Repository\EdiAlpegaFilesReadRepository;
use App\Repository\EdiAlpegaSettingsRepository;
use App\Service\ordersService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(name: 'edialpegatransfer:download', description: 'Read EDI Alpega files from SFTP and create orders')]
class EdiAlpegaTransferDownloadCommand extends Command
{
    private Reader $reader;

    private Parser $parser;

    /**
     * Constructor.
     */
    public function __construct(
        private EdiAlpegaSettingsRepository  $ediAlpegaSettingsRepository,
        private EdiAlpegaFilesReadRepository $ediAlpegaFilesReadRepository,
        private RWFactory                    $RWFactory,
        private InterfacingService2          $interfacingService2,
    )
    {
        parent::__construct();

    }

    /**
     * Configure.
     */
    protected function configure(): void
    {
        $this->addArgument(
            'read-interface',
            InputArgument::OPTIONAL,
            'Select read interface: sftp',
            'sftp'
        );
        $this->addArgument(
            'parse-interface',
            InputArgument::OPTIONAL,
            'Select parse interface: edi',
            'edi'
        );
        $this->addArgument(
            'show-and-exit',
            InputArgument::OPTIONAL,
            'Set "show-file" if you do not want to create orders and only want to show last file content' . PHP_EOL .
            'Set "show-parsed-data" if you do not want to create orders and only want to show parsed content',
            ''
        );
    }

    /**
     * Execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('[START]');

        $inputsArray = $input->getArguments();
        $this->reader = $this->RWFactory->getReader($inputsArray['read-interface']);
        $this->parser = $this->RWFactory->getParser($inputsArray['parse-interface']);

        $settings = $this->ediAlpegaSettingsRepository->findBy(['enabled' => true]);
        if (is_array($settings) && count($settings) > 0) {
            try {
                foreach ($settings as $setting) {
                    $this->reader->connect($setting->getServer(), $setting->getPort(), $setting->getUserName(), $setting->getPassword());
                    $listOfFiles = $this->reader->getList($setting->getDirectoryRead());
                    foreach ($listOfFiles as $file) {
                        if ($this->checkFileAlreadyRead($file) === false) {
                            $fileContent = $this->reader->read($file);
                            if ($inputsArray['show-and-exit'] === 'show-file') {
                                $io->info('[FILE CONTENT] ' . $file);
                                $io->info($fileContent);
                                break;
                            }
                            $data = $this->parser->read($fileContent);
                            if ($inputsArray['show-and-exit'] === 'show-parsed-data') {
                                $io->info('[PARSED DATA] ' . $file);
                                $io->info(print_r($data, true));
                                break;
                            }

                            $io->info('[CREATE ORDERS] ' . $file);
                            $io->info('[FOUND ORDERS] ' . count($data['orders']));
                            $result = $this->createOrder(
                                $setting->getClient()->getId(),
                                $setting->getShipper()->getId(),
                                $setting->getUser()->getId(),
                                $data
                            );
                            $io->info('[CREATED] ' . count($result['created']));
                            $io->info('[NOT CREATED] ' . count($result['not_created']));
                            $this->addFileRead($file, $result);
                        }
                    }
                    $this->reader->disconnect();
                }
            } catch (Throwable $throwable) {
                \Sentry\captureException($throwable);
                $io->error($throwable->getMessage());
            }
        }

        $io->info('[END]');
        return Command::SUCCESS;
    }


    private function createOrder(int $clientId, int $shipperId, int $userId, array $data): array
    {
        $dataOrders = (new EdiAlpegaOrdersReadDTO($shipperId, $data))->formatData();
        return $this->ordersService->createFromArray($clientId, $userId, $dataOrders);
    }

    private function checkFileAlreadyRead(string $fileName): bool
    {
        $file = $this->ediAlpegaFilesReadRepository->findOneBy(['filename' => $fileName]);
        return !is_null($file);
    }

    private function addFileRead(string $fileName, array $orderCreationResult): void
    {
        $fileRead = (new EdiAlpegaFilesRead())
            ->setFilename($fileName)
            ->setOrderCreationResult(json_encode($orderCreationResult));
        $this->ediAlpegaFilesReadRepository->save($fileRead);
    }
}
