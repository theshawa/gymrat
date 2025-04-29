<?php

require_once "../auth-guards.php";
if (auth_required_guard("staff", "/staff/login")) exit;
