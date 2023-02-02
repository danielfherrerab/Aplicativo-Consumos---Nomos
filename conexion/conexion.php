<?php
	session_start();
	class Database{
		private $servidorlocal;
		private $basededatos;
		private $nombre;
		private $password;
		private $charset;

		public function __construct(){
				$this->servidorlocal = 'localhost';
				$this->basededatos 	 = 'consumos';
				$this->nombre       = 'root';
				$this->password         = '';
				$this->charset    = 'utf8';
		}
		function connect(){
			try{
				$conexion = "mysql:host=".$this->servidorlocal.";dbname=".$this->basededatos.";charset=".$this->charset;
				$opciones = [
				PDO::ATTR_ERRMODE 		    => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES  => false, ];
				$pdo = new PDO($conexion, $this->nombre, $this->password,$opciones);
				return $pdo;
			}
			catch(PDOException $e){
				print_r('Error en la conexion:  '.$e->getMessage());
			}
		}
	}
	
  $conexion=mysqli_connect('localhost','root','','consumos') or die ('problemas en la conexion');

	date_default_timezone_set("America/Bogota");
	setlocale(LC_ALL,"es_ES");

	$corteEnCurso = date("Y-m");

	$fecha_mes = date("Y-m-d");
	$fecha_hoy 					= date("Y-m-d H:i:s");
	$mes_hoy 						= date("m");
	$dia 								= date('Y-m-01 00:00:00');
	$fecha_cierre 			= date('Y-m-05 00:00:00');
	$fecha 							= date("Y-m-d H:i:s");
	$solofecha_cero 		= date("Y-m-d 00:00:00");

	$meses = ['Default','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

	/* principal */
	$fecha_minima 	= date('Y-m-01 00:00:00');
	$mes_anterior 	= date("m",strtotime($dia."- 1 month,"));
	$final = new DateTime('now');
	$final->modify('last day of this month');
	$fecha_maxima =  $final->format('Y-m-d 23:59:59');

	/* ordenes_consumos */
	
	$fecha_hoy_final 		= date("Y-m-d 23:59:59");
	$fecha_hoy_inicio 		= date("Y-m-d 00:00:00");
	$fecha_inicio = date("Y-m-01");
	$date = new DateTime('now');
	$date->modify('last day of this month');
	$fecha_final =  $date->format('Y-m-d');
	$datetime_final =  $date->format('Y-m-d 23:59:59');

	/* extension consulta_medidas */
	$solo_fecha = date('Y-m-01 00:00:00');

	$buscar_cortes = mysqli_query($conexion, "SELECT * from corte_consumos");
?>