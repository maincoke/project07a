<?php
// Modulo para el borrado en BD de los eventos registrados --//
require('./library_db.php');
session_start();

if (isset($_SESSION['username'])) {
  $dataevent = [];
  foreach ($_POST as $key => $value) {
    if (is_bool(json_decode($value)) === true or is_int(json_decode($value)) == true){
      $dataevent[$key] = json_decode($value);
    } else {
      $dataevent[$key] = $value;
    }
  }
  $con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
  if ($con->initConnection()) {
    $idUser = $con->simpleBringData(['users'], ['identusr', 'emailuser'], 'emailuser = "'.$_SESSION['username'].'"');
    $userRow = $idUser->fetch_assoc();
    if ($con->deleteData('events', "identevt = {$dataevent['id']} AND fk_iduser = {$userRow['identusr']}")) {
      $response['result'] = 'ok';
      $response['msg'] = 'El evento fue borrado con éxito!!';
    } else {
      $response['result'] = '';
      $response['msg'] = 'Hubo un error en el borrado del evento en la BD!!';
    }
  } else {
    $response['msg'] = "Hubo un error en la conexión con la BD!!";
  }
} else {
  $response['msg'] = "El usuario no ha iniciado sesión o la sesión expiró!!";
}

echo json_encode($response);

$con->closeConnection();

?>
