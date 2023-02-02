<?php
	include_once 'conexion/conexion.php';
	$dia = date('Y-m-01 H:i:s');
	$fecha = date("Y-m-d H:i:s");
	
	if (isset($_POST['cerrar_sesion'])){
		session_unset();
		unset($_SESSION["correo"]);
		session_destroy();//header('Location:../login.php');
	}

	if (isset($_SESSION['Consumo_planchas'])){
		switch($_SESSION['Consumo_planchas']){
			case 'si':
				header('Location: principal.php');		break;
			case 'no':
				header('Location: consumo.php');			break;
			default:
				echo "no estoy en nada";							break;
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- css -->
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="stylesheet" href="assets/css/mostrar-tabla.css">
		<link rel="stylesheet" type="text/css" href="assets/dataTables/css/dataTables.dataTables.css"/>
		<link rel="stylesheet" href="assets/dataTables/css/jquery.dataTables.css">
		<!-- js -->
		<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
		<script src="assets/dataTables/js/jquery.dataTables.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		<title>INICIO SESION</title>
  </head>

  <body>
	<div class="logo_superior"></div>
		<div class="inicio_sesion">
			<h1>Iniciar Sesion</h1>
			<fieldset>
			<h3>Formato consumo de inventario</h3>
				<form action="" method="POST">
					<h4>Digite su correo electronico</h4>
					<input type="email" placeholder="Ingrese su correo electronico" name="correo" required class="btn_texto"><br><br>
					<h3>Digite su contraseña</h3>
					<input type="password" placeholder="Ingrese su contraseña" name="clave" required><br><br><br>
					<input type="submit" value="INGRESAR">
				</form>
			</fieldset>
			<a href="#modal" id="show-modal"><button>Ver consumos</button></a>
    <?php
			if (isset($_POST['correo']) && isset($_POST['clave'])) {
				$username = mysqli_real_escape_string($conexion, $_POST["correo"]);  
				$password = mysqli_real_escape_string($conexion, $_POST["clave"]);  
				$query 		= "SELECT * FROM usuarios WHERE correo = '$username'";  
				$result 	= mysqli_query($conexion, $query); 

				if(mysqli_num_rows($result) >= 1) {  
					while($row = mysqli_fetch_array($result)) {  
						if(password_verify($password, $row["clave"])) {  
								$username = $_POST['correo'];
								$password = $_POST['clave'];
										$Consumo_planchas 						= $result[4];
										$_SESSION['Consumo_planchas'] = $Consumo_planchas;

										switch($Consumo_planchas) {
											case 'si':
												header('Location: principal.php');		break;
											case 'no':
												header('Location: consumo.php');			break;
											default:
												echo "no estoy en nada";							break;
										}

										$id_usuario 								= $result[0];
										$_SESSION['id_usuario'] 			= $id_usuario;
										$correo 							= $result[2];
										$_SESSION['correo'] 	= $correo;
										$nombre_usuario 							= $result[1];
										$_SESSION['nombre'] 	= $nombre_usuario;
										$Ubicaciones 									= $result[5];
										$_SESSION['Ubicaciones'] 			= $Ubicaciones;
										$Inventario 									= $result[6];
										$_SESSION['Inventario'] 			= $Inventario;
										$informe_consumo 							= $result[7];
										$_SESSION['informe_consumo'] 	= $informe_consumo;
										$Usuarios 										= $result[8];
										$_SESSION['Usuarios'] 				= $Usuarios;
										$Importar_ordenes 						= $result[9];
										$_SESSION['Importar_ordenes'] = $Importar_ordenes;
										$Corte_consumos 							= $result[10];
										$_SESSION['Corte_consumos'] 	= $Corte_consumos;

						}  
						else  {  
							echo "<div class='mensaje'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
						}  
					}  
				}  
				else {  
					echo "<div class='mensaje'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>"; 
				} 
			}
		?>
  </div>
		<aside id="modal" class="modal">
			<div class="content-modal">
				<aside class="tabla-prev">
								<?php include_once "extensiones/consulta_registros.php"; ?>
							</section>
						<a href="#" class="close-modal"><button class="cerrar">Cerrar</button></a>
				</aside>
			</div>
		</aside>
	
		<script>
			$.ajax({
					url : 'extensiones/cerrar_corte.php',
					type : 'POST',
					dataType : 'html',
			})
			function cambiarMarcador(idMarcador){
				var valorMarcador = document.getElementById(idMarcador).value;
				
				if ($('#'.idMarcador).is(':checked')) {
					valorMarcador = 0;
				} else {
					valorMarcador = 1;
				}
				console.log(idMarcador);
				
				$('#fila'+idMarcador).toggleClass('TRUE');
				$.ajax({
					url : 'extensiones/cambiarMarcador.php',
					type : 'POST',
					dataType : 'html',
					data : {idMarcador: idMarcador, valorMarcador: valorMarcador},
					})

				.done(function(resultado){

				})
			}

		$(document).ready(function(){
				$('#tabla_consumos').dataTable( {
						"language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
				});
		});

	</script>
  </body>
</html>