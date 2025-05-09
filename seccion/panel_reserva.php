<?php
session_start(); 

//Añado una verificacion extra para que Alguien que no tenga acceso pueda entrar a un Scrip no permitido 
if($_SESSION['id_nivel'] !=5 && $_SESSION['id_nivel'] !=2  && $_SESSION['id_nivel'] !=1){
    header('Location: index.php');
    exit;
}

if(empty($_SESSION['id_usuario']) || empty($_SESSION['id_nivel'])){
    header('Location: cerrarsesion.php');
    exit;
 }
 
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD(); 

if (!empty($_POST['BotonReservar'])) {
    require_once 'funciones/validacion_de_datos.php';
    //estoy en condiciones de poder validar los datos
    if(Validar_Reserva()===true){
        require_once 'funciones/insert_funciones.php';
        if (InsertarReserva($MiConexion)===true) {     
            $_SESSION['Mensaje']= 'Reserva registrada exitosa.';
            $_POST = array(); // VER PORQUE PUSE ESTO ? :/ :S :( .......
            $_SESSION['Estilo']= 'success'; 
            //header('Location: panel_reserva.php');
            //exit;
        }
    } 
}

// Obtener la fecha actual en el formato YYYY-MM-DD
$currentDate = date('Y-m-d');
// Calcular la fecha dentro de 30 días a partir de hoy
$maxDate = date('Y-m-d', strtotime('+30 days'));

// Generar los valores min y max para el atributo datetime-local
$minDateTime = $currentDate . 'T08:00';
$maxDateTime = $maxDate . 'T22:00';

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
                    <h1 class="page-header">Reserva</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="alert alert-info col-lg-12">
                    Realiza una reserva completando los campos <span class="text-danger">OBLIGATORIOS *</span>.			
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			 <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <form role="form" method='post'>

                                <div class="row">
                                    <div class="col-lg-4" style="text-align: center;">
                                        <img src="dist/img/LoginChef.png"/>
                                        <br />
                                    </div>
                                    <div class="col-lg-6">
                                        
                                        <?php if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje']; ?>
                                        </div>
                                        <?php } ?>

										<div class="form-group">
											<label>Fecha y hora:<span class="text-danger"> *</span></label>
											<input class="form-control" type="datetime-local" name="FechaHoraReserva" id="fechaHoraReserva"
											value="<?php echo !empty($_POST['FechaHoraReserva']) ? $_POST['FechaHoraReserva'] : '2024-01-23T08:00'; ?>" 
                                            min="<?php echo $minDateTime; ?>" 
                                            max="<?php echo $maxDateTime; ?>">										
										</div>
                                        <div class="form-group">
                                            <label>Teléfono de contacto:</label>
                                            <small><!--Formato: 2235959595--></small>
                                            <input class="form-control" type="tel" name="Telefono" id="telefono" readonly  
                                            value="<?php echo !empty($_POST['Telefono']) ? $_POST['Telefono'] : $_SESSION['nro_telefono']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input class="form-control" type="email" name="Email" id="email" readonly
                                            value="<?php echo !empty($_POST['Email']) ? $_POST['Email'] : $_SESSION['email']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Cantidad de personas:<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="number" name="CantPersonas" id="cantPersonas" 
                                            value="<?php echo !empty($_POST['CantPersonas']) ? $_POST['CantPersonas'] : 1; ?>">
                                        </div>
                 
                                        <button type="submit" class="btn btn-default" value="Reservar" name="BotonReservar" >Reservar</button>
                                       
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
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
		

    </div>
    <!-- /#wrapper -->

<?php 
$_SESSION['Mensaje']='';
require_once 'footer.inc.php'; 
?>