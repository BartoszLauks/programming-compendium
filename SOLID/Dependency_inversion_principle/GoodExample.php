<?php

interface LoggerInterface
{
    public function logError(string $message): void;
}

class DatabaseLogger implements LoggerInterface
{
    public function logError(string $message): void
    {
        // ..
    }
}

class MailerService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendEmail(): void
    {
        try {
            // ..
        } catch (SomeException $exception) {
            $this->logger->logError($exception->getMessage());
        }
    }
}