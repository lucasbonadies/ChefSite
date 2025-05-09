<?php 
session_start();

if(empty($_SESSION['id_usuario']) || empty($_SESSION['id_nivel'])){
    header('Location: cerrarsesion.php');
    exit;
}

require_once 'funciones/conexion.php';
$MiConexion=ConexionBD(); 

//$_SESSION['Mensaje']='';
//$_SESSION['Estilo']='warning';

require_once 'funciones/select_funciones.php';
$ListadoNiveles= Listar_Niveles($MiConexion);

require_once 'funciones/select_usuarios.php';
$DatosUsuario=array();
$ID_USUARIO_GET= $_SESSION['id_nivel']==5 ? htmlspecialchars($_GET['ID_USUARIO']) : '';
$ID_PERSONA_GET= $_SESSION['id_nivel']==5 ? htmlspecialchars($_GET['ID_PERSONA']) : '';

if (!empty($ID_USUARIO_GET) || !empty($ID_PERSONA_GET)){
	//se estan trayendo los datos de la base
	$DatosUsuario = EncontrarUsuario($ID_USUARIO_GET, $MiConexion);
	if(empty($DatosUsuario)){
		//si llega vacio $DatosUsuario: 
		$_SESSION['Mensaje']='No se encontraró ningún registro del usuario.';
	}
}else {
	//si llega vacia la variable de $_GET: 
	$_SESSION['Mensaje']='Sin datos para mostrar...';	
}

if (!empty($_POST['BotonCrearEmpleado'])) {

	require_once 'funciones/validacion_de_datos.php';
	$pass= false; // variable para confirmar si se puedo subir los datos o no antes de entrar al siguiente if...   

	if(Existe_Clave($_POST['Clave'], $_SESSION['email'], $MiConexion)===true){
		if (Validar_Registro_Empleado()=== true) {  //salio bien la validacion
			if(Crear_Empleado($MiConexion, $ID_USUARIO_GET)===true){
				$_SESSION['Mensaje']='Empleado creado con éxito.';
				$_SESSION['Estilo']='success';
			}else{
				$_SESSION['Mensaje']='No se pudo crear el empleado.';
				$_SESSION['Estilo']='warning';
			}

		}
			
	}	
				 
}

require_once 'header.inc.php'; ?>

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
                <a class="navbar-brand" href="#">
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
                    <h1 class="page-header">Crear empleado</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-<?php
					//si no tengo datos de usuario
						if (empty($DatosUsuario))
							echo 'default';
						else {
							//el array tiene valores
							echo $DatosUsuario['ID_ESTADO']==1 ? 'success' : 'danger'; 
						} ?>  ">
						<?php if (!empty($_SESSION['Mensaje'])) { ?>
						<div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
						<?php echo $_SESSION['Mensaje']; ?>
						</div>
						<?php } ?>
						<div class="panel-heading ">
                            Usuario <?php echo $DatosUsuario['ID_ESTADO']==1 ? 'activo' : 'inactivo' ; ?>
                       	</div>
						<!-- /.panel-heading -->
							<div class="panel-body ">
								<div class="row">
									<form method='post' enctype="multipart/form-data" >
										<div class="col-lg-5">
											<div class="form-group">
												<label>Id Persona:</label>
												<input class="form-control" type="text" name="Id_persona" id="id_persona" readonly
												value="<?php echo $DatosUsuario['ID_PERSONA'];?>">
											</div>
											<div class="form-group">
												<label>Nombre:</label>
												<input class="form-control" type="text" name="Nombre" id="nombre" readonly
												value="<?php echo !empty($_POST['Nombre'])? $_POST['Nombre'] : $DatosUsuario['NOMBRE']; ?>">
											</div>
											<div class="form-group">
												<label>Apellido:</label>
												<input class="form-control" type="text" name="Apellido" id="apellido" readonly
												value="<?php echo !empty($_POST['Apellido'])? $_POST['Apellido'] : $DatosUsuario['APELLIDO']; ?>">
											</div>
											<div class="form-group">
												<label>Dni:</label>
												<input class="form-control" type="number" name="Dni" id="dni" readonly
												value="<?php echo $DatosUsuario['DNI'];?>">
											</div>
											<div class="form-group">
												<label>Nivel:<span class="text-danger"> *</span></label>
												<select class="form-control" name="Id_nivel" id="id_nivel" required >
													<option value="<?php echo empty($_POST['Id_nivel'])? $DatosUsuario['ID_NIVEL'] : ""; ?>">
														<?php echo empty($_POST['Id_nivel'])? $DatosUsuario['NOMBRE_NIVEL']: ""; ?>
													</option>
													<?php 
													$selected= '';
													foreach ($ListadoNiveles as $nivel) {
														$selected = !empty($_POST['Id_nivel']) && $_POST['Id_nivel'] == $nivel['ID_NIVEL'] ? 'selected' : '';
													?>
														<option value="<?php echo $nivel['ID_NIVEL']; ?>" <?php echo $selected; ?>>
															<?php echo $nivel['NOMBRE_NIVEL']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
                                            <div class="form-group">
                                                <label>Sueldo:<span class="text-danger"> *</span></label>
                                                <input class="form-control" type="number" name="Sueldo" id="sueldo" step="0.01" min="0.00" required
                                                value="<?php echo !empty($_POST['Sueldo']) ? $_POST['Sueldo'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
												<label>Fecha de Alta:</label>
												<input class="form-control" type="date" name="FechaAlta" id="fechaAlta" min="1924-01-01" max="2006-01-01" readonly
												value="<?php echo !empty($DatosUsuario['FechaAlta'])?  $DatosUsuario['FechaAlta'] : ''; ?>"> 
											</div>
                                            <div class="form-group">
												<label>Contraseña Actual:</label>
                                                <small><span class="text-danger">Para confirmar los cambios, ingrese la contraseña</span></small>
												<input class="form-control" type="password" name="Clave" id="clave" value="" required>
											</div>
											<button type="submit" class="btn btn-default" value="Modificar" name="BotonCrearEmpleado" > GUARDAR </button>     
										</div> 
										<!-- /.col-lg-5 -->
										<div class="col-lg-3">
											<div class="form-group">
												<img alt="Mi Imagen de Perfil" class="img-responsive" 
												src='dist/img/Imagen_Perfil/<?php echo $DatosUsuario['IMG']; ?>' />
											</div>
										</div>
									</form>
								</div>
								<!-- /.row -->
							</div>
							<!-- /.panel-body -->
						<div class="alert alert-info center-block right">
							<i class="fa fa-arrow-left"></i>
							<a href="mis_datos.php" class="alert-link">Volver</a>.
						</div>
					</div>	 
                    <!-- /.panel panel -->
                </div>
                <!-- /.col-lg-12 -->
			</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
<?php $_SESSION['Mensaje']=''; ?>
<?php require_once 'footer.inc.php'; ?>