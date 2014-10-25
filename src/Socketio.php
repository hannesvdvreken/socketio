<?php
namespace Socketio;

use Closure;
use Socketio\Adapters\Memory;
use Socketio\Contracts\Adapter;
use Socketio\Contracts\Server;

class Socketio implements Server
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $rooms;

    /**
     * Message flags. Like ['broadcast' => true] to ignore rooms.
     *
     * @var array
     */
    protected $flags;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var int
     */
    const EVENT = 2;

    /**
     * @var int
     */
    const BINARY_EVENT = 5;

    /**
     * @param Adapter $adapter
     * @param string  $namespace
     */
    public function __construct(Adapter $adapter = null, $namespace = '/')
    {
        $this->adapter = $adapter ?: new Memory;
        $this->setNamespace($namespace);
        $this->rooms = [];
        $this->flags = [];
    }

    /**
     * @param string  $namespace
     * @param Closure $callback
     *
     * @return Server
     */
    public function of($namespace, Closure $callback = null)
    {
        // Clone this.
        $that = clone $this;

        // Assign it the new namespace.
        $that->setNamespace($namespace);

        // Call callback if given.
        if ($callback) {
            $callback($that);
        }

        // Return the other object.
        return $that;
    }

    /**
     * @param string $event
     * @param mixed  $data
     *
     * @return Server
     */
    public function emit($event, $data)
    {
        // Hardcoded for now.
        $type = self::EVENT; // Or BINARY_EVENT if one piece of data is binary.

        // Build the packet info array.
        $packet = [
            'type' => $type,
            'data' => [$event, $data],
            'nsp' => $this->namespace,
        ];

        // Broadcast it.
        $this->adapter->broadcast($packet, $this->getOptions());

        // Reset.
        $this->reset();

        return $this;
    }

    /**
     * @param string $room
     *
     * @return Server
     */
    public function in($room)
    {
        if (!in_array($room, $this->rooms)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    /**
     * @param string $room
     *
     * @return Server
     */
    public function to($room)
    {
        // Alias.
        return $this->in($room);
    }

    /**
     * @param Adapter $adapter
     *
     * @return Server
     */
    public function adapter(Adapter $adapter)
    {
        // Assign.
        $this->adapter = $adapter;

        // Allow chaining.
        return $this;
    }

    /**
     * @param string $namespace
     */
    protected function setNamespace($namespace)
    {
        $this->namespace = '/' . ltrim($namespace, '/');
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        //if ($this->rooms) {
            $options['rooms'] = $this->rooms ?: null;
        //}
        //if ($this->flags) {
            $options['flags'] = $this->flags ?: null;
        //}

        return $options;
    }

    /**
     * @return null
     */
    protected function reset()
    {
        $this->rooms = [];
        $this->flags = [];
    }
}
