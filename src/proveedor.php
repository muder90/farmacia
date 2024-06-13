<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Laboratorios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['laboratorio']) ) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $laboratorio = $_POST['laboratorio'];
        $direccion = $_POST['direccion'];
		$telefono = $_POST['telefono'];
		$telefono2 = $_POST['telefono2'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM proveedores WHERE proveedor = '$laboratorio'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El proveedor ya existe !!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO proveedores (proveedor, direccion, telefono, telefono2) values ('$laboratorio', '$direccion', '$telefono', '$telefono2')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Proveedor registrado !!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al registrar !!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE proveedores SET proveedor = '$laboratorio', direccion = '$direccion', telefono='$telefono', telefono2='$telefono2'  WHERE id = $id");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Proveedor Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
    mysqli_close($conexion);
}
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="laboratorio" class="text-dark font-weight-bold"><b>PROVEEDOR</b></label>
                                <input type="text" placeholder="Ingrese proveedor" name="laboratorio" id="laboratorio" class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold"><b>DIRECCIÓN</b></label>
                                <input type="text" placeholder="Ingrese Dirección" name="direccion" id="direccion" class="form-control">
                            </div>
                        </div>
						
						<div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold"><b>TELÉFONO</b></label>
                                <input type="text" placeholder="Ingrese Teléfono 1" name="telefono" id="telefono" class="form-control">
                            </div>
                        </div>
						
						<div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold"><b>TELÉFONO 2</b></label>
                                <input type="text" placeholder="Ingrese Teléfono 2" name="telefono2" id="telefono2" class="form-control">
                            </div>
                        </div>
						
						
						
                        <div class="col-md-4 mt-4">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Proveedor</th>
                                <th>Dirección</th>
								<th>Teléfono 1</th>
								<th>Teléfono 2</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM proveedores");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['id']; ?></td>
                                        <td><?php echo $data['proveedor']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
										<td><?php echo $data['telefono']; ?></td>
										<td><?php echo $data['telefono2']; ?></td>
                                        <td style="width: 200px;">
                                            <a href="#" onclick="editarPro(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_proveedor.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>