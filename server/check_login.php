<?php
  // Modulo de Login y Verificación de Credenciales para el acceso --//
  require('./library_db.php');

  $con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
  
  if ($con->initConnection()) {
    $checkCreds = $con->simpleBringData(['users'], ['emailuser', 'pworduser'], 'emailuser = "'.$_POST['username'].'"');
    if ($checkCreds->num_rows != 0) {
      $response['access'] = false;
      $queryCred = $checkCreds->fetch_assoc();
      if (password_verify($_POST['password'], $queryCred['pworduser'])) {
        $response['access'] = true;
        $response['reason'] ='';
        session_start();
        $_SESSION['username'] = $queryCred['emailuser'];
      } else {
        $response['reason'] = 'Las credenciales ingresadas son inválidas!!';
      }
    } else {
      $response['reason'] = 'Estas credenciales no estan registradas!!';
    }
  }

  echo json_encode($response);

  $con->closeConnection();

 ?>
