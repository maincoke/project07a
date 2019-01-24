<?php
// Modulo para la actualización y cambio en BD de los eventos registrados --//
require('./library_db.php');
session_start();

if (isset($_SESSION['username'])) {
  $dataevent = [];
  $dataup = [];
  foreach ($_POST as $key => $value) {
    if (is_bool(json_decode($value)) === true or is_int(json_decode($value)) == true){
      $dataevent[$key] = json_decode($value);
    } else {
      $dataevent[$key] = $value;
    }
  }
  $response['result'] = '';
  if (!empty($dataevent['start_date']) and !empty($dataevent['id'])) {
    $dataup['evtbgindt'] = "'".$dataevent['start_date']."'";
    $dataup['evtfulday'] = !$dataevent['fullday'] ? 0 : 1;
    if (!$dataevent['fullday']) {
      $dataup['evtenddat'] = "'".$dataevent['end_date']."'";
      $dataup['evtbgintm'] = "'".$dataevent['start_hour']."'";
      $dataup['evtendtim'] = "'".$dataevent['end_hour']."'";
    }
    $con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
    if ($con->initConnection() and $con->setCharSet('utf8')) {
      $idUser = $con->simpleBringData(['users'], ['identusr', 'emailuser'], 'emailuser = "'.$_SESSION['username'].'"');
      $userRow = $idUser->fetch_assoc();
      $dataup['fk_iduser'] = (int)$userRow['identusr'];
      if ($con->updateData('events', $dataup, "identevt = ".(int)$dataevent['id'])) {
        $response['result'] = 'ok';
        $response['msg'] = 'El evento fue cambiado con éxito!!';
      } else {
        $response['msg'] = 'Hubo un error en el cambio del evento para la BD!!';
      }
    } else {
      $response['msg'] = "Hubo un error en la conexión con la BD!!";
    }
    $con->closeConnection();
  } else {
    $response['msg'] = 'El evento no existe en la BD!!';
  }
} else {
  $response['msg'] = "El usuario no ha iniciado sesión o la sesión expiró!!";
}

echo json_encode($response);

?>
