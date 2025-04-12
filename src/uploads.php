<?php

function upload_file(string $folder, array $file): string
{
    $target_dir = __DIR__ . "/uploads/$folder/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $ext = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));
    $new_name = uniqid() . ".$ext";
    $target_file = $target_dir . $new_name;

    if (is_writable($target_dir) === false) {
        throw new Exception("The directory $target_dir is not writable.");
    }
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "$folder/$new_name";
    } else {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE) {
            throw new Exception("The uploaded file exceeds the upload_max_filesize directive in php.ini.");
        } elseif ($file['error'] === UPLOAD_ERR_FORM_SIZE) {
            throw new Exception("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.");
        } elseif ($file['error'] === UPLOAD_ERR_PARTIAL) {
            throw new Exception("The uploaded file was only partially uploaded.");
        } elseif ($file['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("No file was uploaded.");
        } elseif ($file['error'] === UPLOAD_ERR_NO_TMP_DIR) {
            throw new Exception("Missing a temporary folder.");
        } elseif ($file['error'] === UPLOAD_ERR_CANT_WRITE) {
            throw new Exception("Failed to write file to disk.");
        } elseif ($file['error'] === UPLOAD_ERR_EXTENSION) {
            throw new Exception("A PHP extension stopped the file upload.");
        }
        throw new Exception("Error occured while moving the file.");
    }
}

function move_from_temp(string $file): bool
{
    $temp_file = __DIR__ . "/uploads/tmp/$file";
    $target_file = __DIR__ . "/uploads/$file";
    if (!file_exists(dirname($target_file))) {
        mkdir(dirname($target_file), 0777, true);
    }
    return rename($temp_file, $target_file);
}

function delete_file(string $file_name): bool
{
    $file = __DIR__ . "/uploads/$file_name";
    if (file_exists($file)) {
        return unlink($file);
    }
    return false;
}

function get_file_url(string $folder, string $file): string
{
    return "/uploads/$folder/" . basename($file);
}
