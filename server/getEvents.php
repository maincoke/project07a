<?php
require('./library_db.php');
session_start();

if (isset($_SESSION['username'])) {
  $con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
  if ($con->initConnection() and $con->setCharSet('utf8')) {
    $idUser = $con->simpleBringData(['users'], ['identusr', 'emailuser'], 'emailuser = "'.$_SESSION['username'].'"');
    $userRow = $idUser->fetch_assoc();
    $fetchEventsUser = $con->simpleBringData(['events AS e'], ['e.evttitles AS title', 'e.evtbgindt AS start_date', PHP_EOL.
                                              'e.evtbgintm AS start_time', 'e.evtenddat AS end_date', 'e.evtendtim AS end_time', PHP_EOL.
                                              'e.evtfulday AS full_day', 'e.fk_iduser AS id_user'], 'e.fk_iduser = '.$userRow['identusr']);
    $i = 0;
    while ($eventsUser = $fetchEventsUser->fetch_assoc()) {
      $resp['eventos'][$i]['title'] = $eventsUser['title'];
      $startDate = !is_null($eventsUser['start_time']) ? $eventsUser['start_date'].'T'.$eventsUser['start_time'] : $eventsUser['start_date'];
      $resp['eventos'][$i]['start'] = $startDate;
      if (!is_null($eventsUser['end_date'])) {
        $endDate = !is_null($eventsUser['end_time']) ? $eventsUser['end_date'].'T'.$eventsUser['end_time'] : $eventsUser['end_date'];
      } else {
        $endDate = '';
      }
      $resp['eventos'][$i]['end'] = $endDate;
      $resp['eventos'][$i]['allDay'] = $eventsUser['full_day'] != 0 ? true : false;
      $i++;
    }
    $resp['msg'] = 'ok';
  } else {
    $resp['msg'] = "Hubo un error en la conexión con la BD!!";
  }
} else {
  $resp['msg'] = "El usuario no ha iniciado sesión o la sesión expiró!!";
}

echo json_encode($resp);
/*echo $userRow['emailuser'].'<br>';
echo $rows.' ---- '.$row2;
echo $resp['eventos'][0]['title'].'<br>';
echo $resp['eventos'][1]['title'].'<br>';
echo $resp['eventos'][0]['start_date'].'<br>';
echo $resp['eventos'][1]['start_date'].'<br>';*/

$con->closeConnection();
?>
