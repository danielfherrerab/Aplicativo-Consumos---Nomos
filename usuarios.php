<?php
	include_once 'conexion/conexion.php';
	// error_reporting(0);
	session_start();
	if($_SESSION['Usuarios'] != 'si'){
		header('location: index.php');
	}
	$nombre_usuario = $_SESSION['nombre'];
	date_default_timezone_set("America/Bogota");
	setlocale(LC_ALL,"es_ES");
	$fecha_hoy = date("Y-m-d H:i:s");
	$mes_hoy = date("m");
	$fecha_hoy = date("Y-m-d H:i:s");

	$consultar_usuarios = mysqli_query($conexion,"SELECT * from usuarios");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="stylesheet" type="text/css" href="assets/dataTables/css/dataTables.dataTables.css"/>
		<link rel="stylesheet" href="assets/dataTables/css/jquery.dataTables.css">
		<!-- js -->
		<script src="assets/js/jQuery/jquery.min.js"></script>
		<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
		<script src="assets/dataTables/js/jquery.dataTables.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		<title>Usuarios - NOMOS</title>
		<style>
			input[type=radio ]{border-radius: 15px;background-color: white;-webkit-box-shadow: 3px 0 3px 0 rgba(0,0,0,0.2);box-shadow: 3px 0 3px 0 rgba(0,0,0,0.2);appearance: none;width: 30px;height: 30px;border: 2px solid #999;transition: 0.2s all linear;margin: auto 7px;position: relative;/* top: 7px; */border:solid 1px #c54646;background-color: #fa8989;}input[type=radio]:checked{-webkit-box-shadow: -3px 0 3px 0 rgba(0,0,0,0.2);box-shadow: 3px 0 -3px 0 rgba(0,0,0,0.2);border:solid 3px green;background-color: #cbffac;}input:checked {border: 6px solid blue;}.importacion {overflow: auto;font-size: 14px;max-height: 700px;}.importacion .table {font-size: 14px;width: 95%;}
		</style>
		
	</head>
	<body>
	<div class="contenedor_mayor">
    <div class="nav_superior">
        <div class="hamburger">
            <div class="one"></div>
            <div class="two"></div>
            <div class="three"></div>
        </div>
        <div class="top_menu">
            <div class="logo"><?php echo $nombre_usuario ?></div>
						<div class="logo">
							<form action="cambiar_clave.php" method="POST">
								<input type="submit" name="cambiar_clave" value="Cambiar clave" class="boton-usuario">
							</form>
						</div>
						<div class="logo">
							<form action="index.php" method="POST">
								<input type="submit" name="cerrar_sesion" value="Cerrar sesion" class="boton-usuario">
							</form>
						</div>
        </div>
    </div>

    <div class="nav_lateral">
        <ul>
					<?php if($_SESSION['Consumo_planchas'] == 'si'){ ?><li><a href="principal.php" 				>Inicio</a>									</li><?php } ?>
					<?php if($_SESSION['informe_consumo']  == 'si'){ ?><li><a href="consumo.php" 					>Informe de consumo</a>			</li><?php } ?>
					<?php if($_SESSION['Inventario'] 			 == 'si'){ ?><li><a href="inventario.php" 				>inventario</a>							</li><?php } ?>
					
					<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 	>Importar ordenes</a>				</li><?php } ?>
					<?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 					class="active">Usuarios</a>								</li><?php } ?>
					<?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  	>Corte de mes</a>						</li><?php } ?>
        </ul>
    </div>

    <div class="main_container pag_usuarios">
			<div class="total_usuarios">
				<header>
					Usuarios en el sistema
				</header>
				<div class="contenedor_tablas">
					<table id="tabla_usuarios" cellspacing = '5' border='0'>
						<thead>
              <tr>
                <th>Numero de usuario</th>
                <th>Nombre del usuario</th>
                <th>Correo del usuario</th>
								<th>Consumo de planchas</th>
                <th>Ubicaciones</th>
                <th>Inventario</th>
                <th>Informe de consumo</th>
                <th>Ver usuarios</th>
                <th>Importar ordenes</th>
                <th>Corte de mes</th>
								<th>Editar</th>
              </tr>
						</thead>

						<tbody>
							<?php 
								while ($lista = mysqli_fetch_array($consultar_usuarios)){
							?>
							<tr>
								<input type="hidden" id="nombre<?php echo $lista[0]; ?>" value="<?php echo $lista['nombre']; ?>">
								<input type="hidden" id="correo<?php echo $lista[0]; ?>" value="<?php echo $lista['correo']; ?>">
								<input type="hidden" id="consumo_planchas<?php echo $lista[0]; ?>" value="<?php echo $lista['consumo_planchas']; ?>">
								<input type="hidden" id="ubicaciones<?php echo $lista[0]; ?>" value="<?php echo $lista['ubicaciones']; ?>">
								<input type="hidden" id="inventario<?php echo $lista[0]; ?>" value="<?php echo $lista['inventario']; ?>">
								<input type="hidden" id="informe_consumo<?php echo $lista[0]; ?>" value="<?php echo $lista['informe_consumo']; ?>">
								<input type="hidden" id="usuarios<?php echo $lista[0]; ?>" value="<?php echo $lista['usuarios']; ?>">
								<input type="hidden" id="importar_ordenes<?php echo $lista[0]; ?>" value="<?php echo $lista['importar_ordenes']; ?>">
								<input type="hidden" id="corte_consumos<?php echo $lista[0]; ?>" value="<?php echo $lista['corte_consumos']; ?>">
								<td><?php echo $lista[0] ?></td>
								<td><?php echo $lista['nombre'] ?></td>
								<td><?php echo $lista['correo'] ?></td>
								<td <?php echo "class='$lista[consumo_planchas]'"; ?>> <?php echo $lista['consumo_planchas']; ?></td>
								<td <?php echo "class='$lista[ubicaciones]'";?>><?php echo $lista['ubicaciones'] ?></td>
								<td <?php echo "class='$lista[inventario]'";?>><?php echo $lista['inventario'] ?></td>
								<td <?php echo "class='$lista[informe_consumo]'";?>><?php echo $lista['informe_consumo'] ?></td>
								<td <?php echo "class='$lista[usuarios]'"?>><?php echo $lista['usuarios'] ?></td>
								<td <?php echo "class='$lista[importar_ordenes]'";?>><?php echo $lista['importar_ordenes'] ?></td>
								<td <?php echo "class='$lista[corte_consumos]'";?>><?php echo $lista['corte_consumos'] ?></td>
								<td><?php if($lista['id_usuario'] == 1){ echo "<button disabled>Modificar</button>"; }else{?><input type="submit" value="Modificar" onclick="modUsuario(this.id)" id="<?php echo $lista[0]; ?>"><?php }?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
          </table>
					</div>
				</div>
				<aside class="agregar_usuario">
					<form action="#" method="post">
					<div class="header">
						Agregar usuario
					</div>
					<div class="contenido">
						<p>Ingrese el nombre del usuario</p><input type="text" name="nuevo_usuario" placeholder="Ingrese el nombre del usuario">
						<p>Ingrese el correo del usuario</p><input type="text" name="nuevo_correo" placeholder="Ingrese el correo del usuario">
						<p>Ingrese la contraseña usuario</p><input type="text" name="nueva_clave" placeholder="Ingrese la contraseña del usuario">
						<p>Vuelva a ingresar la contraseña</p><input type="text" name="nueva_clave" placeholder="Ingrese la contraseña del usuario">
						<header>Permisos de usuario</header>

						<table style="text-align:left" cellspacing="5" border="1">
							<tr><td>Hacer consumo 					</td><td><div class="checkbox"><input class="tgl tgl-flip" id="consumo" name="consumo" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="consumo"></label></div></td></tr>
							<tr><td>Ver ubicaciones 				</td><td><div class="checkbox"><input class="tgl tgl-flip" id="ubicaciones"  name="ubicaciones" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="ubicaciones"></label></div></td></tr>
							<tr><td>Ver inventario 					</td><td><div class="checkbox"><input class="tgl tgl-flip" id="inventario" name="inventario" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="inventario"></label></div></td></tr>
							<tr><td>Ver informe de consumo 	</td><td><div class="checkbox"><input class="tgl tgl-flip" id="informe" name="informe" type="checkbox" value="si" checked/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="informe"></label></div></td></tr>
							<tr><td>Ver usuarios 						</td><td><div class="checkbox"><input class="tgl tgl-flip" id="usuarios" name="usuarios" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="usuarios"></label></div></td></tr>
							<tr><td>Importar ordenes 				</td><td><div class="checkbox"><input class="tgl tgl-flip" id="importar" name="importar" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="importar"></label></div></td></tr>
							<tr><td>Corte de mes						</td><td><div class="checkbox"><input class="tgl tgl-flip" id="Corte" name="Corte" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="Corte"></label></div></td></tr>
						</table>
					</div>
						<input type="submit" value="Añadir" name="AGREGAR_USUARIO">
					</form>
				</aside>

				<?php 
					if(isset($_POST['AGREGAR_USUARIO'])){
						$nuevo_nombre = $_POST['nuevo_usuario'];
						$nuevo_correo = $_POST['nuevo_correo'];
						$nueva_clave = $_POST['nueva_clave'];
						$nueva_clave = password_hash($nueva_clave, PASSWORD_DEFAULT);  
						$P_consumo = $_POST['consumo'];
						$P_ubicaciones = $_POST['ubicaciones'];
						$P_inventario = $_POST['inventario'];
						$P_informe = $_POST['informe'];
						$P_usuarios = $_POST['usuarios'];
						$P_importar = $_POST['importar'];
						$P_corte = $_POST['Corte'];
						$subir_usuario = mysqli_query($conexion,"INSERT INTO usuarios (correo,nombre,clave,consumo_planchas,ubicaciones,inventario,informe_consumo,usuarios,importar_ordenes,Corte_consumos)
						values ('$nuevo_correo','$nuevo_nombre','$nueva_clave','$P_consumo','$P_ubicaciones','$P_inventario','$P_informe','$P_usuarios','$P_importar','$P_corte')");
						if($subir_usuario){
							echo "<script> alert('Usuario agregado correctamente'); window.location.href ='usuarios.php';</script>";
						}
						else{
							echo "<script> alert('No se pudo guardar el usuario'); window.location.href ='usuarios.php';</script>";
						}
					}

				?>
				<aside class="modificar_usuario">
					<form action="#" method="post">
					<div class="header">
						Modificar usuario
					</div>
						<div class="info_usuario">
							<h3>Informacion del usuario</h3><br>
							Nombre del usuario <br><input type="text" name="modi_usuario" placeholder="Ingrese el nombre del usuario" value=""></p>
							Correo del usuario <br><input type="text" name="modi_correo" placeholder="Ingrese el correo del usuario" value=""></p>
							Clave del usuario <br><input type="text" name="modi_clave" placeholder="Ingrese la contraseña del usuario" value=""></p>
							Repita la clave <br><input type="text" name="confir_clave" placeholder="Ingrese la contraseña del usuario" value=""></p>
						</div>
						<div class="permisos">
							<header>
								Permisos de usuario
								</header>
							<table style="text-align:left" cellspacing="5" border="1">
								<tr nowrap="2"><td>Hacer consumo					</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_consumo" name="modi_consumo" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_consumo"></label></div></td></tr>
								<tr nowrap="2"><td>Ver ubicaciones				</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_ubicaciones" name="modi_ubicaciones" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_ubicaciones"></label></div></td></tr>
								<tr nowrap="2"><td>Ver inventario				</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_inventario"  name="modi_inventario" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_inventario"></label></div></td></tr>
								<tr nowrap="2"><td>Ver informe de consumo</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_informe" name="modi_informe" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_informe"></label></div></td></tr>
								<tr nowrap="2"><td>Ver usuarios					</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_usuarios" name="modi_usuarios" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_usuarios"></label></div></td></tr>
								<tr nowrap="2"><td>Importar ordenes			</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_importar" name="modi_importar" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_importar"></label></div></td></tr>
								<tr nowrap="2"><td>Corte de mes					</td><td><div class="checkbox"><input class="tgl tgl-flip" id="modi_corte" name="modi_corte" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="No" data-tg-on="Si" for="modi_corte"></label></div></td></tr>
							</table>
							<input type="submit" value="Modificar" name="MODIFICAR_USUARIO"><input type="button" value="Cancelar" onclick="quitarMod()">
						</div>
					</form>		
				</aside>

				<?php
					if(isset($_POST['MODIFICAR_USUARIO'])){
						$modi_usuario = $_POST['modi_usuario'];
						$modi_correo = $_POST['modi_correo'];
						$modi_clave = $_POST['modi_clave'];
						$confir_clave = $_POST['confir_clave'];
						$modi_consumo = $_POST['modi_consumo'];
						$modi_ubicaciones = $_POST['modi_ubicaciones'];
						$modi_inventario = $_POST['modi_inventario'];
						$modi_informe = $_POST['modi_informe'];
						$modi_usuarios = $_POST['modi_usuarios'];
						$modi_importar = $_POST['modi_importar'];
						$modi_corte = $_POST['modi_corte'];
						if($modi_clave == ""){
							$modificar_usuario = mysqli_query($conexion,"UPDATE usuarios SET correo = '$modi_correo',nombre = '$modi_usuario',consumo_planchas = '$modi_consumo',ubicaciones = '$modi_ubicaciones',inventario = '$modi_inventario',informe_consumo = '$modi_informe',usuarios = '$modi_usuarios',importar_ordenes = '$modi_importar',Corte_consumos = '$modi_corte' where id_usuarios = $id_editar");
						}
						else{
							if($modi_clave == $confir_clave){
								$modi_clave = password_hash($modi_clave, PASSWORD_DEFAULT);  
								$modificar_usuario = mysqli_query($conexion,"UPDATE usuarios SET correo = '$modi_correo',nombre = '$modi_usuario',clave = '$modi_clave',consumo_planchas = '$modi_consumo',ubicaciones = '$modi_ubicaciones',inventario = '$modi_inventario',informe_consumo = '$modi_informe',usuarios = '$modi_usuarios',importar_ordenes = '$modi_importar',Corte_consumos = '$modi_corte' where id_usuarios = $id_editar");
							}
							else{
								echo "<script> alert('debe llenar todos los campos'); </script>";
							}
						}
						if($modificar_usuario){
							echo "<script> alert('Usuario modificado correctamente'); window.location.href ='usuarios.php';</script>";
						}
						else{
							echo "<script> alert('No se pudo modificar el usuario'); window.location.href ='usuarios.php';</script>";
						}
					}
				?>
			</div>
		</section>
		
		<script>
			$(document).ready(function(){
				$(".contenedor_mayor").toggleClass("collapse");
				$(".hamburger").click(function(){
						$(".contenedor_mayor").toggleClass("collapse");
				});

				
				$('#tabla_usuarios').dataTable( {
					"language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
				} );
			});

			function modUsuario(id){
				var nombre = document.getElementById("nombre"+id).value;
				var correo = document.getElementById("correo"+id).value;
				var consumo_planchas = document.getElementById("consumo_planchas"+id).value;
				var ubicaciones = document.getElementById("ubicaciones"+id).value;
				var inventario = document.getElementById("inventario"+id).value;
				var informe_consumo = document.getElementById("informe_consumo"+id).value;
				var usuarios = document.getElementById("usuarios"+id).value;
				var importar_ordenes = document.getElementById("importar_ordenes"+id).value;
				var corte_consumos = document.getElementById("corte_consumos"+id).value;

				if(consumo_planchas == "si"){("inpu[type=checkbox]").eq(7).prop("checked",true);}
				if(ubicaciones == "si"){$("input[type=checkbox]").eq(8).prop("checked",true);}
				if(inventario == "si"){$("input[type=checkbox]").eq(9).prop("checked",true);}
				if(informe_consumo == "si"){$("input[type=checkbox]").eq(10).prop("checked",true);}
				if(usuarios == "si"){$("input[type=checkbox]").eq(11).prop("checked",true);}
				if(importar_ordenes == "si"){$("input[type=checkbox]").eq(12).prop("checked",true);}
				if(corte_consumos == "si"){$("input[type=checkbox]").eq(13).prop("checked",true);}

				$('input[name=modi_usuario').val(nombre);
				$('input[name=modi_correo').val(correo);
				$(".pag_usuarios").addClass("mod_usuario");
			}
			function quitarMod(){
				$(".pag_usuarios").removeClass("mod_usuario");
			}
		</script>
	</body>
</html>