A message collection library for responses.

It is compatible with the PSR LoggerInterface.

#### How to use Messages

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
