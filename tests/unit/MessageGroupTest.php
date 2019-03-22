<?php

use Kusabi\Messages\Message;
use Kusabi\Messages\MessageGroup;
use Kusabi\Messages\MessageGroupInterface;

class MessageGroupTest extends \Codeception\Test\Unit
{
    public function testInstantiate()
    {
        $messageGroup = new MessageGroup();
        $this->assertInstanceOf(MessageGroupInterface::class, $messageGroup);
        $this->assertInstanceOf(ArrayAccess::class, $messageGroup);
        $this->assertInstanceOf(Countable::class, $messageGroup);
        $this->assertInstanceOf(IteratorAggregate::class, $messageGroup);
        $this->assertInstanceOf(JsonSerializable::class, $messageGroup);
    }

    public function testAddMessageWithString()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Testing');
        $this->assertCount(1, $messageGroup);
        $this->assertCount(1, $messageGroup->getMessages());
    }

    public function testAddMessageWithStringReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(1, $messageGroup->addMessage('Testing'));
        $this->assertCount(2, $messageGroup->addMessage('Testing')->getMessages());
    }

    public function testAddMessageWithStringAndContext()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Testing', [1, 2, 3]);
        $this->assertCount(1, $messageGroup);
        $this->assertCount(1, $messageGroup->getMessages());
    }

    public function testAddMessageWithStringAndContextReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(1, $messageGroup->addMessage('Testing', [1, 2, 3]));
        $this->assertCount(2, $messageGroup->addMessage('Testing', [1, 2, 3])->getMessages());
    }

    public function testAddMessagesWithString()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessages([
            ['message' => 'Testing'],
            ['message' => 'Also Testing'],
            ['message' => 'Still Testing']
        ]);
        $this->assertCount(3, $messageGroup);
        $this->assertCount(3, $messageGroup->getMessages());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entries within the messages array must all by arrays
     */
    public function testAddMessagesEntryCannotBeAString()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessages([
            'not an array'
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entries within the messages array must all by arrays
     */
    public function testAddMessagesEntryCannotBeANumber()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessages([
            1
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entries within the messages array must all by arrays
     */
    public function testAddMessagesEntryCannotBeAnObject()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessages([
            new stdClass()
        ]);
    }

    public function testAddMessagesWithStringReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(3, $messageGroup->addMessages([
            ['message' => 'Testing'],
            ['message' => 'Also Testing'],
            ['message' => 'Still Testing']
        ]));
        $this->assertCount(6, $messageGroup->addMessages([
            ['message' => 'Testing'],
            ['message' => 'Also Testing'],
            ['message' => 'Still Testing']
        ])->getMessages());
    }

    public function testAddMessagesWithStringAndContext()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessages([
            ['message' => 'Testing', 'context' => [1, 2, 3]],
            ['message' => 'Also Testing', 'context' => [1, 2, 3]],
            ['message' => 'Still Testing', 'context' => [1, 2, 3]]
        ]);
        $this->assertCount(3, $messageGroup);
        $this->assertCount(3, $messageGroup->getMessages());
    }

    public function testAddMessagesWithStringAndContextReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(3, $messageGroup->addMessages([
            ['message' => 'Testing', 'context' => [1, 2, 3]],
            ['message' => 'Also Testing', 'context' => [1, 2, 3]],
            ['message' => 'Still Testing', 'context' => [1, 2, 3]]
        ]));
        $this->assertCount(6, $messageGroup->addMessages([
            ['message' => 'Testing', 'context' => [1, 2, 3]],
            ['message' => 'Also Testing', 'context' => [1, 2, 3]],
            ['message' => 'Still Testing', 'context' => [1, 2, 3]]
        ])->getMessages());
    }

    public function testAddMessageInstance()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessageInstance(
            new Message('Testing')
        );
        $this->assertCount(1, $messageGroup);
        $this->assertCount(1, $messageGroup->getMessages());
    }

    public function testAddMessageInstanceReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(1, $messageGroup->addMessageInstance(new Message('Testing')));
        $this->assertCount(2, $messageGroup->addMessageInstance(new Message('Testing'))->getMessages());
    }

    public function testAddMessageInstances()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessageInstances([
            new Message('Testing'),
            new Message('Also Testing'),
            new Message('Still Testing'),
        ]);
        $this->assertCount(3, $messageGroup);
        $this->assertCount(3, $messageGroup->getMessages());
    }

    public function testAddMessageInstancesReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $this->assertCount(3, $messageGroup->addMessageInstances([
            new Message('Testing'),
            new Message('Also Testing'),
            new Message('Still Testing'),
        ]));
        $this->assertCount(6, $messageGroup->addMessageInstances([
            new Message('Testing'),
            new Message('Also Testing'),
            new Message('Still Testing'),
        ])->getMessages());
    }

    public function testSetTheMessageArray()
    {
        $messageGroup = new MessageGroup();
        $messageArray = [
            new Message('Testing'),
            new Message('Override')
        ];
        $messageGroup->setMessages($messageArray);
        $this->assertSame($messageArray, $messageGroup->getMessages());
    }

    public function testSetTheMessageArrayReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $messageArray = [
            new Message('Testing'),
            new Message('Override')
        ];
        $this->assertSame($messageArray, $messageGroup->setMessages($messageArray)->getMessages());
    }

    public function testClearMessages()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $this->assertCount(1, $messageGroup);
        $messageGroup->clearMessages();
        $this->assertCount(0, $messageGroup);
    }

    public function testClearMessagesReturnsFluidChain()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $this->assertCount(1, $messageGroup);
        $this->assertCount(0, $messageGroup->clearMessages());
    }

    public function testAccessMessageAsArray()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $messageGroup->addMessage('Also testing');
        $this->assertEquals('Test', $messageGroup[0]);
        $this->assertEquals('Also testing', $messageGroup[1]);
    }

    public function testSetMessageAsArray()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $messageGroup->addMessage('Also testing');
        $messageGroup[1] = new Message('Still Testing');
        $messageGroup[2] = new Message('Still Testing?');
        $this->assertEquals('Test', $messageGroup[0]);
        $this->assertEquals('Still Testing', $messageGroup[1]);
        $this->assertEquals('Still Testing?', $messageGroup[2]);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Only messages can be added to a MessageGroup
     */
    public function testSetMessageAsArrayMustBeMessageInterface()
    {
        $messageGroup = new MessageGroup();
        $messageGroup[0] = 'not a message instance';
    }

    public function testOffsetExists()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $this->assertTrue(isset($messageGroup[0]));
        $this->assertFalse(isset($messageGroup[1]));
    }

    public function testUnsetMessagesAsArray()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $messageGroup->addMessage('Also testing');
        $messageGroup->addMessage('Still testing');
        $this->assertCount(3, $messageGroup);
        unset($messageGroup[1]);
        $this->assertCount(2, $messageGroup);
    }

    public function testIterateThroughMessages()
    {
        $count = 0;
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test');
        $messageGroup->addMessage('Also testing');
        $messageGroup->addMessage('Still testing');
        foreach ($messageGroup as $index => $message) {
            $count++;
            $this->assertSame((string) $message, (string) $messageGroup[$index]);
        }
        $this->assertSame($count, count($messageGroup));
    }

    public function testJsonEncode()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test', ['a', 'b', 'c']);
        $messageGroup->addMessage('Also testing', [1, 2, 3]);
        $messageGroup->addMessage('Still testing', ['you', 'and', 'me']);
        $this->assertSame(
            '[{"message":"Test","context":["a","b","c"]},{"message":"Also testing","context":[1,2,3]},{"message":"Still testing","context":["you","and","me"]}]',
            json_encode($messageGroup)
        );
    }

    public function testCastToString()
    {
        $messageGroup = new MessageGroup();
        $messageGroup->addMessage('Test', ['a', 'b', 'c']);
        $messageGroup->addMessage('Also testing', [1, 2, 3]);
        $messageGroup->addMessage('Still testing', ['you', 'and', 'me']);
        $this->assertSame('Test, Also testing, Still testing', (string) $messageGroup);
    }
}
