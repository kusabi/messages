<?php

namespace Kusabi\Messages;

/**
 * A message is used in a response to indicate something to the receiver.
 *
 * In this library, a message can have a string message and an array context.
 *
 * The context is used to give further information to the message.
 *
 * The message and context combination is common place in both logs and translators.
 *
 * In order for something to be considered a Message, it just needs to be able to return a string message and an array context.
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
interface MessageInterface
{
    /**
     * Get the message string
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Get the context array
     *
     * @return array
     */
    public function getContext(): array;
}
