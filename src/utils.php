<?php

function format_time(DateTime $time): string
{
    $now = new DateTime();
    $diff = $time->diff($now);

    if ($diff->days == 0) {
        if ($diff->h == 0) {
            if ($diff->i == 0) {
                return "just now";
            }
            return $diff->i . " minutes ago";
        }
        return $diff->h . " hours ago";
    }
    if ($diff->days == 1) {
        return "yesterday";
    }
    if ($diff->days < 7) {
        return $diff->days . " days ago";
    }
    return $time->format("F j, Y g:i A");
}
