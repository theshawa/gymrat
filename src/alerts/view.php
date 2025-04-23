<?php

$error_alert = $_SESSION['error'] ?? null;
$success_alert = $_SESSION['success'] ?? null;
$info_alert = $_SESSION['info'] ?? null;

unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['info']);

?>

<?php if ($error_alert || $success_alert || $info_alert): ?>
    <div class="alert-wrapper">
        <?php if ($error_alert): ?>
            <div class="alert error" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <p><?= $error_alert ?></p>
            </div>
        <?php endif; ?>
        <?php if ($success_alert): ?>
            <div class="alert success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p><?= $success_alert ?></p>
            </div>
        <?php endif; ?>
        <?php if ($info_alert): ?>
            <div class="alert info" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <p><?= $info_alert ?></p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.display = 'none';
            });
            document.querySelector('.alert-wrapper').style.display = 'none';
        }, 5000);
        document.querySelectorAll('.alert').forEach(alert => {
            alert.addEventListener('click', e => {
                alert.style.display = 'none';
                document.querySelector('.alert-wrapper').style.display = 'none';
            });
        });
    </script>
<?php endif; ?>