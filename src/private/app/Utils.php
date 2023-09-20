<?php

namespace Sicroc;

abstract class Utils
{
    public static function implodeQuoted(array $a, string $quoteChar = '"', bool $useNulls = false): string
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

    public static function redirect(string $url, string $message): void
    {
        header('Location:' . $url);
        echo $message;
    }

    public static function getSiteSetting(string $searchKey): mixed
    {
        global $settings;

        if (empty($settings)) {
            $sql = 'SELECT setting_key, setting_value FROM site_settings';
            $stmt = \libAllure\DatabaseFactory::getInstance()->query($sql);

            $settings = [];
            global $settings;

            foreach ($stmt->fetchAll() as $setting) {
                $settings[$setting['setting_key']] = $setting['setting_value'];
            }
        }

        if (isset($settings[$searchKey])) {
            return $settings[$searchKey];
        } else {
            return false; // Most settings are feature flags
        }
    }
}
