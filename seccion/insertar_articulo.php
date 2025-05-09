<?php 
session_start();

//Añado una verificacion extra para que Alguien que no tenga acceso pueda entrar a un Scrip no permitido 
if($_SESSION['id_nivel'] !=3 && $_SESSION['id_nivel'] !=5){
    header('Location: index.php');
    exit;
}

if(empty($_SESSION['id_usuario']) or empty($_SESSION['id_nivel'])){
    header('Location: cerrarsesion.php');
    exit;
 }

//Añado una verificacion extra para que Alguien que no tenga acceso pueda entrar a un Scrip no permitido 
if($_SESSION['id_nivel']!=3 && $_SESSION['id_nivel']!=5){
    header('Location: index.php');
    exit;
} 

require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();

require_once 'funciones/select_funciones.php';
$ListadoTipoArticulo=Listar_Tipo_Articulos($MiConexion);
$CantidadTipoArtiuculo = count($ListadoTipoArticulo);

require_once 'funciones/validacion_de_datos.php';
require_once 'funciones/insert_funciones.php';
require_once 'funciones/subir_archivo.php';

if (!empty($_POST['BotonInsertar'])) {
    //estoy en condiciones de poder validar los datos	
    if (Validar_Articulo()!=false) {
        /**** subo el archivo ***/
        if (SubirArchivo('Imagen_Menu') != false ) {
            if (InsertarArticulo($MiConexion) != false) {
                $_SESSION['Mensaje'] = 'Se ha registrado correctamente.';
                $_POST = array(); 
                $_SESSION['Estilo']= 'success';
            }
        }
    }
}

require_once 'header.inc.php'; 
?>

</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Volver al inicio</a>
            </div>
            <!-- /.navbar-header -->

            <?php require_once 'user.inc.php'; ?>
            <!-- /.navbar-top-links -->
            
            <?php require_once 'navbar.inc.php'; ?>           
            <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Crear menú</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Complete los campos <span class="text-danger"> OBLIGATORIOS *</span> para agregar un nuevo artículo al menú.
                        </div>
                        <div class="panel-body">
                            <form role="form" method='post' enctype="multipart/form-data" >
                                <div class="row">
                                    <div class="col-lg-6">
                                        <?php if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje']; ?>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="form-group">
                                            <label>Nombre del artículo:<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" name="Nombre" id="nombre" required
                                            value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Precio:<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="number" name="Precio_unitario" id="precio_unitario" step="0.01" min="0.00" required
                                            value="<?php echo !empty($_POST['Precio_unitario']) ? $_POST['Precio_unitario'] : ''; ?>">
                                        </div>
										<div class="form-group">
                                            <label>Descripción:</label>
                                            <input class="form-control" type="text" name="Descripcion" id="descripcion" 
                                            value="<?php echo !empty($_POST['Descripcion']) ? $_POST['Descripcion'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Imagen: </label>
                                            <small><span class="text-danger">(Solo: png, jpg, jpeg, bmp, webp)</span></small>
                                            <input type="file" name="Imagen_Menu" id='Archivo' accept="image/*">
                                        </div>
										<div class="form-group">
											<label>Tipo de artículo<span class="text-danger"> *</span></label>
											<select class="form-control" name="Tipo" id="tipo" required>
												<option value="">Seleccionar...</option>
												<?php 
												$selected='';
												for ($i=0 ; $i < $CantidadTipoArtiuculo ; $i++) {
													if (!empty($_POST['Tipo'])  && $_POST['Tipo'] ==  $ListadoTipoArticulo[$i]['ID_TIPO'] ) {
														$selected = 'selected';
													}else {
														$selected='';
													}
													?>
													<option value="<?php echo $ListadoTipoArticulo[$i]['ID_TIPO']; ?>" <?php echo $selected; ?>  >
														<?php echo $ListadoTipoArticulo[$i]['NOMBRE']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
                                        <button type="submit" class="btn btn-default" value="Guardar" name="BotonInsertar" >Guardar</button>
                                       
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>
                            </form>

                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->
    </div>
    <!-- /#wrapper -->
<?php  $_SESSION['Mensaje'] =""; ?>
<?php require_once 'footer.inc.php'; ?>