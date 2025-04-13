<?php

require_once __DIR__ . "/../db/models/NotificationUser.php";
require_once __DIR__ . "/../db/models/Notification.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function get(int $user_id, string $user_type): array
{
    $notification = new Notification();
    try {
        $data =  $notification->get_all_of_user($user_id, $user_type);
    } catch (\Throwable $th) {
        return [
            "success" => false,
            "data" => $th->getMessage(),
        ];
    }

    return [
        "success" => true,
        "data" => array_map(function ($notification) {
            return [
                "id" => $notification->id,
                "title" => $notification->title,
                "message" => $notification->message,
                "created_at" => $notification->created_at->format("Y-m-d H:i:s"),
                "valid_till" => $notification->valid_till ? $notification->valid_till->format("Y-m-d H:i:s") : null,
            ];
        }, $data),
    ];
}

function delete(int $user_id, string $user_type)
{
    $notification = new NotificationUser();
    try {
        $notification->delete_all_of_user($user_id, $user_type);
    } catch (Exception $th) {
        return [
            "success" => false,
            "data" => $th->getMessage(),
        ];
    }

    return [
        "success" => true,
        "data" => "deleted",
    ];
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

    $res = $function_name($user_id, $user_type);

    http_response_code(200);
    echo json_encode($res);
}
