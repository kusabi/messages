<?php

namespace Kusabi\Messages;

/**
 * A message group is a collection of messages.
 *
 * All messages within the group are considered equal.
 *
 * In order for something to be considered a Message Group, it just needs to be able to return an array of Messages.
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
interface MessageGroupInterface
{
    /**
     * Get the messages in the collection
     *
     * @return array
     */
    public function getMessages(): array;
}
