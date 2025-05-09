<?php
session_start();

if (empty($_SESSION['id_usuario']) || empty($_SESSION['id_nivel'])) {
    header('Location: cerrarsesion.php');
    exit;
}

require_once 'funciones/conexion.php';
require_once 'funciones/select_funciones.php';
require_once 'funciones/validacion_de_datos.php';
require_once 'funciones/update_funciones.php'; // Incluimos el archivo con la función de actualización
require_once 'funciones/subir_archivo.php';

$MiConexion = ConexionBD();
$ListadoArticulos = Listar_Articulos($MiConexion);
$CantidadArticulos = count($ListadoArticulos);
$ListadoCategorias = Listar_Tipo_Articulos($MiConexion); 

$resultadosActualizacion = []; // Variable para almacenar los resultados de la actualización
$Categoria_Articulos="";

if (!empty($_POST['BotonActualizar'])) {

    $detallesArticulos = [];
    foreach ($ListadoArticulos as $index => $articulo) {

        $detallesArticulos[$articulo['ID_ARTICULO']] = 
        [
            'nombre' => $_POST['nombre' . $index],
            'precio' => $_POST['precio' . $index],
            'estado' => isset($_POST['estado' . $index]) ? 11 : 12,
            'imagen' => $_FILES['imagen' . $index],
            'tipo' => $_POST['tipo' . $index]
        ];

    }

    if (Validar_Update_Articulo($detallesArticulos)===true) {
        if (Actualizar_Articulos($MiConexion, $detallesArticulos) != false) {
            $_SESSION['Mensaje'] = 'Modificación realizada de forma correcta.';
            $_POST = array(); 
            $_SESSION['Estilo']= 'success';
            // Redirigir para recargar la página y ver los valores actualizados
            header("Location: panel_menu.php");
            exit();
        }
    }
}

require_once 'header.inc.php'; 
?>

</head>

<body>

<div id="wrapper">
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
        <?php require_once 'user.inc.php'; ?>
        <?php require_once 'navbar.inc.php'; ?>           
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Modificar menú</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <form id="menuForm" method="POST" action="" enctype="multipart/form-data">
                    <nav>
                        <ol class="breadcrumb panel-heading">
                            <?php 
                            foreach ($ListadoCategorias as $categoria){ ?>
                                <li class="breadcrumb-item">
                                    <button type="button" class="btn btn-info" value="<?= $categoria['NOMBRE'] ?>" onclick="mostrarCategoria('<?= $categoria['ID_TIPO'] ?>')">
                                        <?= $categoria['NOMBRE'] ?>
                                    </button>
                                </li>
                            <?php } ?>
                            <li class="breadcrumb-item">
                                <button type="submit" class="btn btn-success" name="BotonActualizar" value="Actualizar" >
                                    Guardar
                                </button>
                            </li>
                        </ol>
                    </nav>
                    <br />
                    <?php if (!empty($_SESSION['Mensaje'])){ ?>
                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                            <?php echo $_SESSION['Mensaje']; ?>
                        </div>
                    <?php } ?>
                        <?php for ($i = 0; $i < $CantidadArticulos; $i++) { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12" >
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row" style="display: flex; align-items: center;">
                                            <div class="col-xs-3" style="width: 215px;">
                                                <img alt="Image placeholder" class="img-responsive" 
                                                src="dist/img/Imagen_Menu/<?php echo $ListadoArticulos[$i]['IMAGEN']; ?>" />
                                                <input type="file" name="imagen<?php echo $i; ?>" style="width: 140px;" accept="image/*" />
                                            </div>
                                            <div class="col-xs-9 text-right" style="padding-right: 0; display: flex; flex-direction: column;">
                                                <div class="huge" style="flex: 1; width: 100%;"> 
                                                    <input type="text" name="nombre<?php echo $i; ?>" 
                                                        value="<?php echo $ListadoArticulos[$i]['NOMBRE']; ?>" requiered
                                                        style="text-align: right; color: black; width: 100%;" 
                                                        onkeydown="return event.key != 'Enter';" />
                                                </div>
                                                <input type="text" name="precio<?php echo $i; ?>" 
                                                    value="<?php echo $ListadoArticulos[$i]['PRECIO_UNITARIO']; ?>"  requiered
                                                    style="width: 25%; text-align: right; color: black; padding: 3px; align-self: flex-end;" 
                                                    onkeydown="return event.key != 'Enter';" />
                                            </div>
                                        </div>
                                    </div>
                                    <?php foreach ($ListadoCategorias as $categoria){ 
                                            if($ListadoArticulos[$i]['ID_TIPO']==$categoria['ID_TIPO']){
                                                $Categoria_Articulos=$categoria['NOMBRE'];
                                            }
                                        } ?>
                                    <div class="panel-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                        <div class="form-group">
                                            <label>Tipo de artículo:</label>
                                            <select class="form-control" name="tipo<?php echo $i; ?>" id="tipo_id" required >
                                                <option value="<?php echo empty($_POST["tipo$i"])? $ListadoArticulos[$i]['ID_TIPO'] : ""; ?>">
                                                    <?php echo empty($_POST["tipo$i"])? $Categoria_Articulos : "" ; ?>
                                                </option>
                                                <?php 
                                                $selected= '';
                                                foreach ($ListadoCategorias as $categoria) {
                                                    $selected = !empty($_POST["tipo$i"]) && $_POST["tipo$i"] == $categoria['ID_TIPO'] ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $categoria['ID_TIPO']; ?>" <?php echo $selected; ?>>
                                                        <?php echo $categoria['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div style="display: inline-flex; align-items: center; margin-left: 100px;">
                                            <input type="checkbox" name="estado<?php echo $i; ?>" style="margin-left: 10px;"
                                            <?php echo ($ListadoArticulos[$i]['ID_ESTADO'] == 11) ? 'checked' : ''; ?> />
                                            <span style="margin-left: 5px;">Artículo disponible</span>
                                        </div>
                                    </div>

                                    
                                    <!--<button type="submit" name="BotonEliminar" value="<?php // echo $ListadoArticulos[$i]['ID_ARTICULO']; ?>" class="btn btn-danger btn-circle" 
                                        onclick="return confirm('¿Estás seguro que querés eliminar este artículo? Esta opción no se podrá deshacer.');" 
                                        style="margin-left: auto;">
                                        <i class="fa fa-trash"></i>
                                    </button>-->
                                    <input type="hidden" name="idArticulo" value="<?php echo $ListadoArticulos[$i]['ID_ARTICULO']; ?>" />
                                    <div class="clearfix"></div>
                                </div>
                            </div>                     
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>   
    </div>
</div>

<button id="back-to-top"><i class="fa fa-long-arrow-up"></i></button>

<script src="js/mostrar_ocultar.js"> </script>

<?php
$_SESSION['Mensaje']= '';
$_SESSION['Estilo']='';
?>

<?php require_once 'footer.inc.php'; ?>
