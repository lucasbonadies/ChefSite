<?php
session_start();
//Añado una verificacion extra para que Alguien que no tenga acceso pueda entrar a un Scrip no permitido 
if($_SESSION['id_nivel'] !=4 && $_SESSION['id_nivel'] !=5  ){
    header('Location: index.php');
    exit;
}
// Verifica si el usuario está autenticado y tiene los permisos necesarios
if (empty($_SESSION['id_usuario']) || empty($_SESSION['id_nivel'])) {
    // Si no está autenticado o no tiene permisos, redirige a cerrarsesion.php
    header('Location: cerrarsesion.php');
    exit;
}

// Incluye el archivo de conexión a la base de datos
require_once 'funciones/conexion.php';

// Verifica si se recibió el parámetro de éxito en la URL
$mensaje_pago = 'El pago se realizó correctamente';
if (isset($_GET['mensaje_pago']) && $_GET['mensaje_pago'] == 'exito') {
    $mensaje_pago = "El pago se realizó correctamente.";
}

// Verifica si se activó el botón "Cerrar Mesa"
$mensaje_cerrar_mesa = '';
$mostrar_volver_menu = false;

if (isset($_POST['cerrar_mesa'])) {
    // Simulación de cierre de mesa
    $mensaje_cerrar_mesa = "La mesa se cerró correctamente.";
    $mostrar_volver_menu = true;
    // Limpiamos el mensaje de pago
    $mensaje_pago = '';
}

// Incluye el archivo de encabezado
require_once 'header.inc.php';

// Incluye la barra de navegación
require_once 'navbar.inc.php';
?>

<!-- HTML de la página -->

<div id="wrapper">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Cerrar Mesa</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Mostrar el mensaje de resultado del pago -->
                <?php echo "<p>$mensaje_pago</p>"; ?>
                <!-- Mostrar el mensaje de resultado de cerrar mesa -->
                <?php echo "<p>$mensaje_cerrar_mesa</p>"; ?>

                <!-- Botón para cerrar la mesa -->
                <?php if (!$mostrar_volver_menu) { ?>
                    <form action="cerrar_mesa.php" method="post">
                        <input type="submit" name="cerrar_mesa" value="Cerrar Mesa">
                    </form>
                <?php } ?>

                <!-- Botón para volver al menú -->
                <?php if ($mostrar_volver_menu) { ?>
                    <a href="index.php" class="btn btn-primary">Volver al Menú</a>
                <?php } ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<?php
// Incluye el archivo de pie de página
require_once 'footer.inc.php';
?>

