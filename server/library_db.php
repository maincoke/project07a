<?php

class DBConnector {

  private $dbhost;
  private $dbuser;
  private $dbpass;
  private $dbname;
  private $dbconex;

  // Constructor de conector para la gestión de la BD
  function __construct($host_db, $user_db, $pass_db, $name_db){
    $this->dbhost = $host_db;
    $this->dbuser = $user_db;
    $this->dbpass = $pass_db;
    $this->dbname = $name_db;
  }

  // Inicialización y verificación de la Conexión con la BD
  function initConnection(){
    $this->dbconex = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname); // Para motor MySQL ó MariaDB
    if ($this->dbconex->connect_error){
      echo "Error: ".$this->dbconex->connect_error;
      return false;
    } else {
      return true;
    }
  }
  // Establecimiento de Conjunto de caracteres
  function setCharSet($charset){
    if ($this->dbconex->set_charset($charset)) {
      return true;
    } else {
      echo 'Error: '.$this->dbconex->error;
      return false;
    }
  }
  // Finalizacion de la Conexion con la BD
  function closeConnection(){
    $this->dbconex->close(); // Para motor MySQL ó MariaDB
    //$this->dbconex = null; // Para motor general de BD
  }
  
  // Ejecucion del Query para la gestión de operaciones SQL en la BD
  function runQuery($query){
    return $this->dbconex->query($query);
  }

  // Envio de la conexion de la BD para utilizar los metodos de BD
  function getConnection(){
    return $this->dbconex;
  }

  // Creacion de la tabla en la BD con los campos solicitados en los parametro
  function setNewTableQuery($name_tbl, $fields){
    $qsql = 'CREATE TABLE '.$name_tbl.' (';
    $length_array = count($fields);
    $i = 1;
    foreach ($fields as $key => $value){
      $qsql .= $key.' '.$value;
      if ($i != $length_array){
        $qsql .= ', ';
      } else {
        $qsql .= ');';
      }
      $i++;
    }
    return $this->runQuery($qsql);
  }

  // Inclusion de una restricción a una tabla de la BD
  function addRestriction($name_tbl, $restriction){
    $qsql = 'ALTER TABLE '.$name_tbl.' '.$restriction;
    return $this->runQuery($qsql);
  }

  // Creacion de una relacion entre dos tablas de la BD
  function newRelation($from_tbl, $to_tbl, $from_fld, $to_fld) {
    $qsql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_fld.') REFERENCES '.$to_tbl.'('.$to_fld.');';
    return $this->runQuery($qsql);
  }

  // Insercion de datos en tablas de la BD
  function insertData($name_tbl, $data){
    $qsql = 'INSERT INTO '.$name_tbl.' (';
    $i = 1;
    foreach ($data as $key => $value) {
      $qsql .= $key;
      if ($i < count($data)){
        $qsql .= ', ';
      } else {
        $qsql .= ')';
      }
      $i++;
    }
    $qsql .= ' VALUES (';
    $i = 1;
    foreach ($data as $key => $value){
      $qsql .= '"'.$value.'"';
      if ($i < count($data)) {
        $qsql .= ', ';
      } else {
        $qsql .= ');';
      }
      $i++;
    }
    return $this->runQuery($qsql);
  }

  // Actualizacion de registros y datos en una tabla de la BD
  function updateData($name_tbl, $data, $condition){
    $qsql = 'UPDATE '.$name_tbl.' SET ';
    $i = 1;
    foreach ($data as $key => $value){
      $qsql .= $key.' = '.$value;
      if ($i < count($data)){
        $qsql .= ', ';
      } else {
        $qsql .= ' WHERE '.$condition.';';
      }
      $i++;
    }
    return $this->runQuery($qsql);
  }

  // Eliminación y borrado de datos y registros de una tabla en la BD
  function deleteData($name_tbl, $condition){
    $qsql = 'DELETE FROM '.$name_tbl.' WHERE '.$condition.';';
    return $this->runQuery($qsql);
  }

  // Ejecucion de una consulta simple a una o más tablas de la BD
  function simpleBringData($name_tbls, $fields, $condition = ""){
    $qsql = 'SELECT ';
    $arrkeys = array_keys($fields);
    $last_key = end($arrkeys);
    foreach ($fields as $key => $value){
      $qsql .= $value;
      if ($key != $last_key){
        $qsql .= ', ';
      } else {
        $qsql .= ' FROM ';
      }
    }
    $arrkeys = array_keys($name_tbls);
    $last_key = end($arrkeys);
    foreach ($name_tbls as $key => $value) {
      $qsql .= $value;
      if ($key != $last_key){
        $qsql .= ', ';
      } else {
        $qsql .= ' ';
      }
    }
    if ($condition == ''){
      $qsql .= ';';
    } else {
      $qsql .= ' WHERE '.$condition.';';
    }
    return $this->runQuery($qsql);
  }

  // Ejecucion de una consulta a dos o más tablas relacionadas de la BD
  function complexBringData($name_tbls, $fields, $rel_fields, $condition = ""){
    $qsql = 'SELECT ';
    $arrkeys = array_keys($fields);
    $last_key = end($arrkeys);
    foreach ($fields as $key => $value){
      $qsql .= $value;
      if ($key != $last_key){
        $qsql .= ', ';
      } else {
        $qsql .= ' FROM ';
      }
    }
    $arrkeys = array_keys($name_tbls);
    $i = 0;
    $first_key = reset($arrkeys);
    foreach ($name_tbls as $key => $value) {
      if ($key != $first_key){
        $qsql .= ' JOIN '.$value.' ON ';
        if ($i < count($rel_fields)){
          $qsql .= $rel_fields[$i].' = '.$rel_fields[$i+1];
          $i+=2;
        }
      } else {
        $qsql .= $value;
      }
    }
    if ($condition == ''){
      $qsql .= ';';
    } else {
      $qsql .= ' WHERE '.$condition.';';
    }
    return $this->runQuery($qsql);
  }

}

?>