<?php
namespace Socketio\Contracts;

use Closure;

interface Server
{
    /**
     * @param string $namespace
     * @param callable $callback
     *
     * @return Server
     */
    public function of($namespace, Closure $callback = null);

    /**
     * @param string $event
     * @param mixed $data
     *
     * @return Server
     */
    public function emit($event, $data);

    /**
     * @param string $room
     *
     * @return Server
     */
    public function in($room);

    /**
     * @param string $room
     *
     * @return Server
     */
    public function to($room);

    /**
     * @param Adapter $adapter
     *
     * @return Server
     */
    public function adapter(Adapter $adapter);
}