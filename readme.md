# Socket.io messages from PHP

## What does it do?
This package allows you to transfer socket message to a Node.js process via Redis.
Socket.io has a [redis adapter]() to allow scaling a Node.js cluster to send socket messages to any client
connected to any process. We can use this to send messages straight from PHP.

This package provides a `Socketio\Socketio` class with similar methods as the socket.io server in Node.js such as
`emit`, `in`, `to`, `of` and `adapter`.

## Usage

First we can create a `Socketio\Socketio` object.

```php
$io = new Socketio\Socketio;
```

It has some of the same methods as in Node.js:

### Namespace

```php
$chats = $io->of('/chats');

$io->emit('general', $message);
$chats->emit('user.connected', $user);
```

Setup a server object with a different namespace. The `$io` object keeps the same namespace.
Or use a callback:

```php
$io->of('/chats', function ($chats) {
    $chats->emit('user.connected', $user);
});

$io->emit('general', $message);
```

### Rooms
Send to specific room(s):

```php
$io->to('room-a')->emit('event', $message);
$io->to('room-a')->to('room-b')->emit('event', $message);
$io->in('room-c')->emit('event', $message);
```

Where `in` is an alias of `to`. You can send to several rooms at the same time by chaining `to` calls.
The rooms are reset after every `emit` call. This is similar behaviour as in Node.js.

### Adapters

By default, the `Socketio\Socketio` object configures a `Memory` adapter.
This adapter fakes socket message delivery and can be useful for testing/stubbing.

To setup the `Redis` adapter you need to configure some dependencies first.

```php
$predis = new Predis\Client;
$msgpack = new Socketio\Utils\Msgpack;

$adapter = new Socketio\Adapters\Redis($predis, $msgpack);
$io->adapter($adapter);
```

If you have `ext-msgpack` installed, the Socketio\Utils\Msgpack will use the native
`msgpack_pack` and `msgpack_unpack` methods which are supposed to be the fastest around.

If not, you can pass it `Msgpack\Encoder` and `Msgpack\Decoder` objects to do the encoding/decoding.
These come pre-installed with composer if you require this package.

```php
$msgpack = new Socketio\Utils\Msgpack(new Msgpack\Encoder, new Msgpack\Decoder);
```

## The Node.js process

```js
var app = require('express')();
var socketio = require('socket.io');
var adapter = require('socket.io-redis');

var server = app.listen(process.env.NODE_PORT || 9000);

io = socketio(server);
io.adapter(adapter());
```

Right now, you're good to go!

## Contributing

Stick to
[PSR-2 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
and be nice in communication.

## License

[MIT](license)