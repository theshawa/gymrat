<?php
// Place this file in src/trainer/profile/verify-upload.php
// This is a diagnostic script to check permissions and configuration

// Check PHP version and configurations
echo "<h2>PHP Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";

// Check directory permissions
echo "<h2>Directory Permissions</h2>";

$uploadDir = __DIR__ . '/../../uploads/trainer-avatars/';
echo "Upload Directory: $uploadDir<br>";

if (!file_exists($uploadDir)) {
    echo "Directory doesn't exist! Attempting to create...<br>";
    if (mkdir($uploadDir, 0777, true)) {
        echo "Successfully created directory<br>";
    } else {
        echo "FAILED to create directory - check permissions<br>";
    }
} else {
    echo "Directory exists<br>";
}

echo "Is writable: " . (is_writable($uploadDir) ? "Yes" : "No - PROBLEM") . "<br>";
echo "Permissions: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "<br>";

// Check for any existing files in the directory
echo "<h2>Existing Files</h2>";
$files = glob($uploadDir . "*");
if (count($files) > 0) {
    echo "Found " . count($files) . " files in directory:<br>";
    foreach ($files as $file) {
        echo basename($file) . " (" . filesize($file) . " bytes) - Created: " . date("Y-m-d H:i:s", filectime($file)) . "<br>";
    }
} else {
    echo "No files found in directory<br>";
}

// Test file upload capabilities
echo "<h2>Upload Test Form</h2>";
?>

<form action="test-upload.php" method="post" enctype="multipart/form-data">
    <p>Select image to upload:</p>
    <input type="file" name="test_upload" id="test_upload">
    <input type="submit" value="Test Upload" name="submit">
</form>

<?php
// Create a test upload script
$testUploadContent = <<<'EOT'
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
EOT;

$testUploadFile = __DIR__ . '/test-upload.php';
file_put_contents($testUploadFile, $testUploadContent);
echo "<p>Created test upload script at: test-upload.php</p>";

// Database check
echo "<h2>Database Configuration Check</h2>";

require_once "../../db/models/Trainer.php";

session_start();
if (isset($_SESSION['auth']) && isset($_SESSION['auth']['id'])) {
    $trainer = new Trainer();
    $trainer->id = $_SESSION['auth']['id'];

    try {
        $trainer->get_by_id();
        echo "Successfully retrieved trainer with ID: " . $trainer->id . "<br>";
        echo "Current avatar value in database: " . var_export($trainer->avatar, true) . "<br>";

        // Check if the avatar file exists
        if (!empty($trainer->avatar)) {
            $avatarPath = $uploadDir . $trainer->avatar;
            if (file_exists($avatarPath)) {
                echo "Avatar file exists at: $avatarPath<br>";
                echo "File size: " . filesize($avatarPath) . " bytes<br>";
            } else {
                echo "Avatar file does NOT exist at: $avatarPath<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error retrieving trainer: " . $e->getMessage();
    }
} else {
    echo "No active trainer session found";
}