<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/CustomerEmailVerificationRequest.php";

class CustomerPasswordResetRequest extends CustomerEmailVerificationRequest
{
    protected $table = "customer_password_reset_requests";
}
