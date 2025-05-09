<?php 
session_start();

if(empty($_SESSION['id_usuario']) or empty($_SESSION['id_nivel'])){
    header('Location: cerrarsesion.php');
    exit;
}

require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();

require_once 'funciones/select_usuarios.php';
$ListadoUsuarios= Listar_Usuarios($MiConexion);
$CantidadUsuarios = count($ListadoUsuarios);

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
                <a class="navbar-brand" href="index.php">
                    Usuario:  <?php echo $_SESSION['nombre'].' '.$_SESSION['apellido']; ?>
                </a>
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
                    <h1 class="page-header"> Mis Datos </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <?php if (!empty($_SESSION['Mensaje'])) { ?> 
                        <div class="alert alert-<?php echo $_SESSION['Estilo'] ?> alert-dismissable">
                            <?php echo $_SESSION['Mensaje']; ?> 
                        </div>
                    <?php }  ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Mis datos 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id Usuario</th>
                                            <th>Apellido</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Fecha de Nacimiento</th> 
                                                <?php if($_SESSION['id_nivel']!=1){  ?>
                                            <th>Puesto de Trabajo</th>
                                                <?php } ?>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td><?php echo $_SESSION['id_usuario']; ?></td>
                                            <td><?php echo $_SESSION['apellido']; ?></td>
                                            <td><?php echo $_SESSION['nombre']; ?></td>
                                            <td><?php echo $_SESSION['email']; ?></td>
                                            <td><?php echo date("d/m/Y", strtotime($_SESSION['fecha_nacimiento'])); ?></td> 
                                                <?php if($_SESSION['id_nivel']!=1){  ?>
                                            <td><?php echo $_SESSION['nombre_nivel']; ?></td>        
                                                <?php } ?>
                                            <td>
                                                <a class="btn btn-success btn-circle btn-info " 
                                                href="modificar_datos_usuario.php?ID_USUARIO=<?php echo $_SESSION['id_usuario']; ?>" 
                                                role="button" title="Modificar">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <!-- anular acceso futura funcion de pago
                                                <a class="btn btn-success btn-circle btn-warning" href="#" role="button" title="Anular acceso">
                                                    <i class="fa fa-lock"></i>
                                                </a>  -->         
                                                <?php if($_SESSION['id_nivel'] ==1){ ?>
                                                <a class="btn btn-success btn-circle btn-danger " href="modificar_acceso_usuario.php?ID_USUARIO=<?php echo $_SESSION['id_usuario'];?>&ID_ESTADO=<?php echo $_SESSION['id_estado'];?>"
                                                onclick="if(confirm('Esta seguro que desea dar de BAJA la cuenta ?')) {return true;} else {return false}"
                                                role="button" title="Dar de Baja">
                                                <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                                <?php } ?>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body --> 
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            </br>
            <!-- /.row -->
            <?php if($_SESSION['id_nivel'] ==5){ ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Usuarios 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id Usuario</th>
                                            <th>Apellido</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Fecha de Nacimiento</th> 
                                            <th>Puesto de Trabajo</th>
                                            <th>Activo / Inactivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i=0; $i<$CantidadUsuarios; $i++){ ?>
                                        <tr>
                                            <td><?php echo $ListadoUsuarios[$i]['ID_USUARIO']; ?></td>
                                            <td><?php echo $ListadoUsuarios[$i]['APELLIDO']; ?></td>
                                            <td><?php echo $ListadoUsuarios[$i]['NOMBRE']; ?></td>
                                            <td><?php echo $ListadoUsuarios[$i]['EMAIL']; ?></td>
                                            <td><?php echo date("d/m/Y", strtotime($ListadoUsuarios[$i]['FECHA_NACIMIENTO'])); ?></td> 
                                            <td><?php echo $ListadoUsuarios[$i]['NOMBRE_NIVEL']; ?></td>
                                            <td><?php echo $ListadoUsuarios[$i]['ID_ESTADO']==1 ? 'Activo' : 'Inactivo' ; ?></td>
                                            <td>
                                                <a class="btn btn-success btn-circle btn-info " 
                                                href="modificar_datos_usuario.php?ID_USUARIO=<?php echo $ListadoUsuarios[$i]['ID_USUARIO']; ?>" 
                                                role="button" title="Modificar">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <a class="btn btn-success btn-circle btn-warning " 
                                                href="crear_empleado.php?ID_PERSONA=<?php echo $ListadoUsuarios[$i]['ID_PERSONA'];?>&ID_USUARIO=<?php echo $ListadoUsuarios[$i]['ID_USUARIO'];?>" 
                                                role="button" title="Crear Empleado">
                                                    <i class="fa fa-user"></i>
                                                </a>
                                                <!-- anular acceso futura funcion de pago
                                                <a class="btn btn-success btn-circle btn-warning" href="#" role="button" title="Anular acceso">
                                                    <i class="fa fa-lock"></i>
                                                </a>  -->         
                                                <a href="modificar_acceso_usuario.php?ID_USUARIO=<?php echo $ListadoUsuarios[$i]['ID_USUARIO'];?>&ID_ESTADO=<?php echo $ListadoUsuarios[$i]['ID_ESTADO'];?>"
                                                role="button" 
                                                <?php if($ListadoUsuarios[$i]['ID_ESTADO']==1){ ?>
                                                class="btn btn-success btn-circle btn-danger"
                                                onclick="if(confirm('Esta seguro que desea dar de BAJA la cuenta ?')) {return true;} else {return false}"      
                                                title="Dar de Baja">
                                                <i class="fa fa-times"></i>
                                                </a>
                                                <?php } if($ListadoUsuarios[$i]['ID_ESTADO']==2){ ?>
                                                class="btn btn-success btn-circle btn-warning" 
                                                onclick="if(confirm('Esta seguro que desea dar de ALTA la cuenta ?')) {return true;} else {return false}"
                                                title="Dar de Alta">
                                                <i class="fa fa-check"></i>
                                                <?php } ?> 
                                            </td>
                                        </tr>
                                        <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body --> 
                    </div>
                    <!-- /.panel -->
                    <a  href="index.php" class="btn btn-success btn-info " title="Volver"> 
                         Volver 
                    </a>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <?php } ?>
            </br>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

<?php  $_SESSION['Mensaje'] =""; ?>
<?php  $_SESSION['Estilo'] =""; ?>
<?php require_once 'footer.inc.php'; ?>