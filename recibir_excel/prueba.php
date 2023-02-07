<table border="1" style="font-size: 13px;">
  <tr>
    <td>Numero  </td>
    <td>Orden   </td>
    <td>Fecha   </td>
    <td>Cantidad</td>
  </tr>

  <?php
      if(isset($_GET['bien'])){
        $ingreso_ordenes = $_GET['bien'];$total = $_GET['mal'];
        echo "<script> alert('Se terminaron de importar los datos ahora sera redirigido a descargar las observaciones de la importacion si hubieron');  
        window.location.href ='exportar_excel.php?ingreso=$ingreso_ordenes&repetido=$total';</script>";
        echo '<a href="importar_ordenes.php">VOLVER AL INICIO</a>';
      }

    	$DB_HOST = $_ENV['DB_HOST'];
	$DB_USER = $_ENV['DB_USER'];
	$DB_PASSWORD = $_ENV['DB_PASSWORD'];
	$DB_NAME = $_ENV['DB_NAME'];
	$DB_PORT = $_ENV['DB_PORT'];

  	$conexion=mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME,$DB_PORT);
    $nombre = $_FILES['dataCliente']['name'];
    // echo "<script> var a = '$nombre'; alert('aaa'+a); </script>";
    date_default_timezone_set("America/Bogota");
    setlocale(LC_ALL,"es_ES");
    $fecha_imagen   = date("hms");
    $recorrido = 0;
    if(isset($_GET['extraer'])){
      $recorrido = $_GET['extraer'];
    }

    $carpeta = "../recibir_excel/";
    opendir($carpeta);
    $nombre = $fecha_imagen.'-'.$_FILES['dataCliente']['name'];
    $destino = $carpeta.$nombre;

    require 'convertir_excel/vendor/autoload.php';
    
    $ingreso_ordenes = 0;$total = 0;$actualizados = 0;$sin_subir = 0;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Shared\Date;

    $nombreArchivo  = 'BD OP.xlsx';
    $documento      = IOFactory::load($nombreArchivo);
    $hojaActual     = $documento  -> getSheet(0);
    $numeroFilas    = $hojaActual -> getHighestDataRow();
    $letra          = $hojaActual -> getHighestColumn();

    while($recorrido <= 1){
      for($indiceFila = 2; $indiceFila <= $numeroFilas;$indiceFila++){
        // echo '<tr>';

          $valorA = $hojaActual -> getCellByColumnAndRow(1,$indiceFila);
          $valorB = $hojaActual -> getCellByColumnAndRow(2,$indiceFila);
          $valorB =  str_replace('"', '', $valorB);
          
          $valorC = $hojaActual->getCell('C'.$indiceFila)->getValue();
          $fecha  = Date::excelToDateTimeObject($valorC);
          $valorC = $fecha->format('Y-m-d');
          if($valorC == "1970-01-01"){ $valorC = "00-00-00 00:00:00";}

          $valorD = $hojaActual -> getCellByColumnAndRow(4,$indiceFila);

          if ($valorD == "") { $valorD = 0; }
          /*BUSCAR DUPLICADOS */

          $checkemail_duplicidad = "SELECT * FROM Ordenes WHERE Numero_Orden = $valorA";
          $ca_dupli         = mysqli_query($conexion, $checkemail_duplicidad);
          $cant_duplicidad  = mysqli_num_rows($ca_dupli);

          // if ( $cant_duplicidad == 0 ) { 
          //   while ($lista = mysqli_fetch_array($ca_dupli)){
          //     $cantidad_lista = $lista['Cantidad_planeada'];
          //   }
          //   if(($cantidad_lista != "") and ($cantidad_lista != $valorD)){
          //     $updateOrden    =  ("UPDATE ordenes SET Cantidad_planeada = '$valorD' WHERE numero_orden = '$valorA'");
          //   }
          // }
          /* EN CASO DE QUE HAYA DUPLICADO */

            if ( $cant_duplicidad == 0 ) { 
              $insertarData   = "INSERT INTO ordenes(Numero_orden,Orden,Fecha_ingreso,Cantidad_planeada) VALUES('$valorA','$valorB','$valorC','$valorD')";
              $subir_ordenes  = mysqli_query($conexion, $insertarData);
              echo '<td>'.$valorA.'</td><td>'.$valorB.'</td><td>'.$valorC.'</td><td>'.$valorD.'</td>';
              $ingreso_ordenes++;
              $total++;
              echo '<tr>';
            } 
            /**Caso Contrario actualizo el o los Registros ya existentes*/
            else{
              $checkorden_duplicidad  = "SELECT * FROM Ordenes_faltantes WHERE Numero_Orden = $valorA";
              $catch_dupli            = mysqli_query($conexion, $checkorden_duplicidad);
              $cantidad_duplicidad    = mysqli_num_rows($catch_dupli);
              if ($cantidad_duplicidad == 0) { 
                $updateData     = ("INSERT INTO ordenes_faltantes (Numero_orden,Orden,Fecha_ingreso,Cantidad_planeada) VALUES('$valorA','$valorB','$valorC','$valorD')");
                $result_update  = mysqli_query($conexion, $updateData);
                // if($result_update){ echo "Se subio en la faltante tabla<br>";}else{echo "no se subio a la otras tabla<br>";}
                $actualizados++;
                $total++;
              } 
              else{
                // while ($fila = mysqli_fetch_array($catch_dupli)){
                //   $cantidad_lista = $fila['Cantidad_planeada'];
                // }
                // if(($cantidad_lista != "") and ($cantidad_lista > $valorD)){
                //   $valorD = $cantidad_lista;
                // }
                $updateData     =  ("UPDATE ordenes_faltantes SET Fecha_Ingreso = '$valorC',Orden = '$valorB',Cantidad_planeada = '$valorD' WHERE Orden = '$valorA'");
                $result_update  = mysqli_query($conexion, $updateData);
                // if($result_update){ echo "Se actualizo en otra tabla<br>";}else{echo "no se actualizo<br>";}
                $total++;
                $actualizados++;
              }
              $sin_subir++;
            }

          /*INSERTAR LOS REGISTROS */
          // $insertarData = "INSERT INTO ordenes(Numero_orden,Orden,Fecha_ingreso,Cantidad_planeada) VALUES('$valorA','$valorB','$valorC','$valorD')";
          // $subir_ordenes = mysqli_query($conexion, $insertarData);
          // if($subir_ordenes){echo " se subio ";}else{echo " no se subio ";}
          // echo '<td>'.$valorA.'</td><td>'.$valorB.'</td><td>'.$valorC.'</td><td>'.$valorD.'</td>';
          // echo $indiceFila.' | ';
        
      }
      echo "</table>carga completa";
      $recorrido++;
    }

    echo "<br>Se importaron $ingreso_ordenes ordenes. Se contaron $actualizados registros repetidos que se subieron y actualizaron en otra tabla y un total de $total registros contados";
    echo "<br><a href='prueba.php?bien=$ingreso_ordenes&mal=$total'><input name='enviar' type='submit' value='VER REPORTE'></a>";
  ?>

<a href="importar_ordenes.php">VOLVER AL INICIO</a>