<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
    <link rel="stylesheet" href="recursos/css/main.css">
		<link rel="stylesheet" href="recursos/css/estilos.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@800;900&display=swap" rel="stylesheet">
		
		<link rel="stylesheet" href="recursos/css/mostrar-tabla.css">
		<script src="recursos/js/jquery.min.js"></script>
		<script src="recursos/js/peticion5.js"></script>
		<title>Ordenes - NOMOS</title>
    <?php
				$DB_HOST = $_ENV['DB_HOST'];
				$DB_USER = $_ENV['DB_USER'];
				$DB_PASSWORD = $_ENV['DB_PASSWORD'];
				$DB_NAME = $_ENV['DB_NAME'];
				$DB_PORT = $_ENV['DB_PORT'];
			
				  $conexion=mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME,$DB_PORT);
			session_start();
			if($_SESSION['Importar_ordenes'] != 'si'){
				header('location: index.php');
			}
			$nombre_usuario = $_SESSION['nombre_usuario'];
      $result = "SELECT COUNT(*) as total_ordenes FROM ordenes";
			$NUM_ITEMS_BY_PAGE = 15;
			$resultado = $conexion->query($result);
			$row = $resultado -> fetch_assoc();
			$num_total_rows 			= $row['total_ordenes'];
      date_default_timezone_set("America/Bogota");
			setlocale(LC_ALL,"es_ES");
			$fecha_hoy = date("Y-m-d H:i:s");
		?>

  </head>
	<body onload="myFunction()">
		<div class="loading" id="loading"><div class="cargador"></div></div>
	<nav class="barra-navegacion">
			<div class="contenedor-logo">
				<a href="principal.php"><img src="recursos/imagenes/LOGO.png" width="60"></a>
			</div>

			<ul class="links">
				<?php if($_SESSION['Consumo_planchas'] == 'si'){ ?><li class="link"><a href="principal.php" class="delineado">Inicio</a></li><?php } ?>
				<?php if($_SESSION['Ubicaciones'] == 'si'){ ?><li class="link"><a href="ubicaciones.php" class="delineado">Ingreso de ubicaciones </a></li><?php } ?>
				<?php if($_SESSION['Inventario'] == 'si'){ ?><li class="link"><a href="inventario.php" class="delineado">inventario</a></li><?php } ?>
				<?php if($_SESSION['informe_consumo'] == 'si'){ ?><li class="link"><a href="consumo.php" class="delineado">Informe de consumo</a></li><?php } ?>
				<?php if($_SESSION['Usuarios'] == 'si'){ ?><li class="link"><a href="usuarios.php" class="delineado">Usuarios</a></li><?php } ?>
				<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li class="link"><a href="importar_ordenes.php" class="delineado">Importar ordenes</a></li><?php } ?>
			</ul>
			<div class="info_usuario">
				<div class="li">Perfil: <b><?php echo $nombre_usuario; ?></b></div><hr class="separador">
				<div class="li"><a href="cambiar_clave.php" class="delineado">Cambiar clave</a></div>
				</div>
			<form action="index.php" method="POST">
				<input type="submit" name="cerrar_sesion" value="Cerrar sesion" class="boton-usuario">
			</form>
			<div id="toggle" class="boton-menu"></div>
		</nav>
    
    <div class="contenedor_manejar_ordenes">
      <header class="header">
        <h2>AGREGAR O MODIFICAR</h2>
      </header>
      <aside class="importacion">
        <form action="recibir_excel/prueba.php" method="POST" enctype="multipart/form-data">
          <h3>Para importar ordenes con excel</h3>
              <input  type="file" name="dataCliente" id="file-input" accept=".xlsx">
            <input type="submit" name="subir" class="btn-enviar" value="Subir Excel"/><br><br>
						<h4>--- > Como importar correctamente un archivo excel para subir ordenes de produccion < ---</h4>
        </form>
				<div class="contenedor_manejar_ordenes">
					<aside class="importacion">
						<ol>
							<li>El archivo excel debe contener 4 columnas las cuales son <b>Numero de OP,Nombre del trabajo</b>,<b>la fecha en la que se ordenaron</b> y la <b>cantidad planeada</b> y se debe dejar una fila de encabezado de la tabla</li><br>
						</ol>
						<br><img src="recursos/imagenes/formato.png" alt="imagen de ejemplo para subir excel" width="100%">
      		</aside>
					<aside class="agregar_ordenes">
						<ol start="2">
							<li>La fecha de las ordenes debe tener formato de <b>fecha corta y al momento de guardar el archivo se debe dejar con extension xlsx</b></li><br>
						</ol><br>
						<img src="recursos/imagenes/formato_horas.png" alt="imagen de ejemplo para subir excel" width="100%">
					</aside>
					<!-- <aside class="paginacion">
					<ol start="3">
							<li>El archivo se debe guardar como formato <b>CSV </b>(delimitado por comas)</li><br>
						</ol><br>
						<img src="recursos/imagenes/extension.png" alt="imagen de ejemplo para subir excel" width="100%">
					</aside> -->
					</div>
			</aside>
      <aside class="agregar_ordenes">
					<h1>Agregar OP</h1>
					<fieldset><form action="#" method="post">
						<p>Ingrese el numero de la orden<input type="text" name="nuevo_numero" placeholder="Ingrese el numero de la OP"></p>
						<p>Ingrese el nombre del trabajo<input type="text" name="nueva_orden" placeholder="Ingrese la orden de produccion"></p>
						<p>Ingrese la cantidad planeada de la orden<input type="text" name="cantidad_planeada" placeholder="Ingrese la cantidad planeada"></p>
						<input type="submit" value="Añadir" name="AGREGAR_OP">
						</form></fieldset>
      </aside>
    <aside class="paginacion"><br>
    <h1>Paginación de ordenes</h1><br>
    <h4>Puede buscar por numero de OP o puede buscar por fecha
    <input type="text" placeholder="Ingrese su busqueda"  id="busqueda_paginacion"></h4><br>
      							<section id="tabla_paginacion" class="apilacion_ordenes">
								<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
							</section>
		</aside>
  </div>
        		

	<?php
		if(isset($_POST['AGREGAR_OP'])){
			$nuevo_numero = $_POST['nuevo_numero'];
			$nueva_orden = $_POST['nueva_orden'];
			$cantidad = $_POST['cantidad_planeada'];

			$buscar_orden = mysqli_query($conexion,"SELECT * from ordenes where numero_orden = $nuevo_numero or orden = '$nueva_orden'");
			if(mysqli_num_rows($buscar_orden) > 0){
				while ($lista = mysqli_fetch_array($buscar_orden)){
					$numero = $lista[0];
					$orden = $lista[1];
					$cantidad_planeada = $lista[3];
				}
				if(($cantidad > $cantidad_planeada) or ($nueva_orden != $orden)){
					$actualizar_orden = mysqli_query($conexion,"UPDATE ordenes set orden = '$nueva_orden',Cantidad_planeada = $cantidad where numero_orden = $nuevo_numero");
					if ($actualizar_orden){
						echo "<script>
										alert ('Se actualizo la OP con la informacion dada debido a que ya se encontraba una orden registrada');
										window.location.href ='importar_ordenes.php';
									</script> ";
					}
					else{
						echo "<script>
										alert ('No se pudo guardar');
										window.location.href ='importar_ordenes.php';
									</script>";
					}
				}
			}
			else{
				$ingresar_Datos = mysqli_query($conexion,"INSERT into ordenes (Numero_Orden,Orden,Fecha_Ingreso) values ('$nuevo_numero','$nueva_orden','$fecha_hoy')");

				if ($ingresar_Datos){
					echo "<script>
									alert ('OP añadido con exito');
									window.location.href ='importar_ordenes.php';
								</script> ";
				}
				else{
					echo "<script>
									alert ('no se pudo guardar');
									window.location.href ='importar_ordenes.php';
								</script>";
				}
			}
		}
  ?>
		<script>
			const boton_menu = document.querySelector('.boton-menu');
			const links = document.querySelector('.links');

			boton_menu.addEventListener('click', () =>{
				boton_menu.classList.toggle('rotate')
				links.classList.toggle('activar-menu')
			})
			const Inicial = document.getElementById('disponibles')
			const Final  = document.getElementById('cantidad')

			Final.addEventListener('change',()=>{
					Final.setAttribute('max',Inicial.value)
					
			})
		</script>
		<script src="recursos/js/main.js"></script>
</body>
</html>