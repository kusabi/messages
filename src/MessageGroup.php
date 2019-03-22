<?php

namespace Kusabi\Messages;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;

/**
 * A message group is a collection of messages.
 *
 * All messages within the group are considered equal.
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
class MessageGroup implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, MessageGroupInterface
{
    /**
     * The messages in this message group
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Convert this message to a string
     *
     * @return string
     */
    public function __toString()
    {
        return implode(', ', array_map(function (MessageInterface $message) {
            return (string) $message;
        }, $this->messages));
    }

    /**
     * Add a new message to the group using a string message and array context
     *
     * @param string $message
     * @param array $context
     *
     * @return self
     */
    public function addMessage(string $message = '', array $context = []): self
    {
        return $this->addMessageInstance(
            new Message($message, $context)
        );
    }

    /**
     * Add multiple messages to the group in one call using a string message and array context
     *
     * Each entry of the array must be an array with keys `message` and `context` (without these, the messages created will be empty
     *
     * @param array $messages
     *
     * @throws InvalidArgumentException if any entries in the array ar not an array
     *
     * @return self
     *
     */
    public function addMessages(array $messages = []): self
    {
        foreach ($messages as $message) {

            // Each entry must be an array
            if (!is_array($message)) {
                throw new InvalidArgumentException('Entries within the messages array must all by arrays');
            }

            // Add the message
            $this->addMessage(
                $message['message'] ?? '',
                $message['context'] ?? []
            );
        }
        return $this;
    }

    /**
     * Add a new instance of MessageInterface to the group
     *
     * @param MessageInterface $message
     *
     * @return self
     */
    public function addMessageInstance(MessageInterface $message): self
    {
        $this->messages[] = $message;
        return $this;
    }

    /**
     * Add multiple new instances of MessageInterface to the group in one call
     *
     * @param array $messages
     *
     * @return self
     */
    public function addMessageInstances(array $messages): self
    {
        foreach ($messages as $message) {
            $this->addMessageInstance($message);
        }
        return $this;
    }

    /**
     * Clear the messages in the group
     *
     * @return self
     */
    public function clearMessages(): self
    {
        $this->messages = [];
        return $this;
    }

    /**
     * Returns the total number of messages in the group
     *
     * Allows the class to be used in PHPs `count()` method
     *
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     *
     * Used for looping through the messages in the group
     *
     * @return ArrayIterator
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->messages);
    }

    /**
     * Set the entire message array at once
     *
     * @param array $messages
     *
     * @return self
     */
    public function setMessages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Determine if an offset exists within the message array
     *
     * Allows the group to be used in PHPs `isset($messageGroup[0])`
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->messages[$offset]);
    }

    /**
     * Access a message by it's array offset
     *
     * Allows access to the messages as an array `$messageGroup[0]`
     *
     * @see ArrayAccess::offsetGet()
     *
     * @return MessageInterface
     */
    public function offsetGet($offset)
    {
        return $this->messages[$offset];
    }

    /**
     * Set a message by it's array offset
     *
     * Allows access to the messages as an array `$messageGroup[0] = new Message()`
     *
     * @see ArrayAccess::offsetSet()
     *
     * @throws InvalidArgumentException when $value is not an instance of MessageInterface
     */
    public function offsetSet($offset, $value)
    {
        if (!is_object($value) || !$value instanceof MessageInterface) {
            throw new InvalidArgumentException('Only messages can be added to a MessageGroup');
        }
        $this->messages[$offset] = $value;
    }

    /**
     * Unset a message by it's array offset
     *
     * Allows access to the messages as an array `unset($messageGroup[0])`
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->messages[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->messages;
    }
}
