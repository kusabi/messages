<?php

namespace Kusabi\Messages;

/**
 * A message collection is a collection of message groups.
 *
 * It has one group for each level.
 *
 * In order for something to be considered a Message Collection, it just needs to be able to return:
 *  - An array of Message Groups
 *  - An array of Messages merged down into a single super array (extended from MessageGroupInterface)
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
interface MessageCollectionInterface extends MessageGroupInterface
{
    /**
     * Get all the message groups
     *
     * @return array
     */
    public function getMessageGroups(): array;

    /**
     * Get all the messages outside of their groups and into a single array
     *
     * @see MessageGroupInterface::getMessages()
     */
    public function getMessages(): array;
}
