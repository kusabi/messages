[![Release Badge](https://img.shields.io/github/release/kusabi/messages.svg)](https://img.shields.io/github/release/kusabi/messages.svg)
[![Tag Badge](https://img.shields.io/github/tag/kusabi/messages.svg)](https://img.shields.io/github/tag/kusabi/messages.svg)
[![Licence Badge](https://img.shields.io/github/license/kusabi/messages.svg)](https://img.shields.io/github/license/kusabi/messages.svg)
[![Issues Badge](https://img.shields.io/github/issues/kusabi/messages.svg)](https://img.shields.io/github/issues/kusabi/messages.svg)
[![Codacy Badge](https://img.shields.io/codacy/grade/7ec4d964189f4da3845b80c009f8d8fe.svg)](https://img.shields.io/codacy/grade/7ec4d964189f4da3845b80c009f8d8fe.svg)
[![Code Size](https://img.shields.io/github/languages/code-size/kusabi/messages.svg)](https://img.shields.io/github/languages/code-size/kusabi/messages.svg)

A message collection library for responses.

It is compatible with the PSR LoggerInterface.

# How to use Messages

A message is the smallest unit in the library.

It contains a message and a context. 

The message is a `string` and the context is an `array`.

All manipulator methods return the instance of Message for fluid chaining.

`$message->setMessage('Success')->setContext([1, 2, 3]);`

```php
use Kusabi\Messages\Message;

// Creating a blank message
$message = new Message();

// Creating a message with a simple success string
$message = new Message('Successfully retrieved users');

// Creating a message with context.
// Here the context suggests we are only retriving recent users.
$message = new Message('Successfully retrieved users', [
    'created' => 'recent'
]);

// Updating the message after creation
$message->setMessage('Changed the message');
$message->setContext([
    'created' => 'recent',
    'changed_at' => date('Y-m-d H:i:s')
]);

// Merging more context data into the context array
$message->mergeContext([
    'changed_at' => date('Y-m-d H:i:s'),
    'reason' => 'merged'
]);

// Add more entries to the context using a key and value
$message->addContext('php', '7.2');

// Retrieving the values
$message->getMessage();
$message->getContext();

// Json encode the message
echo json_encode($message); // {"message":"Changed the message", "context":{...}}

// Cast as string just returns the message
echo (string) $message; // "Changed the message"
```

# How to use Message Groups

A message group is a collection of messages.

It is an array wrapper around this collection of message.

All manipulator methods return the instance of MessageGroup for fluid chaining.

```php
$messageGroup->addMessage('Success', [])->clearMessages()->count();
```

```php
// Creating a Message Group
$messageGroup = new MessageGroup();
$messageGroup->addMessage('Test Message', [
    'context' => 1
]);
$messageGroup->addMessageInstance(
    new Message('Test message')
);
$messageGroup[] = new Message('Test');

// Clearing the message group
$messageGroup->setMessages([]);
$messageGroup->clearMessages();

// Accessing Message Group properties
if (count($messageGroup) > 0) {
    foreach($messageGroup as $message) {
        echo $message->getMessage();
    }
    $messageGroup->clearMessages();
}

// Json encode the message group
echo json_encode($messageGroup); // [{"message":"First Message", "context":{...}},{"message":"Second Message", "context":{...}}]

// Cast as string just returns the messages
echo (string) $messageGroup; // "First Message, Second Message"
```

# How to use Message Collections

A message collection is a collection of message groups.

It has one for each verbosity level.

It acts as an array wrapper around this collection of message groups.

All manipulator methods return the instance of MessageCollection for fluid chaining.

```php
$messageCollection
    ->success('It worked')
    ->notice('We will be experiencing down time shortly', [
        'start' => '1970-01-01 12:00:00'
    ]);
```

```php
// Creating a Message Collection
$messageCollection = new MessageCollection();
$messageCollection->info('We will be experiencing downtime next Sunday', [
    'timestamp' => $nextWeek->getTimestamp()
]);
$messageCollection->error('Failed to connect to database', [
    'host' => '127.0.0.1',
    'password' => 'secret'
]);

// Accessing Message Collection properties
$messageCollection->getMessageGroups();
$messageCollection->getMessages();
```
