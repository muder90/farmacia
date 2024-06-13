<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Medicamentos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
	$preciou = $_POST['preciou'];
    $cantidad = $_POST['cantidad'];
	$cantporunidad = $_POST['cantporunidad'];
	$maximo = $_POST['maximo'];
	$minimo = $_POST['minimo'];
	$mensual = $_POST['mensual'];
	$uso = $_POST['uso'];
    $tipo = $_POST['tipo'];
    $presentacion = $_POST['presentacion'];
    $laboratorio = $_POST['laboratorio'];
    $vencimiento = '';
	 $hoy = new DateTime();
	date_default_timezone_set('America/Mazatlan');
	$hoy = $hoy->format('Y-m-d');
	
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
    }
    if (empty($codigo) || empty($producto) || empty($tipo) || empty($presentacion) || empty($laboratorio)  || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad <  0 ) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El codigo ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,preciou,existencia,cantporunidad, id_lab,id_presentacion,id_tipo, vencimiento, maximo, minimo, mensual, uso) values ('$codigo', '$producto', '$precio','$preciou', '$cantidad','$cantporunidad', $laboratorio, $presentacion, $tipo, '$vencimiento', '$maximo', '$minimo','$mensual', '$uso')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }
            }
        } else 
		{
            $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= $precio,  preciou= $preciou, existencia = $cantidad, cantporunidad = '".$cantporunidad."' id_lab = '".$laboratorio ."',  id_presentacion = '". $presentacion ."' , id_tipo = '".$tipo."', vencimiento = '$vencimiento', maximo = '$maximo', minimo = '$minimo', uso = '$uso', mensual ='$mensual', fechaact='".$hoy."' WHERE codproducto = $id");
            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
			else {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
}
include_once "includes/header.php";
?>
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Médicamentos
                    </div>
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i><b> Clave </b></label>
                                        <input type="text" placeholder="Ingrese clave" name="codigo" id="codigo" class="form-control">
                                        <input type="hidden" id="id" name="id">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="producto" class=" text-dark font-weight-bold"><b>Nombre del Medicamento</b></label>
                                        <input type="text" placeholder="Ingrese nombre del médicamento" name="producto" id="producto" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="precio" class=" text-dark font-weight-bold"><b>Precio</b></label>
                                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                                    </div>
                                </div>
								
								<div class="col-md-2">
                                    <div class="form-group">
                                        <label for="precio" class=" text-dark font-weight-bold"><b>Precio Unidad</b></label>
                                        <input type="text" placeholder="Ingrese precio" class="form-control" name="preciou" id="preciou">
                                    </div>
                                </div>
								
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidad" class=" text-dark font-weight-bold"><b>Stock</b></label>
                                        <input type="number" placeholder="Ingrese cantidad actual" class="form-control" name="cantidad" id="cantidad">
                                    </div>
                                </div>
								
								<div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidad" class=" text-dark font-weight-bold"><b>Stock por unidad</b></label>
                                        <input type="number" placeholder="Ingrese el total de unidades por medicamento" class="form-control" name="cantporunidad" id="cantporunidad">
                                    </div>
                                </div>
								
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo" class=" text-dark font-weight-bold"><b>Familia o Tipo</b></label>
                                        <select id="tipo" class="form-control" name="tipo" required>
                                            <?php
                                            $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos order by tipo");
                                            while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['tipo'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="presentacion" class=" text-dark font-weight-bold"><b>Presentación</b></label>
                                        <select id="presentacion" class="form-control" name="presentacion" required>
                                            <?php
                                            $query_pre = mysqli_query($conexion, "SELECT * FROM presentacion");
                                            while ($datos = mysqli_fetch_assoc($query_pre)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="laboratorio" class=" text-dark font-weight-bold"><b>Laboratorio</B></label>
                                        <select id="laboratorio" class="form-control" name="laboratorio" required>
                                            <?php
                                            $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios");
                                            while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['laboratorio'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="vencimiento" class=" text-dark font-weight-bold"><b>Caducidad</b></label>
                                        <input id="vencimiento" class="form-control" type="date" name="vencimiento">
                                    </div>
                                </div>
								
								<div class="col-md-3">
                                    <div class="form-group">
                                        
                                        <label for="maximo" class=" text-dark font-weight-bold"><b>Máximo</b></label>
                                        <input id="maximo" class="form-control" type="number" name="maximo">
                                    </div>
                                </div>
								
								<div class="col-md-3">
                                    <div class="form-group">
                                       
                                        <label for="mimimo" class=" text-dark font-weight-bold"><b>Mínimo</b></label>
                                        <input id="minimo" class="form-control" type="number" name="minimo">
                                    </div>
                                </div>
								
								<div class="col-md-3">
                                    <div class="form-group">
                                       
                                        <label for="mensual" class=" text-dark font-weight-bold"><b>Consumo Ménsual</b></label>
                                        <input id="mensual" class="form-control" type="number" name="mensual">
                                    </div>
                                </div>
								
								 <div class="col-md-12">
                                    <div class="form-group">
                                        
                                        <label for="uso" class=" text-dark font-weight-bold"><b>Descripción de uso</b></label>
                                        <input id="uso" class="form-control" type="text" name="uso">
                                    </div>
                                </div>
							
                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-align: center;" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Familia</th>
                            <th>Presentacion</th>
                            <th>Precio</th>
							<th>Precio Unidad</th>
                            <th>Stock</th>
							<th>Cantidad por unidad</th>
							<th>Minimo</th>
							<th>Maximo</th>
							<th>Mensual</th>
							<th>Estatus</th>
							<th>Caducidad</th>
                            <th>Acción</th>
							<th>Actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT p.*, t.id, t.tipo, pr.id, pr.nombre, p.minimo, p.maximo, p.mensual, p.vencimiento FROM producto p INNER JOIN tipos t ON p.id_tipo = t.id INNER JOIN presentacion pr ON p.id_presentacion = pr.id");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['codproducto']; ?></td>
                                    <td><?php echo $data['codigo']; ?></td>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td><?php echo $data['tipo']; ?></td>
                                    <td><?php echo $data['nombre']; ?></td>
                                    <td><?php echo $data['precio']; ?></td>
									<td><?php echo $data['preciou']; ?></td>
                                    <td><?php echo $data['existencia']; ?></td>
									<td><?php echo $data['cantporunidad']; ?></td>
									<td><?php echo $data['minimo']; ?></td>
									<td><?php echo $data['maximo']; ?></td>
									<td><?php echo $data['mensual']; ?></td>
									<td <?php if ($data['existencia'] <= $data['minimo']) {echo 'bgcolor=red';} elseif ($data['existencia'] <= $data['maximo']) {echo 'bgcolor=yellow';}  elseif ($data['existencia'] >= $data['maximo']) {echo 'bgcolor=green';} ?> ><b><?php if ($data['existencia'] <= $data['minimo']) {echo "PEDIR";} elseif($data['existencia'] <= $data['maximo']){echo "INSUFICIENTE";}  elseif($data['existencia'] >= $data['maximo']){echo "PERFECTO";}  ; ?><b/></td>
									<td><?php echo $data['vencimiento']; ?></td>
                                    <td>
                                        <a href="#" onclick="editarProducto(<?php echo $data['codproducto']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>

                                        <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                        </form>
                                    </td>
									<td><?php echo $data['fechaact']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>