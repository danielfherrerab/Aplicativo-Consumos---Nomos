<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../recursos/css/main.css">
  <title>Exportar detallado - NOMOS</title>
</head>
<body onload="myFunction()">
	<div class="loading" id="loading"><div class="cargador"></div></div>

<?php
$conexion = mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');$conexion=mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');
  require 'convertir_excel/vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\SpreadSheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  $spreadsheet = new SpreadSheet();
  $spreadsheet -> getProperties() -> setcreator("Nomos") -> setTitle("Informe_detallado");
  date_default_timezone_set("America/Bogota");
  setlocale(LC_ALL,"es_ES");
  $fecha = date("Y_m_d");
  $nombre = $fecha."_Informe_detallado.xlsx";
  $dia 					= date('Y-m-01 H:i:s');
  $fecha 				= date("Y-m-d H:i:s");
  $date = new DateTime('now');
  $date->modify('last day of this month');
  $fecha_final =  $date->format('Y-m-d H:i:s');
  $verificador = 0;

	if(isset($_POST['origen'])){
      $desde	= $_POST['origen'];
      $hasta 	= $_POST['fin'];

      $preconsulta = "SELECT * from consumo_planchas";
      $busq_orden		= $_POST['busqueda_orden'];
      $variable 		= $_POST['busqueda'];
      if($_POST['busqueda_orden'] != ""){
        $preconsulta = "SELECT * from consumo_planchas WHERE (Fecha_Actual between '$desde' and '$hasta') and Numero_Orden LIKE '%".$busq_orden."%'";
        $verificador = 10;
      }
      if($_POST['busqueda'] != ""){
        // echo "<script>var a = '$fecha_final'; alert('aaa'+a); </script>";
        $preconsulta ="SELECT * from consumo_planchas WHERE (Fecha_Actual between '$desde' and '$hasta') and (Numero_articulo LIKE '%".$variable."%' or lugar_consumo LIKE '%".$variable."%' or descripcion LIKE '%".$variable."%')";
        $verificador = 10;
      }
      echo "<br>Se genero su informe en formato excel";
    }

  if(isset($_GET['ingreso'])){
    ?>
    <form action="" method="post">
    <h5>Puede buscar por OP</h5>
				<input type="text" placeholder="Ingrese el numero de la orden"  name="busqueda_orden" list="datalist-ordenes"><br><br>  
					Desde <input type="datetime-local" name="origen" value="<?php echo $dia; ?>" min="2021-01-01" max="2023-12-31"> 
					Hasta <input type="datetime-local" name="fin" value="<?php echo $fecha_final; ?>" min="2022-01-01" max="2024-12-31"><br><br>
				<h5>O puede buscar por articulo,ubicacion y descripcion</h5>
				<input type="text" placeholder="Ingrese su busqueda"  name="busqueda">
        <input type="submit" value="Buscar registros"><br><br>
    </form>
  <?php
  }
  if($verificador == 10){ 
    // $preconsulta = $_GET['consulta'];
    echo "<br>POR FAVOR ESPERE A QUE SE DESCARGUE EL ARCHIVO<br>";
    echo "<a href='../consumo.php'>VOLVER AL INICIO</a>";
    $spreadsheet -> setactivesheetindex(0);
    $hojaActiva = $spreadsheet -> getActiveSheet();
    $hojaActiva -> getColumnDimension('A') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('B') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('C') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('D') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('E') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('F') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('G') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('H') ->setAutoSize(true);
    $hojaActiva -> getStyle("F")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
    $hojaActiva -> mergeCells('A1:K1');

    $estilo = [
      'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
    ];
    $styleArray = [
      'borders' => [
          'allBorders' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => '00000000'],
          ],
      ],
  ];
    $hojaActiva->getStyle('A1')->applyFromArray($estilo);

    $hojaActiva -> setCellValue ('A1','INFORME DETALLADO DE COSUMOS DEL MES');
    $hojaActiva -> setCellValue ('A2','Id de consumo');
    $hojaActiva -> setCellValue ('B2','Consumidor');
    $hojaActiva -> setCellValue ('C2','Numero de orden');
    $hojaActiva -> setCellValue ('D2','Ubicacion de consumo');
    $hojaActiva -> setCellValue ('E2','Fecha de consumo');
    $hojaActiva -> setCellValue ('F2','Numero de articulo');
    $hojaActiva -> setCellValue ('G2','Descripcion');
    $hojaActiva -> setCellValue ('H2','Observacion');
    $hojaActiva -> setCellValue ('I2','Nomos');
    $hojaActiva -> setCellValue ('J2','Premedia');
    $hojaActiva -> setCellValue ('K2','Xpress');
    
    $consultar = mysqli_query($conexion,$preconsulta);
    $total = mysqli_num_rows($consultar);

    $fila= 3;
    while ($lista = mysqli_fetch_array($consultar)){
      $hojaActiva -> setCellValue ('A'.$fila,$lista[0]);
      $hojaActiva -> setCellValue ('B'.$fila,$lista[1]);
      $hojaActiva -> setCellValue ('C'.$fila,$lista[2]);
      $hojaActiva -> setCellValue ('D'.$fila,$lista[3]);
      $hojaActiva -> setCellValue ('E'.$fila,$lista[4]);
      $hojaActiva -> setCellValue ('F'.$fila,$lista[5]);
      $hojaActiva -> setCellValue ('G'.$fila,$lista[6]);
      $hojaActiva -> setCellValue ('H'.$fila,$lista[7]);
      $hojaActiva -> setCellValue ('I'.$fila,$lista[8]);
      $hojaActiva -> setCellValue ('J'.$fila,$lista[9]);
      $hojaActiva -> setCellValue ('K'.$fila,$lista[10]);

      $fila++;
    }
    $hojaActiva->getStyle('A1:K'.$fila.'')->applyFromArray($styleArray);

    $writer = new Xlsx($spreadsheet);
    $writer -> save("excel_generado/$nombre");
    echo "<script> window.location.href ='excel_generado/$nombre';</script>";
  }

?>
		<script src="../recursos/js/main.js"></script>
	</body>
</html>