<?php
/**
 * Script de creación de BD, tablas e inserción de datos de prueba --//
 */
require('./library_db.php');

function insertarUsersData($tbl, $conex) {
$datain[0]['namesusr'] = 'Liam Neeson';
$datain[0]['dbirthusr'] = '1952-06-07';
$datain[0]['emailuser'] = 'liamneeson0706@nextu.com';
$datain[0]['pworduser'] = password_hash('lneeson0706', PASSWORD_DEFAULT);
$datain[1]['namesusr'] = 'Jeff Bridges';
$datain[1]['dbirthusr'] = '1949-12-04';
$datain[1]['emailuser'] = 'jeffbridges0412@nextu.com';
$datain[1]['pworduser'] = password_hash('jbridges0412', PASSWORD_DEFAULT);
$datain[2]['namesusr'] = 'Harrison Ford';
$datain[2]['dbirthusr'] = '1942-07-13';
$datain[2]['emailuser'] = 'harrisonford1307@nextu.com';
$datain[2]['pworduser'] = password_hash('hford1307', PASSWORD_DEFAULT);
  for($i = 0; $i < count($datain); $i++) {
    foreach ($datain[$i] as $key => $value) {
      $conex->insertData($tbl, $datain[$i]);
      break;
    }
  }
  echo('<h3><b>Se insertaron 3 registros de usuario para pruebas BackEnd.</b></h3><br>');
}

function definirCampos($tb) {
  switch ($tb) {
    case 0:
      $tbfields['identusr'] = 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY';
      $tbfields['namesusr'] = 'VARCHAR(40) COLLATE latin1_spanish_ci NOT NULL';
      $tbfields['dbirthusr'] = 'DATE NOT NULL';
      $tbfields['emailuser'] = 'VARCHAR(50) COLLATE latin1_spanish_ci NOT NULL';
      $tbfields['pworduser'] = 'VARCHAR(256) COLLATE latin1_spanish_ci NOT NULL';
      break;
    case 1:
      $tbfields['identevt'] = 'SMALLINT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY';
      $tbfields['evttitles'] = 'TINYTEXT COLLATE latin1_spanish_ci NOT NULL';
      $tbfields['evtbgindt'] = 'DATE NOT NULL';
      $tbfields['evtbgintm'] = 'TIME DEFAULT NULL';
      $tbfields['evtenddat'] = 'DATE DEFAULT NULL';
      $tbfields['evtendtim'] = 'TIME DEFAULT NULL';
      $tbfields['evtfulday'] = 'TINYINT(1) NOT NULL';
      $tbfields['fk_iduser'] = 'INT(11) NOT NULL';
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
        if ($tbschedule[$key] == 'users') {
          insertarUsersData($tbschedule[$key], $condb);
        }
      } else {
        echo('<b>Ya existen registro en la tabla '.$tbschedule[$key].' en la BD schedule_db para pruebas.</b><br>');
        break;
      }
    }
  } else {
    echo('No conecto!!<br>');
  }
  $condb->closeConnection();
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

crearBaseDatos();

?>