<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../recursos/css/main.css">
  <title>Exportar resumido - NOMOS</title>
</head>
<body onload="myFunction()">
	<div class="loading" id="loading"><div class="cargador"></div></div>

<?php
$conexion = mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');$conexion=mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');
  require 'convertir_excel/vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\SpreadSheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  $spreadsheet = new SpreadSheet();
  $spreadsheet -> getProperties() -> setcreator("Nomos") -> setTitle("Informe_resumido");
  date_default_timezone_set("America/Bogota");
  setlocale(LC_ALL,"es_ES");
  $fecha = date("Y_m_h_i");
  $nombre = $fecha."_Informe_resumido.xlsx";
  $dia 					= date('Y-m-01');
  $fecha 				= date("Y-m-d H:i:s");
  $date = new DateTime('now');
  $date->modify('last day of this month');
  $fecha_final =  $date->format('Y-m-d');

  if(isset($_POST['from'])){
    echo "<br>Se genero su informe en formato excel";
    $desde = $_POST['from'];
    $hasta = $_POST['until'];
    echo "<script> window.location.href ='exportar_resumido.php?numero=$desde&hasta=$hasta';</script>";
  }
  if(isset($_GET['ingreso'])){
    ?>
    
    <form action="" method="post">
      Desde <input type="date" name="from" value="<?php echo $dia;?>" min="2022-01-01" max="2023-12-31"> 
      Hasta <input type="date" name="until" value="<?php echo $fecha_final;?>" min="2022-01-01" max="2023-12-31"><br><br>
      <br><input type="submit" value="DESCARGAR REPORTE">
    </form>

  <?php
  }
  
  if(isset($_GET['numero'])){ 
    $desde = $_GET['numero'];
    $hasta = $_GET['hasta'];
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

    $hojaActiva -> getStyle("A")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
    $hojaActiva -> mergeCells('A1:F1');

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

    $hojaActiva -> setCellValue ('A1','INFORME RESUMIDO DE COSUMOS DEL MES');
    $hojaActiva -> setCellValue ('A2','Articulo');
    $hojaActiva -> setCellValue ('B2','Descripcion');
    $hojaActiva -> setCellValue ('C2','En Nomos');
    $hojaActiva -> setCellValue ('D2','En Premedia');
    $hojaActiva -> setCellValue ('E2','En Xpress');
    $hojaActiva -> setCellValue ('F2','TOTAL CONSUMIDO');
    
    $query = "SELECT * from articulos where articulos.Estado = 'habilitado' ORDER BY Numero_articulo";
    $buscarAlumnos = mysqli_query($conexion,$query);

    $fila= 3;
    while($filaRegistros= $buscarAlumnos->fetch_array()) {
      $articulo = $filaRegistros[0];
      $descripcion = $filaRegistros[1];

      $hojaActiva -> setCellValue ('A'.$fila,$articulo);
      $hojaActiva -> setCellValue ('B'.$fila,$descripcion);

      $almacen = '';
      $selector = mysqli_query($conexion,"SELECT * FROM lugar_ingreso");
      $row = $selector -> fetch_assoc();
      $num_total_rows = "(".$row['Lugar_Ingreso'].")";
      while ($consulta = mysqli_fetch_array($selector)){
        $sum_ubica = $consulta['Lugar_Ingreso'];
        $almacen.= " + (".$sum_ubica.')';
      }
      $resultado = $num_total_rows . $almacen;

      $sumatoria = mysqli_query($conexion,"SELECT Numero_Articulo,Descripcion,sum($resultado) as cantidad_consumida from consumo_planchas WHERE (Fecha_Actual between '$desde' and '$hasta') and Numero_Articulo = $articulo");
      while ($linea=mysqli_fetch_array($sumatoria)) {
        $En_consumo     	= $linea['cantidad_consumida'];
      }

      $selector = mysqli_query($conexion,"SELECT lugar_ingreso FROM lugar_ingreso");$i = 1;$variable = ['','C','D','E'];
        while ($query = mysqli_fetch_array($selector)){
          $ubicacion = $query['lugar_ingreso'];
          $suma_ubicacion = mysqli_query($conexion,"SELECT sum($ubicacion) as total FROM consumo_planchas where (Fecha_Actual between '$desde' and '$hasta') and numero_articulo = '$articulo'");
          
          while ($listado = mysqli_fetch_array($suma_ubicacion)){
            $suma_total = $listado['total'];
            $hojaActiva -> setCellValue ($variable[$i].$fila,$suma_total);
          }
          $i++;
        }
        
        $hojaActiva -> setCellValue ('F'.$fila,$En_consumo);

      $fila++;
    }
    $hojaActiva->getStyle('A1:F'.$fila.'')->applyFromArray($styleArray);

    $writer = new Xlsx($spreadsheet);
    $writer -> save("excel_generado/$nombre");
    echo "<script> window.location.href ='excel_generado/$nombre';</script>";
  }
?>
		<script src="../recursos/js/main.js"></script>
	</body>
</html>
