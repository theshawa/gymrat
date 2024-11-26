<?php

require_once "../auth-guards.php";

auth_required_guard("/rat");

session_destroy();

header("Location: ./login");
