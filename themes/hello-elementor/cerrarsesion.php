<?php
session_start();
unset($_SESSION['thbr_usuario']); //solo borra el usuario loguiado.

header("Location: http://localhost/wordpress/inicio");
exit;
?>
