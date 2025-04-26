<?php
// Test upload script
$uploadDir = __DIR__ . '/../../uploads/trainer-avatars/';

// Create directory if needed
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if(isset($_FILES["test_upload"]) && $_FILES["test_upload"]["error"] == 0) {
    echo "<h1>Upload Diagnostics</h1>";
    echo "File received: " . $_FILES["test_upload"]["name"] . "<br>";
    echo "Size: " . $_FILES["test_upload"]["size"] . " bytes<br>";
    echo "Type: " . $_FILES["test_upload"]["type"] . "<br>";
    
    $newFileName = 'test_' . time() . '_' . $_FILES["test_upload"]["name"];
    $destination = $uploadDir . $newFileName;
    
    echo "Attempting to save to: $destination<br>";
    
    if(move_uploaded_file($_FILES["test_upload"]["tmp_name"], $destination)) {
        echo "SUCCESS: File was saved successfully!";
        echo "<p>File is accessible at: <a href='/uploads/trainer-avatars/$newFileName' target='_blank'>/uploads/trainer-avatars/$newFileName</a></p>";
    } else {
        echo "ERROR: File upload failed!<br>";
        echo "Error code: " . $_FILES["test_upload"]["error"] . "<br>";
        
        switch($_FILES["test_upload"]["error"]) {
            case UPLOAD_ERR_INI_SIZE:
                echo "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "A PHP extension stopped the file upload";
                break;
            default:
                echo "Unknown upload error";
                break;
        }
    }
} else {
    echo "No file uploaded or error occurred: " . $_FILES["test_upload"]["error"];
}