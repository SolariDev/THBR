<?php
session_start();
unset($_SESSION['thbr_usuario']); //solo borra el usuario loguiado.

session_write_close();

header("Location: http://localhost/wordpress/inicio");
exit;
?>
