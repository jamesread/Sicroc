<?php

namespace Sicroc;

function implodeQuoted($a, $quoteChar = '"', $useNulls = false)
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

function redirect($url)
{
    header('Location:' . $url);
}
