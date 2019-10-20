<?php

namespace Tests;

use JsonSerializable;
use Kusabi\Messages\Message;
use Kusabi\Messages\MessageInterface;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testInstantiate()
    {
        $message = new Message();
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(JsonSerializable::class, $message);
    }

    public function testInstantiateWithMessage()
    {
        $message = new Message('Testing');
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(JsonSerializable::class, $message);
        $this->assertSame('Testing', $message->getMessage());
    }

    public function testInstantiateWithMessageAndContext()
    {
        $message = new Message('Testing', [
            'type' => 1
        ]);
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(JsonSerializable::class, $message);
        $this->assertSame('Testing', $message->getMessage());
        $this->assertSame([
            'type' => 1
        ], $message->getContext());
    }

    public function testChangeMessage()
    {
        $message = new Message();
        $message->setMessage('testing');
        $this->assertSame('testing', $message->getMessage());
    }

    public function testChangeMessageReturnsFluidChain()
    {
        $message = new Message();
        $this->assertSame('testing', $message->setMessage('testing')->getMessage());
    }

    public function testChangeContext()
    {
        $message = new Message();
        $message->setContext([1, 2, 3]);
        $this->assertSame([1, 2, 3], $message->getContext());
    }

    public function testChangeContextReturnsFluidChain()
    {
        $message = new Message();
        $this->assertSame([1, 2, 3], $message->setContext([1, 2, 3])->getContext());
    }

    public function testMergeContext()
    {
        $message = new Message();
        $message->setContext(['a' => 'b']);
        $this->assertSame(['a' => 'b'], $message->getContext());
        $message->mergeContext(['c' => 'd']);
        $this->assertSame(['a' => 'b', 'c' => 'd'], $message->getContext());
    }

    public function testMergeContextReturnsFluidChain()
    {
        $message = new Message();
        $message->setContext(['a' => 'b']);
        $this->assertSame(['a' => 'b'], $message->getContext());
        $this->assertSame(['a' => 'b', 'c' => 'd'], $message->mergeContext(['c' => 'd'])->getContext());
    }

    public function testAddContext()
    {
        $message = new Message();
        $message->setContext(['a' => 'b']);
        $this->assertSame(['a' => 'b'], $message->getContext());
        $message->addContext('c', 'd');
        $this->assertSame(['a' => 'b', 'c' => 'd'], $message->getContext());
    }

    public function testAddContextReturnsFluidChain()
    {
        $message = new Message();
        $message->setContext(['a' => 'b']);
        $this->assertSame(['a' => 'b'], $message->getContext());
        $this->assertSame(['a' => 'b', 'c' => 'd'], $message->addContext('c', 'd')->getContext());
    }

    public function testJsonEncode()
    {
        $message = new Message('Testing', [1, 2, 3]);
        $this->assertSame('{"message":"Testing","context":[1,2,3]}', json_encode($message));
    }

    public function testCastToString()
    {
        $message = new Message('Testing', [1, 2, 3]);
        $this->assertSame('Testing', (string) $message);
    }
}
