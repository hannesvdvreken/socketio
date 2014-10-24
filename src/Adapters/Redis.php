<?php
namespace Socketio\Adapters;

use Predis\Client;
use Socketio\Contracts\Adapter;
use Socketio\Contracts\Msgpack;
use Socketio\Utils\Uid;

class Redis implements Adapter
{
    /**
     * @var Client
     */
    protected $predis;

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var Msgpack
     */
    private $msgpack;

    /**
     * @param Client  $predis
     * @param Msgpack $msgpack
     * @param string  $prefix
     */
    public function __construct(Client $predis = null, Msgpack $msgpack, $prefix = 'socket.io')
    {
        $this->predis  = $predis;
        $this->msgpack = $msgpack;
        $this->channel = $prefix . '#' . Uid::generate(6);
    }

    /**
     * @param array $packet
     * @param array $options
     *
     * @return $this
     */
    public function broadcast(array $packet, array $options)
    {
        // Encode to a string so that socket.io-redis can decode it.
        $data = $this->msgpack->encode([$packet, $options]);

        // Publish it on the channel.
        $this->predis->publish($this->channel, $data);

        // Allow chaining.
        return $this;
    }
}