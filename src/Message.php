<?php

namespace Kusabi\Messages;

use JsonSerializable;

/**
 * A message is used in a response to indicate something to the receiver.
 *
 * In this library, a message can have a string message and an array context.
 *
 * The context is used to give further information to the message.
 *
 * The message and context combination is common place in both logs and translators.
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
class Message implements JsonSerializable, MessageInterface
{
    /**
     * The message string
     *
     * @var string
     */
    protected $message = '';

    /**
     * The message context
     *
     * @var array
     */
    protected $context = [];

    /**
     * Instantiate a new message.
     *
     * If no message or context is passed then they both default to an empty string and array accordingly
     *
     * @param string $message Defaults to empty string ''
     * @param array $context Defaults to empty array []
     */
    public function __construct(string $message = '', array $context = [])
    {
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * Convert this message to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message string
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set the context array
     *
     * @param array $context
     *
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Merge another context array into the current context array
     *
     * This will overwrite any existing keys on collision.
     *
     * @param array $context
     *
     * @return self
     */
    public function mergeContext(array $context): self
    {
        return $this->setContext(array_merge(
            $this->getContext(),
            $context
        ));
    }

    /**
     * Add a new context entry into the array.
     *
     * This will overwrite any existing keys on collision.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function addContext(string $key, $value): self
    {
        return $this->mergeContext([
           $key => $value
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->getMessage(),
            'context' => $this->getContext()
        ];
    }
}
