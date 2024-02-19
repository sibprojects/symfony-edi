<?php

namespace App\EdiTransfer\Domain\Factory;

use App\EdiTransfer\Domain\Exception\EdiTransferFactoryException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class RWFactory
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getReader(string $interfaceName): object
    {
        try {
            $interfaceName = ucfirst($interfaceName);
            $className = 'App\EdiTransfer\Infrastructure\Reader\\' . $interfaceName . 'Reader';
            return $this->container->get($className);
        } catch (Throwable $throwable) {
            throw new EdiTransferFactoryException('Read interface "' . $interfaceName . '" not found!', JsonResponse::HTTP_NO_CONTENT, $throwable);
        }
    }

    public function getParser(string $interfaceName): object
    {
        try {
            $interfaceName = ucfirst($interfaceName);
            $className = 'App\EdiTransfer\Infrastructure\Parser\\' . $interfaceName . 'Parser';
            return $this->container->get($className);
        } catch (Throwable $throwable) {
            throw new EdiTransferFactoryException('Parse interface "' . $interfaceName . '" not found!', JsonResponse::HTTP_NO_CONTENT, $throwable);
        }
    }

    public function getEncoder(string $interfaceName): object
    {
        try {
            $interfaceName = ucfirst($interfaceName);
            $className = 'App\EdiTransfer\Infrastructure\Encoder\\' . $interfaceName . 'Encoder';
            return $this->container->get($className);
        } catch (Throwable $throwable) {
            throw new EdiTransferFactoryException('Parse interface "' . $interfaceName . '" not found!', JsonResponse::HTTP_NO_CONTENT, $throwable);
        }
    }

    public function getWriter(string $interfaceName, SymfonyStyle $io): object
    {
        try {
            $interfaceName = ucfirst($interfaceName);
            $className = 'App\EdiTransfer\Infrastructure\Writer\\' . $interfaceName . 'Writer';
            $class = $this->container->get($className);
            $class->setIo($io);
            return $class;
        } catch (Throwable $throwable) {
            throw new EdiTransferFactoryException('Write interface "' . $interfaceName . '" not found!', JsonResponse::HTTP_NO_CONTENT, $throwable);
        }
    }
}