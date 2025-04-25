<?php

require_once __DIR__ . "/../db/models/Notification.php";

function __notify_user(string $user_type, int $user_id, string $title, string $message, string $source = "system", ?DateTime $valid_till = null)
{
    $notification = new Notification();
    $notification->fill([
        "title" => $title,
        "message" => $message,
        "receiver_id" => $user_id,
        "receiver_type" => $user_type,
        "source" => $source,
        "valid_till" => $valid_till,
    ]);
    $notification->create();
}

function notify_rat(int $rat_id, string $title, string $message, string $source = "system", ?DateTime $valid_till = null)
{
    __notify_user("rat", $rat_id, $title, $message, $source, $valid_till);
}

function notify_trainer(int $trainer_id, string $title, string $message, string $source = "system", ?DateTime $valid_till = null)
{
    __notify_user("trainer", $trainer_id, $title, $message, $source, $valid_till);
}

// Add staff notification function
function notify_staff($staff_id, $title, $message, $source = "system", $valid_till = null)
{
    __notify_user("staff", $staff_id, $title, $message, $source, $valid_till);
}