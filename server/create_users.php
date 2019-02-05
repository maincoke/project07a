<?php
/**
 * Script de creación de BD, tablas e inserción de datos de prueba --//
 */
require('./library_db.php');

function insertarDataUser($tbl, $conex) {

  /*$datain['namesusr'] = $datauser['userfnames'];
$datain['dbirthusr'] = $datauser['userdbirth'];
$datain['emailuser'] = $datauser['useremaila'];
$datain['pworduser'] = password_hash($datauser['userpasswd'], PASSWORD_DEFAULT);*/

}

function crearBaseDatos() {
  $conex = new mysqli('localhost', 'admin', 'nextudbadmin');
  if (!$conex->connect_error) {
    $database = 'schedule_db';
    if (!$conex->select_db($database)) {
      echo('<b>Base de datos NO exite!! -- Error : <br></b>'.$conex->error.'<br>');
      $dbquery = 'CREATE DATABASE IF NOT EXISTS '.$database.' DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci';
      echo('<b>Creando Base de datos para la Agenda: <schedule_db>...</b><br><br>');
      if ($conex->query($dbquery)) {
        echo('<b>Base de datos: '.$database.' fue creada con éxito!!...</b><br><br>');
      } else {
        exit('<b>Hubo un error en la creación de la base de datos: '.$database.'</b><br>'.$conex->error);
      }
      $conex->close();
    }
    crearTablasyDatos($database);
  } else {
    exit('<b>No se puede conectar al servidor MySQL o el servidor esta inactivo!!</b><br>'.$conex->connect_error);
  }
}

function definirCampos($tb) {
  switch ($tb) {
    case 0:
      $tbfields['identusr'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
      $tbfields['namesusr'] = 'VARCHAR(40) NOT NULL';
      $tbfields['dbirthusr'] = 'DATE NOT NULL';
      $tbfields['emailuser'] = 'VARCHAR(50) NOT NULL';
      $tbfields['pworduser'] = 'VARCHAR(256) NOT NULL';
      break;
    case 1:
      $tbfields['identevt'] = 'SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY';
      $tbfields['evttitles'] = 'TINYTEXT NOT NULL';
      $tbfields['evtbgindt'] = 'DATE NOT NULL';
      $tbfields['evtbgintm'] = 'DATE NULL';
      $tbfields['evtenddat'] = 'DATE NULL';
      $tbfields['evtendtim'] = 'DATE NULL';
      $tbfields['evtfulday'] = 'TINYINT NOT NULL';
      $tbfields['fk_userid'] = 'INT NOT NULL';
    break;    
    default:
      break;
  }  
  return $tbfields;
}

function crearTablasyDatos($db) {
  $condb = new DBConnector('localhost', 'admin', 'nextudbadmin', $db);
  if ($condb->initConnection()) {
    $tbschedule[0] = 'users';
    $tbschedule[1] = 'events';
    foreach ($tbschedule as $key => $value) {
      $tbname = ' IF NOT EXISTS `'.$value.'`';
      $fields = definirCampos($key);
      $tboptions = 'DEFAULT CHARSET = latin1, DEFAULT COLLATE = latin1_spanish_ci';
      $createTb = $condb->setNewTableQuery($tbname, $fields, $tboptions);
      $checktb = $condb->runQuery('SELECT * FROM `'.$value.'`');
      if ($checktb->num_rows < 3) {
        echo('<b>Actualizando tablas y campos para el uso de la Agenda...!!<br>Creando tabla '.$value.' ...!!</b><br><br>');
      }
    }
    //insertarUsersData($tbschedule[0], $condb);
  } else {
    echo('No conecto!!<br>');
  }
  $condb->closeConnection();
}

crearBaseDatos();

?>