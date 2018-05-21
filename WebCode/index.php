<?php

require_once 'includes/all_fns.php';

if (!isset($_SESSION['user_id'])) {
    redirect("/login.php");
}

redirect("/web/system/index.php");