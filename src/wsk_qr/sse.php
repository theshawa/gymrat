<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

if (ob_get_level()) ob_end_clean();
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);

set_time_limit(0);
ignore_user_abort(true);



$file_path  = __DIR__ . '/QR.txt';

if (!file_exists($file_path)) {
    file_put_contents($file_path, '');
}

$current_qr_code = file_get_contents($file_path);

echo "event: connected\n";
echo "data: " . $current_qr_code . "\n\n";
flush();

while (true) {
    // Check if client disconnected
    if (connection_aborted()) {
        break;
    }

    if (file_get_contents($file_path) !== $current_qr_code) {
        $current_qr_code = file_get_contents($file_path);
        echo "event: qr_code_changed\n";
        echo "data: " . $current_qr_code . "\n\n";
        flush();
    }

    clearstatcache();

    sleep(1);
}
