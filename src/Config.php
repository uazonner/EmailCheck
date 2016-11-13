<?php
namespace App;

class Config
{
    /**
     * @param string $key
     * @return string|bool
     */
    public static function pop3($key)
    {
        $pop3['host'] = 'ssl://pop.bigmir.net';
        $pop3['port'] = '995';

        if (key_exists($key, $pop3)) {
            return $pop3[$key];
        } else {
            return false;
        }
    }

    /**
     * @param string $key
     * @return string|bool
     */
    public static function smtp($key)
    {
        $smtp['host'] = 'smtp.bigmir.net';
        $smtp['port'] = 465;
        $smtp['smtpSecure'] = 'ssl';
        $smtp['charset'] = 'UTF-8';

        if (key_exists($key, $smtp)) {
            return $smtp[$key];
        } else {
            return false;
        }
    }

    /**
     * @param string $key
     * @return string|bool
     */
    public static function getUser($key)
    {
        $user['name'] = 'Check email app';
        $user['email'] = 'testinbox@bigmir.net';
        $user['pass'] = '123456qwerty';

        if (key_exists($key, $user)) {
            return $user[$key];
        } else {
            return false;
        }
    }
}