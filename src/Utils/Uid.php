<?php
namespace Socketio\Utils;

class Uid
{
    private static $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    public static function generate($length)
    {
        // Start empty.
        $uid = '';

        // Select a random char and append it to the string.
        for ($i = 0; $i < $length; $i++) {
            $uid .= self::$chars[mt_rand(0, strlen(self::$chars) - 1)];
        }

        // Return the result.
        return $uid;
    }
}