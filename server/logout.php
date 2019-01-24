<?php
// Modulo para el Logout y desconexión del usuario --//
session_start();

if (isset($_SESSION['username'])) {
  session_destroy();
  ob_start();
  header('Location: ../client/index.html');
  ob_end_flush();
  die();
}

?>
