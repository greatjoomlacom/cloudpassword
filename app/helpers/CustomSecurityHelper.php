<?php

namespace CustomHelpers;

class CustomSecurityHelper {

    /**
     * Random key
     * @param $len
     * @param bool $readable
     * @param bool $hash
     * @return string
     */
    public static function random_key($len = 10, $readable = false, $hash = false)
    {
        $key = '';

        if ($hash)
            $key = substr(sha1(uniqid(rand(), true)), 0, $len);
        else if ($readable)
        {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            for ($i = 0; $i < $len; ++$i)
                $key .= substr($chars, (mt_rand() % strlen($chars)), 1);
        }
        else
            for ($i = 0; $i < $len; ++$i)
                $key .= chr(mt_rand(33, 126));

        return $key;
    }

    /**
     * Get crypted password for htpasswd files
     * @param $plainpasswd
     * @return string
     */
    public static function cryptApr1Md5($plainpasswd)
    {
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $len  = strlen($plainpasswd);
        $text = $plainpasswd . '$apr1$' . $salt;
        $bin  = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $plainpasswd{0};
        }
        $bin = pack("H32", md5($text));
        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) $new .= $salt;
            if ($i % 7) $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }

        $tmp = '';
        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) $j = 5;
            $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
        }
        $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
            "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
        return "$" . "apr1" . "$" . $salt . "$" . $tmp;
    }
}
?>