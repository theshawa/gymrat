<?php

require_once __DIR__ . "/../db/models/Notification.php";
require_once __DIR__ . "/../db/models/Announcement.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once "../config.php";
require_once "../utils.php";

function get(int $user_id, string $user_type): array
{
    $notification = new Notification();
    $announcement = new Announcement();

    try {
        $notifications =  $notification->get_all_of_user($user_id, $user_type);
        $announcements =  $announcement->get_all_of_user($user_type, $user_id);

        // 5 mins buffer time check
        $announcements = array_filter($announcements, function ($announcement) {
            $now = new DateTime();
            $created_at = $announcement->created_at;
            $duration = $created_at->diff($now);
            $mins = $duration->days * 24 * 60 + $duration->h * 60 + $duration->i;
            return $mins > ANNOUNCEMENT_INVISIBLE_FROM_CREATION_UPTO;
        },);

        $data_1 = array_map(function ($notification) {
            return [
                "id" => $notification->id,
                "title" => $notification->title,
                "message" => $notification->message,
                "created_at" => format_time($notification->created_at, true),
                "valid_till" => $notification->valid_till ? $notification->valid_till->format("Y-m-d H:i:s") : null,
                "is_read" => $notification->is_read,
                "source" => $notification->source,
                "type" => "notification",
            ];
        }, $notifications);

        $data_2 = array_map(function ($announcement) {
            return [
                "id" => $announcement->id,
                "title" => $announcement->title,
                "message" => $announcement->message,
                "created_at" => format_time($announcement->created_at, true),
                "valid_till" => $announcement->valid_till ? $announcement->valid_till->format("Y-m-d H:i:s") : null,
                "is_read" => false,
                "source" => $announcement->source,
                "type" => "announcement",
            ];
        }, $announcements);

        return [
            "success" => true,
            "data" => array_merge($data_1, $data_2),
        ];
    } catch (\Throwable $th) {
        return [
            "success" => false,
            "data" => $th->getMessage(),
        ];
    }
}

function delete(int $user_id, string $user_type)
{
    $notification = new Notification();
    try {
        $notification->delete_all_of_user($user_id, $user_type);
        return [
            "success" => true,
            "data" => "deleted",
        ];
    } catch (Exception $th) {
        return [
            "success" => false,
            "data" => $th->getMessage(),
        ];
    }
}

$method = $_SERVER["REQUEST_METHOD"];
$function_name = strtolower($method);

if (function_exists($function_name)) {
    header("Content-Type: application/json");

    if (!isset($_SESSION['auth'])) {
        echo json_encode([
            "success" => false,
            "data" => "unauthorized",
        ]);
        return;
    }

    $user_id = $_SESSION['auth']['id'];
    $user_type = $_SESSION['auth']['role'];

    ob_start();

    $res = $function_name($user_id, $user_type);

    ob_clean();

    http_response_code(200);
    echo json_encode($res);
}
