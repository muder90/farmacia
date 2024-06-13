<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Historial de salidas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre, u.nombre as nom, p.descripcion as producto, d.cantidad FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente 
join usuario u on u.idusuario=v.id_usuario
join detalle_venta d on d.id_venta=v.id
join producto p on p.codproducto=d.id_producto
");
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        <b>Historial de Salidas de Médicamento</b>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-light" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Recibió</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th></th>
						<th>Entregó</th>
						<th>Producto</th>
						<th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td>
                                <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                            </td>
							<td><?php echo $row['nom']; ?></td>
							<td><?php echo $row['producto']; ?></td>
							<td><?php echo $row['cantidad']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>