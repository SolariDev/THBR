<?php
session_start();
unset($_SESSION['thbr_usuario']); 

session_write_close();

wp_safe_redirect(home_url('/inicio'));
exit;
?>