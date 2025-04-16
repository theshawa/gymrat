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

$last_sent_wsk = "";

require_once "../db/models/WorkoutSessionKey.php";
$wsk = new WorkoutSessionKey();

echo "event: connected\n";
echo "data: " . $current_qr_code . "\n\n";
flush();

while (true) {
    // Check if client disconnected
    if (connection_aborted()) {
        break;
    }

    try {
        $wsk->get_one();
        $loaded_wsk = $wsk->session_key;
        if ($loaded_wsk !== $last_sent_wsk) {
            echo "event: qr_code_changed\n";
            echo "data: " . $loaded_wsk . "\n\n";
            flush();
            $last_sent_wsk = $loaded_wsk;
        }
    } catch (Exception $e) {
        echo "event: error\n";
        echo "data: " . $e->getMessage() . "\n\n";
        flush();
    }

    clearstatcache();

    sleep(1);
}
