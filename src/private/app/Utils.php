<?php

namespace Sicroc;

abstract class Utils
{
    public static function implodeQuoted($a, $quoteChar = '"', $useNulls = false)
    {
        $ret = "";

        for ($i = 0; $i < sizeof($a); $i++) {
            if (empty($a[$i]) && $useNulls) {
                $ret .= 'NULL';
            } else {
                $ret .= $quoteChar . $a[$i] . $quoteChar;
            }

            if ($i + 1 != sizeof($a)) {
                $ret .= ', ';
            }
        }

        return $ret;
    }

    public static function redirect($url)
    {
        header('Location:' . $url);
    }
}
