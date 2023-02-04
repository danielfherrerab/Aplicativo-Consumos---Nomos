<?php
  include_once '../conexion/conexion.php';
  
  require 'convertir_excel/vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\IOFactory;

  $nombre = $_FILES['dataCliente']['name'];

  $destino = "/";

  copy($_FILES['dataCliente']['tmp_name'],$destino);
  // Leer el archivo de Excel
  $spreadsheet = IOFactory::load($_FILES['dataCliente']['name']);

  // Seleccionar la hoja de cálculo activa
  $worksheet = $spreadsheet->getActiveSheet();

  // Obtener los datos de la hoja de cálculo en una matriz
  $data = $worksheet->toArray();

  $i = 0;
  // Recorrer la matriz y hacer algo con los datos
  foreach ($data as $row) {
    if($i != 0){

      if($row[2] != ""){
        $row[2] = date("Y-m-d", strtotime($row[2]));
      }

      $duplicados = mysqli_query($conexion,"SELECT * FROM Ordenes WHERE numero_op = '$row[2]'");
      /* EN CASO DE QUE HAYA DUPLICADO */

        if(mysqli_num_rows($duplicados) > 0 ) { 
            $insertarRepetidas = mysqli_query($conexion,"INSERT INTO ordenes_duplicadas VALUES('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$row[5]')");
            $actualizados++;
        } 
        else{
          $insertarData   = mysqli_query($conexion,"INSERT INTO ordenes VALUES('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$row[5]')");
          // $ingreso_ordenes++;
        }
      // echo $row[0] . " <br>";
    }
    $i++;
  }
  if($i >= 1){
    echo true;
  }
?>
