<?php

namespace CleverReachCore\Infrastructure\Service;

use CleverReachCore\Business\Service\ConfigService;
use CleverReachCore\Core\Infrastructure\Logger\Interfaces\ShopLoggerAdapter;
use CleverReachCore\Core\Infrastructure\Logger\LogData;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;
use Shopware\Core\Kernel;

/**
 * Class LoggerService
 *
 * @package CleverReachCore\Infrastructure\Service
 */
class LoggerService implements ShopLoggerAdapter
{
    private Kernel $kernel;

    /**
     * Creates LoggerService.
     *
     * @param Kernel $logger
     */
    public function __construct(Kernel $logger)
    {
        $this->kernel = $logger;
    }

    /**
     * Logs message in system.
     *
     * @param LogData $data
     */
    public function logMessage(LogData $data): void
    {
        $logLevel = $data->getLogLevel();
        $configService = ConfigService::getInstance();

        if ($logLevel > $configService->getMinLogLevel()) {
            return;
        }

        $message = "[Date: {$data->getTimestamp()}] Message: {$data->getMessage()}";

        $logger = $this->getSystemLogger();

        switch ($logLevel) {
            case Logger::ERROR:
                $logger->error($message);
                break;
            case Logger::WARNING:
                $logger->warning($message);
                break;
            case Logger::DEBUG:
                $logger->debug($message);
                break;
            default:
                $logger->info($message);
        }
    }

    /**
     * Returns system logger with predefined log directory and log file.
     *
     * @return MonologLogger
     */
    private function getSystemLogger(): MonologLogger
    {
        $logger = new MonologLogger('cleverreach');
        $logFile = $this->kernel->getLogDir() . '/cleverreach/cleverreach.log';
        $logger->pushHandler(new RotatingFileHandler($logFile));

        return $logger;
    }
}
