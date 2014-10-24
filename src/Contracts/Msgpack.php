<?php
namespace Socketio\Contracts;

interface Msgpack
{
    /**
     * @param $string
     *
     * @return mixed
     */
    public function decode($string);

    /**
     * @param $data
     *
     * @return string
     */
    public function encode($data);
}