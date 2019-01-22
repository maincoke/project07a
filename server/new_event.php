<?php
require('./library_db.php');
session_start();

if (isset($_SESSION['username'])) {
  $dataevent = [];
  $datain = [];
  foreach ($_POST as $key => $value) {
    if (is_bool(json_decode($value)) === true or is_int(json_decode($value)) == true){
      $dataevent[$key] = json_decode($value);
    } else {
      $dataevent[$key] = $value;
    }
  }
  $response['result'] = '';
  if (!empty($dataevent['titulo']) and !empty($dataevent['start_date'])) {
    $datain['evttitles'] = $dataevent['titulo'];
    $datain['evtbgindt'] = $dataevent['start_date'];
    $datain['evtfulday'] = $dataevent['allDay'];
    if (!$dataevent['allDay']) {
      $datain['evtbgintm'] = $dataevent['start_hour'];
      $datain['evtenddat'] = $dataevent['end_date'];
      $datain['evtendtim'] = $dataevent['end_hour'];
    }
    $con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
    if ($con->initConnection() and $con->setCharSet('utf8')) {
      $idUser = $con->simpleBringData(['users'], ['identusr', 'emailuser'], 'emailuser = "'.$_SESSION['username'].'"');
      $userRow = $idUser->fetch_assoc();
      $datain['fk_iduser'] = $userRow['identusr'];
      if ($con->insertData('events', $datain)) {
        $response['idevt'] = $con->lastRow();
        $response['result'] = 'ok';
        $response['msg'] = 'El evento fue registrado con éxito!!';
      } else {
        $response['msg'] = 'Hubo un error en el registro del evento en la BD!!';
      }
    } else {
      $response['msg'] = "Hubo un error en la conexión con la BD!!";
    }
    $con->closeConnection();
  } else {
    $response['msg'] = 'Datos inválidos del evento para el registro en la BD!!';
  }
} else {
  $response['msg'] = "El usuario no ha iniciado sesión o la sesión expiró!!";
}

echo json_encode($response);

?>
