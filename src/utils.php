<?php

function format_time(DateTime $time, $relative_time = false): string
{
    if (!$relative_time) {
        return $time->format("F j, Y g:i A");
    }
    $now = new DateTime();
    $diff = $time->diff($now);

    if ($diff->days == 0) {
        if ($diff->h == 0) {
            if ($diff->i == 0) {
                return "just now";
            }
            return $diff->i . " minute" . ($diff->i === 1 ? '' : 's') . " ago";
        }
        return $diff->h . " hour" . ($diff->h === 1 ? '' : 's') . " ago";
    }
    if ($diff->days == 1) {
        return "yesterday";
    }
    $count = $diff->days;
    $item = "day";

    if ($diff->days < 7) {
        $count = $diff->days;
        $item = "day";
    } else if ($diff->days < 30) {
        $count = floor($diff->days / 7);
        $item = "week";
    } else if ($diff->days < 365) {
        $count = floor($diff->days / 30);
        $item = "month";
    } else {
        $count = floor($diff->days / 365);
        $item = "year";
    }
    if ($count > 1) {
        $item .= "s";
    }
    return "$count $item ago";
}
