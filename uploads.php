<?php

function upload_file(string $folder, array $file): false|string
{
    $target_dir = __DIR__ . "/uploads/$folder/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $ext = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));
    $new_name = uniqid() . ".$ext";
    $target_file = $target_dir . $new_name;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "$folder/$new_name";
    } else {
        return false;
    }
}

function delete_file(string $folder, string $file): bool
{
    $file = __DIR__ . "/uploads/$folder/" . basename($file);
    if (file_exists($file)) {
        return unlink($file);
    }
    return false;
}

function get_file_url(string $folder, string $file): string
{
    return "/uploads/$folder/" . basename($file);
}
