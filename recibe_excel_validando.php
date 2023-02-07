<?php
include 'conexion/conexion.php';

$tipo       = $_FILES['dataCliente']['type'];
$tamanio    = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas     = file($archivotmp);

$i = 0;
$menor = -9999;
$ingreso_ordenes = 0;
$sin_subir = 0;
$actualizados = 0;
foreach ($lineas as $linea) {
	$cantidad_registros = count($lineas);
	$cantidad_regist_agregados =  ($cantidad_registros - 1);

	if ($i != 0) {

		$datos = explode(";", $linea);
		$numero              = !empty($datos[0])  ? ($datos[0]) : '';
		$orden                = !empty($datos[1])  ? ($datos[1]) : '';
		$fecha                = !empty($datos[2])  ? ($datos[2]) : '';
		$cantidad_planeada    = !empty($datos[4])  ? ($datos[4]) : '';
		$minuscula = mb_strtolower($orden,"UTF-8");
		$orden = ucfirst($minuscula);
		$orden =  str_replace('"', '', $orden);
		$originalDate = $fecha;
		$fecha = date("Y-m-d H:i:s", strtotime($originalDate));
	   if(($fecha == "") OR ($fecha < "2020-01-01")) {
      $fecha = "2022-00-00 00:00:00"; 
      // echo "no tiene fecha";
	   }
	   if ($cantidad_planeada == "") {
		$cantidad_planeada = 0;
	   }
	   if ($orden == "") {
			$orden = "/*No tiene nombre/*";
	   }
if(!empty($numero)){
	$checkemail_duplicidad = "SELECT * FROM Ordenes WHERE Numero_Orden = $numero";
			$ca_dupli = mysqli_query($conexion, $checkemail_duplicidad);
			$cant_duplicidad = mysqli_num_rows($ca_dupli);
			while ($lista = mysqli_fetch_array($ca_dupli)){
				$cantidad_lista = $lista['Cantidad_planeada'];
			}
			if(($cantidad_lista != "") and ($cantidad_lista > $cantidad_planeada)){
				$cantidad_planeada = $cantidad_lista;
				$updateOrden =  ("UPDATE ordenes SET Cantidad_planeada = '$cantidad_planeada' WHERE numero_orden = '$numero'");
			}
			// echo 'NUMERO - '.$numero.' |-|   ORDEN - '.$orden.' |-|   FECHA -'.$fecha.' |-|   CANTIDAD - '.$cantidad_planeada.'<br><hr><br>';
		}
	//No existe Registros Duplicados
	if ( $cant_duplicidad == 0 ) { 
	$insertarData = "INSERT INTO ordenes(Numero_orden,Orden,Fecha_ingreso,Cantidad_planeada) VALUES('$numero','$orden','$fecha','$cantidad_planeada')";
	$subir_ordenes = mysqli_query($conexion, $insertarData);
	// if($subir_ordenes){ echo "Se subio en la tabla normal<br>";}
  $ingreso_ordenes++;
	} 
	/**Caso Contrario actualizo el o los Registros ya existentes*/
	else{
	// $updateData =  ("UPDATE ordenes SET Fecha_Ingreso = '$fecha',Orden = '$orden' WHERE Orden = '$orden'");
	// $result_update = mysqli_query($conexion, $updateData);
	// if($result_update){ echo "Se actualizo en la tabla normal<br>";}else{echo "no se actualizo en la normal<br>";}

	$checkorden_duplicidad = "SELECT Numero_Orden FROM Ordenes_faltantes WHERE Numero_Orden = $numero";
	$catch_dupli = mysqli_query($conexion, $checkorden_duplicidad);
	$cantidad_duplicidad = mysqli_num_rows($catch_dupli);
	if ( $cantidad_duplicidad == 0 ) { 
		$updateData =  ("INSERT INTO ordenes_faltantes (Numero_orden,orden,Fecha_ingreso,Cantidad_planeada) VALUES('$numero','$orden','$fecha','$cantidad_planeada')");
		$result_update = mysqli_query($conexion, $updateData);
		// if($result_update){ echo "Se subio en la faltante tabla<br>";}else{echo "no se subio a la otras tabla<br>";}
    $sin_subir++;
		} 
	else{
		$updateData =  ("UPDATE ordenes_faltantes SET Fecha_Ingreso = '$fecha',Orden = '$orden',Cantidad_planeada = '$cantidad_planeada' WHERE Orden = '$orden'");
		$result_update = mysqli_query($conexion, $updateData);
		// if($result_update){ echo "Se actualizo en otra tabla<br>";}else{echo "no se actualizo<br>";}
    $sin_subir++;
    $actualizados++;
		} 
			$checkorden_duplicidad = "SELECT Numero_Orden FROM Ordenes_faltantes WHERE Numero_Orden = $numero";
			$catch_dupli = mysqli_query($conexion, $checkorden_duplicidad);
			$cantidad_duplicidad = mysqli_num_rows($catch_dupli);
	}
  }
echo $i.'  ';
$i++;
}
	echo "Se importaron $ingreso_ordenes ordenes y Hubieron $actualizados ordenes repetidas que se subieron y actualizaron en otra tabla y un total de $sin_subir registros contados";
	echo "<form action='' method='post'><input name='enviar'  type='submit' value='Seguir'></form>";
	if(isset($_POST['enviar'])){
		echo "<script> alert('Se terminaron de importar los datos ahora sera redirigido a descargar las observaciones de la importacion si hubieron');  window.location.href ='exportar_excel.php?bien=$ingreso_ordenes&mal=$sin_subir';</script>";
	}
// echo "<script> alert('Se guardaron todos los datos');</script>";
?>
<!-- window.location.href ='principal.php'; -->
