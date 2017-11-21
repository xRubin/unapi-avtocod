<?php

namespace unapi\avtocod;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait AvtocodServiceTrait
{
    /** @var ClientInterface */
    private $client;
    /** @var LoggerInterface */
    private $logger;
    /** @var string Токен безопасности, смотрите документацию и команду разработчика (SDK) /dev/token */
    private $authorization;

    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['client'])) {
            $this->client = new AvtocodClient();
        } elseif ($config['client'] instanceof ClientInterface) {
            $this->client = $config['client'];
        } else {
            throw new \InvalidArgumentException('Client must be instance of ClientInterface');
        }

        if (!isset($config['logger'])) {
            $this->logger = new NullLogger();
        } elseif ($config['logger'] instanceof LoggerInterface) {
            $this->setLogger($config['logger']);
        } else {
            throw new \InvalidArgumentException('Logger must be instance of LoggerInterface');
        }

        if (isset($config['authorization'])) {
            $this->authorization = $config['authorization'];
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Токен безопасности, смотрите документацию и команду разработчика (SDK) /dev/token
     * @param string $authorization
     */
    public function setAuthorization(string $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * Токен безопасности, смотрите документацию и команду разработчика (SDK) /dev/token
     * @return string
     */
    public function getAuthorization(): string
    {
        return $this->authorization;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}