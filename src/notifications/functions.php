<?php

require_once __DIR__ . "/../db/models/Notification.php";
require_once __DIR__ . "/../db/models/NotificationUser.php";

function __new_notification_to_users(string $user_type, array $user_ids, string $title, string $message, ?DateTime $valid_till)
{
    $notification = new Notification();
    $notification->fill([
        "title" => $title,
        "message" => $message,
        "valid_till" => $valid_till
    ]);
    $notification->create();

    $notification_users = array_map(function ($user_id) use ($notification, $user_type) {
        $notification_user = new NotificationUser();
        $notification_user->fill([
            "user_id" => $user_id,
            "notification_id" => $notification->id,
            "user_type" => $user_type,
        ]);
        return $notification_user;
    }, $user_ids);

    $notification_user = new NotificationUser();
    $notification_user->bulk_create($notification_users);
}

function new_notification_to_rats(array $rat_ids, string $title, string $message, ?DateTime $valid_till)
{
    __new_notification_to_users("rat", $rat_ids, $title, $message, $valid_till);
}

function new_notification_to_trainers(array $trainer_ids, string $title, string $message, ?DateTime $valid_till)
{
    __new_notification_to_users("trainer", $trainer_ids, $title, $message, $valid_till);
}
