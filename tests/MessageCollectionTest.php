<?php

namespace Tests;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Kusabi\Messages\InvalidVerbosityException;
use Kusabi\Messages\MessageCollection;
use Kusabi\Messages\MessageCollectionInterface;
use Kusabi\Messages\MessageGroup;
use Kusabi\Messages\MessageGroupInterface;
use Kusabi\Messages\MessageLoggerInterface;
use PHPUnit\Framework\TestCase;

class MessageCollectionTest extends TestCase
{
    const VALID_VERBOSITY_LEVELS = [
        'debug',
        'info',
        'success',
        'notice',
        'validation',
        'warning',
        'error',
        'critical',
        'alert',
        'emergency'
    ];

    public function testInstantiate()
    {
        $messageCollection = new MessageCollection();
        $this->assertInstanceOf(MessageCollectionInterface::class, $messageCollection);
        $this->assertInstanceOf(MessageLoggerInterface::class, $messageCollection);
        $this->assertInstanceOf(ArrayAccess::class, $messageCollection);
        $this->assertInstanceOf(Countable::class, $messageCollection);
        $this->assertInstanceOf(IteratorAggregate::class, $messageCollection);
        $this->assertInstanceOf(JsonSerializable::class, $messageCollection);
    }

    public function testCanAddMessages()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);

        $this->assertCount(1, $messageCollection->getDebugMessageGroup());
        $this->assertCount(1, $messageCollection->getInfoMessageGroup());
        $this->assertCount(1, $messageCollection->getSuccessMessageGroup());
        $this->assertCount(1, $messageCollection->getNoticeMessageGroup());
        $this->assertCount(1, $messageCollection->getValidationMessageGroup());
        $this->assertCount(1, $messageCollection->getWarningMessageGroup());
        $this->assertCount(1, $messageCollection->getErrorMessageGroup());
        $this->assertCount(1, $messageCollection->getCriticalMessageGroup());
        $this->assertCount(1, $messageCollection->getAlertMessageGroup());
        $this->assertCount(1, $messageCollection->getEmergencyMessageGroup());

        $this->assertCount(1, $messageCollection->getMessageGroup('debug'));
        $this->assertCount(1, $messageCollection->getMessageGroup('info'));
        $this->assertCount(1, $messageCollection->getMessageGroup('success'));
        $this->assertCount(1, $messageCollection->getMessageGroup('notice'));
        $this->assertCount(1, $messageCollection->getMessageGroup('validation'));
        $this->assertCount(1, $messageCollection->getMessageGroup('warning'));
        $this->assertCount(1, $messageCollection->getMessageGroup('error'));
        $this->assertCount(1, $messageCollection->getMessageGroup('critical'));
        $this->assertCount(1, $messageCollection->getMessageGroup('alert'));
        $this->assertCount(1, $messageCollection->getMessageGroup('emergency'));
    }

    public function testAccessMessageGroupsThroughVerbosityLevels()
    {
        $messageCollection = new MessageCollection();
        foreach (self::VALID_VERBOSITY_LEVELS as $level) {
            $this->assertCount(0, $messageCollection->getMessageGroup($level));
        }
    }

    public function testAccessMessageGroupsThroughVerbosityLevelsThrowsExceptionForInvalidLevel()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level 'not-real'");
        $messageCollection = new MessageCollection();
        $messageCollection->getMessageGroup('not-real');
    }

    public function testLogWithVerbosityLevel()
    {
        $messageCollection = new MessageCollection();
        foreach (self::VALID_VERBOSITY_LEVELS as $level) {
            $this->assertCount(1, $messageCollection->log($level, 'test')->getMessageGroup($level));
        }
    }

    public function testLogWithVerbosityLevelThrowsExceptionForInvalidLevel()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level 'not-real'");
        $messageCollection = new MessageCollection();
        $messageCollection->log('not-real', 'test');
    }

    public function testIterateThroughMessages()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);

        $levels = [
            'debug',
            'info',
            'success',
            'notice',
            'validation',
            'warning',
            'error',
            'critical',
            'alert',
            'emergency'
        ];

        foreach ($messageCollection as $level => $messageGroup) {
            $this->assertSame($level, current($levels));
            $this->assertCount(1, $messageGroup);
            $this->assertInstanceOf(MessageGroupInterface::class, $messageGroup);
            next($levels);
        }
    }

    public function testAccessLevelAsArray()
    {
        $messageCollection = new MessageCollection();
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['debug']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['info']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['success']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['notice']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['validation']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['warning']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['error']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['critical']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['alert']);
        $this->assertInstanceOf(MessageGroupInterface::class, $messageCollection['emergency']);
    }

    public function testUnsetClearsGroups()
    {
        $messageCollection = new MessageCollection();
        foreach (self::VALID_VERBOSITY_LEVELS as $level) {
            $messageCollection->log($level, 'Debug Message', [1, 2, 3]);
            $this->assertCount(1, $messageCollection->getMessageGroup($level));
            unset($messageCollection[$level]);
            $this->assertCount(0, $messageCollection->getMessageGroup($level));
        }
    }

    public function testUnsetThrowsExceptionForInvalidVerbosityLevel()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level 'not-real'");
        $messageCollection = new MessageCollection();
        unset($messageCollection['not-real']);
    }

    public function testCountIsSizeOfGroupsArray()
    {
        $messageCollection = new MessageCollection();
        $this->assertCount(count(self::VALID_VERBOSITY_LEVELS), $messageCollection);
    }

    public function testJsonEncode()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);

        $this->assertSame(
            '{"debug":[{"message":"Debug Message","context":[1,2,3]}],"info":[{"message":"Info Message","context":[1,2,3]}],"success":[{"message":"Success Message","context":[1,2,3]}],"notice":[{"message":"Notice Message","context":[1,2,3]}],"validation":[{"message":"Validation Message","context":[1,2,3]}],"warning":[{"message":"Warning Message","context":[1,2,3]}],"error":[{"message":"Error Message","context":[1,2,3]}],"critical":[{"message":"Critical Message","context":[1,2,3]}],"alert":[{"message":"Alert Message","context":[1,2,3]}],"emergency":[{"message":"Emergency Message","context":[1,2,3]}]}',
            json_encode($messageCollection)
        );
    }

    public function testCastToString()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->debug('Debug Message 2', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);

        $this->assertSame(
            'Debug Message, Debug Message 2, Info Message, Success Message, Notice Message, Validation Message, Warning Message, Error Message, Critical Message, Alert Message, Emergency Message',
            (string) $messageCollection
        );
    }

    public function testGetMessagesGetsAllMessagesIntoCollapsedArrayOrderedByHighestPriority()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->debug('Debug Message 2', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message 2', [1, 2, 3]);

        $messages = $messageCollection->getMessages();
        $this->assertCount(12, $messages);
        $this->assertSame('Emergency Message', (string) $messages[0]);
    }

    public function testGetLogMessagesAddsLevelToArray()
    {
        $messageCollection = new MessageCollection();
        $messageCollection->debug('Debug Message', [1, 2, 3]);
        $messageCollection->debug('Debug Message 2', [1, 2, 3]);
        $messageCollection->info('Info Message', [1, 2, 3]);
        $messageCollection->success('Success Message', [1, 2, 3]);
        $messageCollection->notice('Notice Message', [1, 2, 3]);
        $messageCollection->validation('Validation Message', [1, 2, 3]);
        $messageCollection->warning('Warning Message', [1, 2, 3]);
        $messageCollection->error('Error Message', [1, 2, 3]);
        $messageCollection->critical('Critical Message', [1, 2, 3]);
        $messageCollection->alert('Alert Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message', [1, 2, 3]);
        $messageCollection->emergency('Emergency Message 2', [1, 2, 3]);

        $messages = $messageCollection->getLogMessages();
        $this->assertCount(12, $messages);
        $this->assertSame([
            'level' => 'emergency',
            'verbosity' => 800,
            'message' => 'Emergency Message',
            'context' => [
                0 => 1,
                1 => 2,
                2 => 3
            ]

        ], $messages[0]);
    }

    public function testSetMessageGroupAsArray()
    {
        $messageCollection = new MessageCollection();
        foreach (self::VALID_VERBOSITY_LEVELS as $level) {
            $messageCollection->log($level, 'test');
            $messageCollection[$level] = new MessageGroup();
            $this->assertCount(0, $messageCollection->getMessageGroup($level));
        }
    }

    public function testSetMessageGroupAsArrayMustBeMessageGroupInterface()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only message groups can be added to a message collection');
        $messageCollection = new MessageCollection();
        $messageCollection['success'] = 'not a message group instance';
    }

    public function testSetMessageGroupAsArrayThrowsExceptionForInvalidVerbosityLevel()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level 'not-real'");
        $messageCollection = new MessageCollection();
        $messageCollection['not-real'] = new MessageGroup();
    }

    public function testAddMessagesRebuildsFromGetLogMessagesExactly()
    {
        $messageCollection = new MessageCollection();
        foreach (self::VALID_VERBOSITY_LEVELS as $level) {
            // Ignore success and validation as they are transformed for LoggerInterface
            if ($level === 'success' || $level === 'validation') {
                continue;
            }
            $messageCollection->log($level, 'Test One');
            $messageCollection->log($level, 'Test Two', [1, 2, 3]);
        }

        $messageCollectionTwo = new MessageCollection();
        $messageCollectionTwo->addMessages(
            $messageCollection->getLogMessages()
        );

        $this->assertSame(json_encode($messageCollection), json_encode($messageCollectionTwo));
    }

    public function testAddMessagesEntryCannotBeAString()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entries within the messages array must all by arrays');
        $messageCollection = new MessageCollection();
        $messageCollection->addMessages([
            'not an array'
        ]);
    }

    public function testAddMessagesEntryCannotBeANumber()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entries within the messages array must all by arrays');
        $messageCollection = new MessageCollection();
        $messageCollection->addMessages([
            1
        ]);
    }

    public function testAddMessagesEntryCannotBeAnObject()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entries within the messages array must all by arrays');
        $messageCollection = new MessageCollection();
        $messageCollection->addMessages([
            new \stdClass()
        ]);
    }

    public function testAddMessagesThrowsExceptionIfVerbosityLevelIsMissing()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level ''");
        $messageCollection = new MessageCollection();
        $messageCollection->addMessages([
            ['message' => 'success', 'context' => []]
        ]);
    }

    public function testAddMessagesThrowsExceptionIfVerbosityLevelIsInvalid()
    {
        $this->expectException(InvalidVerbosityException::class);
        $this->expectExceptionMessage("Invalid verbosity level 'not-real'");
        $messageCollection = new MessageCollection();
        $messageCollection->addMessages([
            ['level' => 'not-real', 'message' => 'success', 'context' => []]
        ]);
    }
}
