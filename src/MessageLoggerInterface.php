<?php

namespace Kusabi\Messages;

use Psr\Log\LoggerInterface;

/**
 * MessageLoggerInterface extends the PSR LoggerInterface to add entries for success and validation verbosity's
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 *
 * @see LoggerInterface
 */
interface MessageLoggerInterface extends LoggerInterface
{
    /**
     * Successful response messages
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function success(string $message, array $context = []);

    /**
     * Validation response messages
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function validation(string $message, array $context = []);
}
