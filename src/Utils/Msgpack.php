<?php
namespace Socketio\Utils;

use Msgpack\Decoder;
use Msgpack\Encoder;
use Socketio\Contracts\Msgpack as MsgpackContract;

class Msgpack implements MsgpackContract
{
    /**
     * @var callable
     */
    private $encode;

    /**
     * @var callable
     */
    private $decode;

    public function __construct(Encoder $encoder = null, Decoder $decoder = null)
    {
        // Prefer C library because of speed.
        if (extension_loaded('msgpack')) {
            $this->encode = 'msgpack_pack';
            $this->decode = 'msgpack_unpack';
        } else {
            $this->encode = [$encoder, 'encode'];
            $this->decode = [$decoder, 'decode'];
        }
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    public function decode($string)
    {
        return call_user_func($this->decode, $string);
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function encode($data)
    {
        return call_user_func($this->encode, $data);
    }
}