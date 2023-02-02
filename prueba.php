<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
<?php 
include_once 'conexion/conexion.php';

$disp_fecha = array();

$query = "SELECT fecha_corte FROM corte_consumos where estado = 'disponible'";

$result = mysqli_query($conexion, $query);

while ($row = mysqli_fetch_assoc($result)) {
  $fecha = substr($row['fecha_corte'], 5);
  $disp_fecha[] = $fecha;
}
$disp_fecha[] = date("m");

?>
<form action="">
<input type="datetime-local" name="" id="miInput">
<input type="submit" value="enviar">
</form>
<script>
  // Convertir las disp_fecha en objetos Date
  var fechas = <?php echo json_encode($disp_fecha); ?>;

  var mesesDisponibles = [];
  for (var i = 0; i < fechas.length; i++) {
  var fecha = new Date(fechas[i]);
  var mes = fecha.getMonth();
  if (!mesesDisponibles.includes(mes)) {
    mesesDisponibles.push(mes);
  }
}



// Validar la fecha seleccionada por el usuario
$("form").submit(function(event) {
  event.preventDefault();
  var fechaSeleccionada = new Date($("input[type='datetime-local']").val());
  var mesSeleccionado = fechaSeleccionada.getMonth();
  if (!mesesDisponibles.includes(mesSeleccionado)) {
    alert("El mes seleccionado no está disponible");
  }
});

</script>