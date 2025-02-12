<?php

require_once "../auth-guards.php";

auth_required_guard_with_role("trainer", "./");

session_destroy();

header("Location: ./login");
