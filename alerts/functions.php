<?php


function redirect_with_alert(string $message, string $redirect_url = "/", string $type = 'info'): void
{

    $_SESSION[$type] = $message;
    header("Location: $redirect_url");
    exit(1);
}

function redirect_with_error_alert(string $message, string $redirect_url = "/",): void
{
    redirect_with_alert($message, $redirect_url, 'error');
}

function redirect_with_success_alert(string $message, string $redirect_url = "/",): void
{
    redirect_with_alert($message, $redirect_url, 'success');
}

function redirect_with_info_alert(string $message, string $redirect_url = "/",): void
{
    redirect_with_alert($message, $redirect_url, 'info');
}
