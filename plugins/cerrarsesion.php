<?php
session_start();
session_destroy();

wp_logout();
wp_safe_redirect(home_url('/inicio'));
exit;
?>