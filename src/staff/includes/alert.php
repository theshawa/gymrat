<?php

$status = null;
$error = null;
$message = null;

if (isset($alertConfig)) {
    if (isset($alertConfig['status'])) {
        $status = $alertConfig['status'];
    }
    if (isset($alertConfig['error'])) {
        $error = $alertConfig['error'];
    }
    if (isset($alertConfig['message'])) {
        $message = $alertConfig['message'];
    }
}

$iconColor = ($status === 'failed') ? 'staff-icon-red' : 'staff-icon-green';

?>

<div>
    <?php if ($status === 'failed'): ?>
        <div id="staff-alert" class="staff-alert-container staff-alert-error">
            <div>
                <h3>Error!</h3>
                <?php if ($error): ?>
                    <p><?php echo $error; ?></p>
                <?php else: ?>
                    <p>Error has occurred (undefined)</p>
                <?php endif; ?>
            </div>
            <button type="button" onclick="hideAlert()" class="staff-btn-outline">
                <svg class="w-6 h-6 <?php echo $iconColor; ?> dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>
    <?php elseif ($status === 'success'): ?>
        <div id="staff-alert" class="staff-alert-container staff-alert-success">
            <div>
                <h3>Success!</h3>
                <?php if ($message): ?>
                    <p><?php echo $message; ?></p>
                <?php else: ?>
                    <p>Action was successful</p>
                <?php endif; ?>
            </div>
            <button type="button" onclick="hideAlert()" class="staff-btn-outline">
                <svg class="w-6 h-6 <?php echo $iconColor; ?> dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                </svg>
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
    function hideAlert() {
        const div = document.getElementById('staff-alert');
        if (div) {
            div.style.display = 'none';
        }
    }
</script>

<style>
    .staff-alert-container {
        margin: 0px 0px 20px 0px;
        padding: 10px 15px;
        border-radius: 10px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .staff-alert-error {
        border: 1px solid var(--color-red);
        background-color: var(--color-red-light-faded);
        color: var(--color-red);
    }

    .staff-alert-success {
        border: 1px solid var(--color-green);
        background-color: var(--color-green-light-faded);
        color: var(--color-green);
    }

    .staff-icon-red {
        color: var(--color-red);
    }

    .staff-icon-green {
        color: var(--color-green);
    }
</style>