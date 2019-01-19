<?php
require('./library_db.php');
$datauser = [];
$datain = [];
foreach ($_POST as $key => $value) {
  if (is_bool(json_decode($value)) === true or is_int(json_decode($value)) == true){
    $datauser[$key] = json_decode($value);
  } else {
    $datauser[$key] = $value;
  }
}

$datain['namesusr'] = $datauser['userfnames'];
$datain['dbirthusr'] = $datauser['userdbirth'];
$datain['emailuser'] = $datauser['useremaila'];
$datain['pworduser'] = password_hash($datauser['userpasswd'], PASSWORD_DEFAULT);

$con = new DBConnector('localhost', 'admin', 'nextudbadmin', 'schedule_db');
if ($con->initConnection()) {
  $response['result'] = 'ok';
  $emailCheck = $con->simpleBringData(['users'], ['emailuser'], 'emailuser = "'.$datauser['useremaila'].'"');
  if ($emailCheck->num_rows == 0) {
    if ($con->insertData('users', $datain)) {
      $response['msg'] = "Datos de usuario registrados con éxito!!";
    } else {
      $response['msg'] = "Hubo un error en el ingreso de datos en la BD!!";
    }
  } else {
    $response['msg'] = "La dirección de correo: ".$datauser['useremaila'].",\nya se encuentra registrada!! \nDebe registrar una dirección de correo distinta!!";
  }
}

echo json_encode($response);

$con->closeConnection();

?>
