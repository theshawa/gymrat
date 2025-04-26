<?php

class Logger
{
    private const log_file = __DIR__ . "/app.log";
    public static function log(...$items)
    {
        if (!file_exists(self::log_file)) {
            file_put_contents(self::log_file, "");
        }
        $time = date("Y-m-d H:i:s");
        ob_start();
        foreach ($items as $item) {
            if (is_array($item) || is_object($item)) {
                var_dump($item);
            } else {
                echo $item . " ";
            }
        }
        $msg = ob_get_clean();
        $msg = "[$time] $msg";
        error_log($msg . "\n", 3, self::log_file);
    }
}
