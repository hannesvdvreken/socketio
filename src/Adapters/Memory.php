<?php
namespace Socketio\Adapters;

use Socketio\Contracts\Adapter;

class Memory implements Adapter
{
    /**
     * @param array $packet
     * @param array $options
     *
     * @return $this
     */
    public function broadcast(array $packet, array $options)
    {
        return $this;
    }
}