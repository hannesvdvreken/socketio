<?php
namespace Socketio\Contracts;

interface Adapter
{
    public function broadcast(array $packet, array $options);
}