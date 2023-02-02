
<table border="1" id="tabla_medidas">
	<thead>
		<tr>
			<th>Medida</th>
			<th>Cantidad</th>
		</tr>
	</thead>
	<tbody>
		<?php
		include_once '../conexion/conexion.php';
		$medidas = mysqli_query($conexion, "SELECT distinct(medida) from articulos");
		if(isset($_POST['medida'])){
			$parametro	= $conexion->real_escape_string($_POST['medida']);
			$medidas = mysqli_query($conexion, "SELECT distinct(medida) from articulos where medida like '%$parametro%'");
		}
		$total = 0;
		if(mysqli_num_rows($medidas) >= 1){
			while($refe = mysqli_fetch_array($medidas)){
				$consumos = mysqli_query($conexion, "SELECT sum(cantidad) as cantidad,medida from consumo_planchas as consumo inner join articulos on articulos.id_articulo = consumo.id_articulo where medida like '%$refe[medida]%' and  (fecha_consumo between '$solo_fecha' and '$fecha_final')");
				while($recorrer = mysqli_fetch_array($consumos)){
					echo "<tr><td>$recorrer[medida]</td><td>$recorrer[cantidad]</td></td></tr>";
					$total = $total + $recorrer["cantidad"];
				}
			}
		}
		else{
			echo "<tr><td colspan='7'>No se encontraron registros</td></tr>";
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th>TOTAL</th><th><?php echo $total; ?></th>
		</tr>
	</tfoot>
</table>
