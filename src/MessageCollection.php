<?php

namespace Kusabi\Messages;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Kusabi\Messages\Exceptions\InvalidVerbosityException;

/**
 * A message collection is a collection of message groups in a format ready for responses.
 *
 * It has one group for each level of verbosity.
 *
 * It is compatible with the PSR LoggerInterface
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
class MessageCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, MessageCollectionInterface, MessageLoggerInterface
{
    /**
     * Verbosity level of debug messages
     *
     * @var integer
     */
    const DEBUG = 100;

    /**
     * Verbosity level of info messages
     *
     * @var integer
     */
    const INFO = 200;

    /**
     * Verbosity level of success messages
     *
     * @var integer
     */
    const SUCCESS = 250;

    /**
     * Verbosity level of notice messages
     *
     * @var integer
     */
    const NOTICE = 300;

    /**
     * Verbosity level of validation messages
     *
     * @var integer
     */
    const VALIDATION = 350;

    /**
     * Verbosity level of warning messages
     *
     * @var integer
     */
    const WARNING = 400;

    /**
     * Verbosity level of error messages
     *
     * @var integer
     */
    const ERROR = 500;

    /**
     * Verbosity level of critical messages
     *
     * @var integer
     */
    const CRITICAL = 600;

    /**
     * Verbosity level of alert messages
     *
     * @var integer
     */
    const ALERT = 700;

    /**
     * Verbosity level of emergency messages
     *
     * @var integer
     */
    const EMERGENCY = 800;

    /**
     * The debug message group
     *
     * @var MessageGroup
     */
    protected $debug;

    /**
     * The info message group
     *
     * @var MessageGroup
     */
    protected $info;

    /**
     * The success message group
     *
     * @var MessageGroup
     */
    protected $success;

    /**
     * The notice message group
     *
     * @var MessageGroup
     */
    protected $notice;

    /**
     * The validation message group
     *
     * @var MessageGroup
     */
    protected $validation;

    /**
     * The warning message group
     *
     * @var MessageGroup
     */
    protected $warning;

    /**
     * The error message group
     *
     * @var MessageGroup
     */
    protected $error;

    /**
     * The critical message group
     *
     * @var MessageGroup
     */
    protected $critical;

    /**
     * The alert message group
     *
     * @var MessageGroup
     */
    protected $alert;

    /**
     * The emergency message group
     *
     * @var MessageGroup
     */
    protected $emergency;

    /**
     * MessageCollection constructor.
     */
    public function __construct()
    {
        $this->debug = new MessageGroup();
        $this->info = new MessageGroup();
        $this->success = new MessageGroup();
        $this->notice = new MessageGroup();
        $this->validation = new MessageGroup();
        $this->warning = new MessageGroup();
        $this->error = new MessageGroup();
        $this->critical = new MessageGroup();
        $this->alert = new MessageGroup();
        $this->emergency = new MessageGroup();
    }

    /**
     * Convert this message collection to a string
     *
     * @return string
     */
    public function __toString()
    {
        return implode(', ', array_map(function (MessageGroupInterface $messageGroup) {
            return (string) $messageGroup;
        }, $this->getMessageGroups()));
    }

    /**
     * Get the debug message group
     *
     * @return MessageGroup
     */
    public function getDebugMessageGroup(): MessageGroup
    {
        return $this->debug;
    }

    /**
     * Get the info message group
     *
     * @return MessageGroup
     */
    public function getInfoMessageGroup(): MessageGroup
    {
        return $this->info;
    }

    /**
     * Get the success message group
     *
     * @return MessageGroup
     */
    public function getSuccessMessageGroup(): MessageGroup
    {
        return $this->success;
    }

    /**
     * Get the notice message group
     *
     * @return MessageGroup
     */
    public function getNoticeMessageGroup(): MessageGroup
    {
        return $this->notice;
    }

    /**
     * Get the validation message group
     *
     * @return MessageGroup
     */
    public function getValidationMessageGroup(): MessageGroup
    {
        return $this->validation;
    }

    /**
     * Get the warning message group
     *
     * @return MessageGroup
     */
    public function getWarningMessageGroup(): MessageGroup
    {
        return $this->warning;
    }

    /**
     * Get the error message group
     *
     * @return MessageGroup
     */
    public function getErrorMessageGroup(): MessageGroup
    {
        return $this->error;
    }

    /**
     * Get the critical message group
     *
     * @return MessageGroup
     */
    public function getCriticalMessageGroup(): MessageGroup
    {
        return $this->critical;
    }

    /**
     * Get the alert message group
     *
     * @return MessageGroup
     */
    public function getAlertMessageGroup(): MessageGroup
    {
        return $this->alert;
    }

    /**
     * Get the emergency message group
     *
     * @return MessageGroup
     */
    public function getEmergencyMessageGroup(): MessageGroup
    {
        return $this->emergency;
    }

    /**
     * Get a message group using the verbosity level string
     *
     * @param string $level
     *
     * @throws InvalidVerbosityException if verbosity level does not exist
     *
     * @return MessageGroup
     *
     */
    public function getMessageGroup(string $level): MessageGroup
    {
        if (!method_exists($this, $level)) {
            throw new InvalidVerbosityException($level);
        }
        return $this->$level;
    }

    /**
     * {@inheritdoc}
     *
     * @see MessageCollectionInterface::getMessageGroups()
     */
    public function getMessageGroups(): array
    {
        return [
            'debug' => $this->debug,
            'info' => $this->info,
            'success' => $this->success,
            'notice' => $this->notice,
            'validation' => $this->validation,
            'warning' => $this->warning,
            'error' => $this->error,
            'critical' => $this->critical,
            'alert' => $this->alert,
            'emergency' => $this->emergency,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * Messages are sorted by highest verbosity level first
     *
     * @see MessageCollectionInterface::getMessages()
     */
    public function getMessages(): array
    {
        $results = [];
        foreach ($this->getMessageGroups() as $messageGroup) {
            $results = array_merge($messageGroup->getMessages(), $results);
        }
        return $results;
    }

    /**
     * Add multiple messages to the collection in one call using a format similar to that returned by `getLogMessages()`
     *
     * Each entry of the array must be an array with keys `level`, `message` and `context` (without level it with fail but without message and context the messages created will be empty)
     *
     * @param array $messages
     *
     * @throws InvalidArgumentException if any entries in the array ar not an array
     * @throws InvalidVerbosityException if any of the log verbosity's are missing or invalid
     *
     * @return self
     */
    public function addMessages(array $messages)
    {
        foreach ($messages as $message) {
            // Each entry must be an array
            if (!is_array($message)) {
                throw new InvalidArgumentException('Entries within the messages array must all by arrays');
            }

            // Log verbosity must exist and be valid
            if (!isset($message['level']) || !method_exists($this, $message['level'])) {
                throw new InvalidVerbosityException($message['level'] ?? '');
            }

            // Add the message
            $this->log(
                $message['level'],
                $message['message'] ?? '',
                $message['context'] ?? []
            );
        }
        return $this;
    }

    /**
     * Get an array of messages in a format friendly to a LoggerInterface implementation
     *
     * @return array
     */
    public function getLogMessages(): array
    {
        $results = [];
        foreach ($this->getMessageGroups() as $level => $messageGroup) {

            // Get a LoggerInterface valid level
            switch ($level) {
                case 'success': $level = 'info'; break;
                case 'validation': $level = 'notice'; break;
            }
            $levelVerbosity = constant('self::'.strtoupper($level));

            // Messages
            $messages = json_decode(json_encode($messageGroup->jsonSerialize()), true);

            // Add the log level string and value to the messages
            $results = array_merge(array_map(function ($message) use ($level, $levelVerbosity) {
                return array_merge([
                    'level' => $level,
                    'verbosity' => $levelVerbosity
                ], $message);
            }, $messages), $results);
        }

        // return the array with level and verbosity
        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::debug()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function debug($message, array $context = [])
    {
        $this->debug->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::info()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function info($message, array $context = [])
    {
        $this->info->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::success()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function success(string $message, array $context = [])
    {
        $this->success->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::notice()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function notice($message, array $context = [])
    {
        $this->notice->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::validation()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function validation(string $message, array $context = [])
    {
        $this->validation->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::warning()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function warning($message, array $context = [])
    {
        $this->warning->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::error()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function error($message, array $context = [])
    {
        $this->error->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::critical()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function critical($message, array $context = [])
    {
        $this->critical->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::alert()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function alert($message, array $context = [])
    {
        $this->alert->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     *
     * @see MessageLoggerInterface::emergency()
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function emergency($message, array $context = [])
    {
        $this->emergency->addMessage($message, $context);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidVerbosityException if verbosity level is invalid
     *
     * @return self
     *
     * @see MessageLoggerInterface::log()
     *
     *
     * @phan-suppress PhanParamSignatureMismatch
     */
    public function log($level, $message, array $context = [])
    {
        if (!method_exists($this, $level)) {
            throw new InvalidVerbosityException($level);
        }
        return $this->$level($message, $context);
    }

    /**
     * {@inheritdoc}
     *
     * Used for looping through the message groups in the collection
     *
     * @return ArrayIterator
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getMessageGroups());
    }

    /**
     * {@inheritdoc}
     *
     * Determine if an offset exists within the message groups
     *
     * Allows the group to be used in PHPs `isset($messageCollection['success'])`
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->getMessageGroups()[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * Access a message group by it's verbosity level
     *
     * Allows access to the message groups as an array `$messageCollection['success']`
     *
     * @see ArrayAccess::offsetGet()
     *
     * @return MessageGroupInterface
     */
    public function offsetGet($offset)
    {
        return $this->getMessageGroups()[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * Set a message group by it's array offset verbosity level
     *
     * Allows access to the messages as an array `$messageCollection['success'] = new MessageGroup()`
     *
     * @see ArrayAccess::offsetSet()
     *
     * @throws InvalidVerbosityException if offset does not exist
     * @throws InvalidArgumentException if value does not implement MessageGroupInterface
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidVerbosityException($offset);
        }
        if (!is_object($value) || !$value instanceof MessageGroupInterface) {
            throw new InvalidArgumentException('Only message groups can be added to a message collection');
        }
        $this->$offset = $value;
    }

    /**
     * {@inheritdoc}
     *
     * Unset a message group by it's array offset verbosity level
     *
     * This only clears the messages in that group
     *
     * Allows access to the messages as an array `unset($messageCollection['success'])`
     *
     * @see ArrayAccess::offsetUnset()
     *
     * @throws InvalidVerbosityException if offset does not exist
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidVerbosityException($offset);
        }
        $this->$offset->clearMessages();
    }

    /**
     * Returns the total number of message groups in the collection
     *
     * Allows the class to be used in PHPs `count()` method
     *
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->getMessageGroups());
    }

    /**
     * {@inheritdoc}
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->getMessageGroups();
    }
}
