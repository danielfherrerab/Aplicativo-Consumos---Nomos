<?php
$conexion = mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');$conexion=mysqli_connect('localhost','root','','bd_consumos') or die ('problemas en la conexion');
  require 'convertir_excel/vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\SpreadSheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  $spreadsheet = new SpreadSheet();
  $spreadsheet -> getProperties() -> setcreator("Nomos") -> setTitle("Registros");

  if(isset($_GET['ingreso'])){
    $ingreso_ordenes = $_GET['ingreso'];
    $total = $_GET['repetido'];    
    echo "<br>Se importaron $ingreso_ordenes ordenes. Se contaron $total registros repetidos que se subieron y actualizaron en una tabla aparte que podra descargar";
    echo '<br><a href="excel_generado/Registros.xlsx">DESCARGAR REPORTE</a>';
    echo '<br><a href="importar_ordenes.php">VOLVER AL INICIO</a>';
    $ingreso_ordenes = $_GET['ingreso'];$sin_subir = $_GET['repetido'];
  }
  if(isset($_GET['numero'])){ 
    echo "POR FAVOR ESPERE A QUE SE DESCARGUE EL ARCHIVO";
    echo "SI SE COMIENZA A DESCARGAR UN ARCHIVO EXCEL SE DEBE A QUE HUBIERON ORDENES REPETIDAS";
    $spreadsheet -> setactivesheetindex(0);
    $hojaActiva = $spreadsheet -> getActiveSheet();
    $hojaActiva -> getColumnDimension('A') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('B') ->setAutoSize(true);
    $hojaActiva -> getColumnDimension('C') ->setAutoSize(true);
    $hojaActiva -> mergeCells('A1:D1');

    $styleArray = [
      'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
    ];
    $hojaActiva->getStyle('A1')->applyFromArray($styleArray);

    $hojaActiva -> setCellValue ('A1','REGISTROS NO AGREGADOS AL SISTEMA');
    $hojaActiva -> setCellValue ('A2','Numero OP') -> setCellValue ('B2','Nombre del trabajo') -> setCellValue ('C2','Fecha de ingreso') -> setCellValue ('D2','CP');
    $consultar = mysqli_query($conexion,"SELECT * from ordenes_faltantes");
    $total = mysqli_num_rows($consultar);
    $i = 3;
    while ($lista = mysqli_fetch_array($consultar)){
      $numero = $lista[0];
      $orden = $lista[1];
      $fecha = $lista[2];
      $cantidad = $lista[3];
        $hojaActiva -> setCellValue ('A'.$i.'',$numero);
        $hojaActiva -> setCellValue ('B'.$i.'',$orden);
        $hojaActiva -> setCellValue ('C'.$i.'',$fecha);
        $hojaActiva -> setCellValue ('D'.$i.'',$cantidad);
        $i++;
    }
    $writer = new Xlsx($spreadsheet);
    $writer -> save("excel_generado/Registros.xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Registros.xlsx"');
    header('Cache-Control: max-age=0');
  
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'xlsx');
    // $writer -> save("excel_generado/Registros.xlsx");
    // $writer->save('php://output');
    // $borrar_duplicados = mysqli_query($conexion,"DELETE from ordenes_faltantes");
    echo "<script> window.location.href ='excel_generado/Registros.xlsx.php';</script>";
  }
?>