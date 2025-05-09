<?php 
session_start();

require_once 'funciones/conexion.php';
$MiConexion=ConexionBD(); 

require_once 'funciones/select_funciones.php';
$ListadoPaises = Listar_Paises($MiConexion);
$CantidadPaises= count($ListadoPaises);
$ListadoProvincias = Listar_Provincias($MiConexion);
$CantidadProvincias= count($ListadoProvincias);
$ListadoLocalidades = Listar_Localidades($MiConexion);
$CantidadLocalidades= count($ListadoLocalidades);

$_SESSION['Mensaje']='';
$_SESSION['Estilo']='warning';

if (!empty($_POST['BotonRegistrar'])) {
    //estoy en condiciones de poder validar los datos
	require_once 'funciones/validacion_de_datos.php';
    if (Validar_Registro($_POST['Clave']) && Validar_Terminos_Condiciones() && Validar_Clave($_POST['Clave']) && Validar_Email()) {
		if(Existe_Email($MiConexion)===false){
			require_once 'funciones/insert_funciones.php';
			if (InsertarPersona($MiConexion) != false && InsertarUsuario($MiConexion) != false) {
				$_SESSION['Mensaje'] = 'Se ha registrado correctamente. Ingrese su Usuario y Contraseña';
				$_SESSION['Estilo'] = 'success';
				$_POST = array();
				header('Location: login.php');
				exit;
			}else{
				$_SESSION['Mensaje'] = 'No se pudo realziar el registro verifique los datos y vuelva a intentar.';
			}
		}
    }
}

require_once 'header.inc.php'; ?>

</head>

<body>
	<div id="wrapper">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header" style="text-align: center;">Formulario de Registro</h1>
				</div>
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
											<label>Nombre:<span class="text-danger"> *</span></label>
											<input class="form-control" type="text" name="Nombre" id="nombre" required
											value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>">
										</div>
										<div class="form-group">
											<label>Apellido:<span class="text-danger"> *</span></label>
											<input class="form-control" type="text" name="Apellido" id="apellido" required
											value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>">
										</div>
										<div class="form-group">
											<label>Dni:<span class="text-danger"> *</span></label>
											<input class="form-control" type="number" name="Dni" id="dni" required
											value="<?php echo !empty($_POST['Dni']) ? $_POST['Dni'] : ''; ?>">
										</div>
										<div class="form-group">
											<label>Fecha de nacimiento:<span class="text-danger"> *</span></label>
											<input class="form-control" type="date" name="FechaNacimiento" id="fechaNacimiento" min="1924-01-01" max="2006-01-01" required
											value="<?php echo !empty($_POST['FechaNacimiento']) ? $_POST['FechaNacimiento'] : ''; ?>"> <!-- FALTA REALIZAR DESDE PHP PARA QUE SE ACTUALICE SOLO MAXIMO Y MINIMO PARA LA FECHA DE NACIMIENTO -->
										</div>
										<div class="form-group">
											<label>Teléfono:<span class="text-danger"> *</span></label>
											<small>Formato: 2235959595</small>
											<input class="form-control" type="tel" name="Telefono" id="telefono" required
											value="<?php echo !empty($_POST['Telefono']) ? $_POST['Telefono'] : ''; ?>">  <!-- probar con required para la comprobacion de valores y  pattern="[0-9]{3}-[0-9]{1}-[0-9]{6}" para la forma del numero-->
										</div>
										<div class="form-group">
											<label>Email:<span class="text-danger"> *</span></label>
											<input class="form-control" type="email" name="Email" id="email" required
											value="<?php echo !empty($_POST['Email']) ? $_POST['Email'] : ''; ?>">
										</div>

										<div class="form-group">
											<label>Clave:<span class="text-danger"> *</span></label>
											<input class="form-control" type="password" name="Clave" id="clave" value="" required>
										</div>

										<div class="form-group">
											<label>Reingresa la clave:<span class="text-danger"> *</span></label>
											<input ID="txtPassword" class="form-control" type="password" name="ReClave" id="reclave" value="" required>
										</div>

										<div class="form-group">
											<label>País<span class="text-danger">*</span></label>
											<select class="form-control" name="Pais" id="pais" required>
												<option value="">Selecciona...</option>
												<?php 
												$selected='';
												for ($i=0 ; $i < $CantidadPaises ; $i++) {
													if (!empty($_POST['Pais'])  && $_POST['Pais'] ==  $ListadoPaises[$i]['ID_PAIS'] ) {
														$selected = 'selected';
													}else {
														$selected='';
													}
													?>
													<option value="<?php echo $ListadoPaises[$i]['ID_PAIS']; ?>" <?php echo $selected; ?>  >
														<?php echo $ListadoPaises[$i]['NOMBRE']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label>Provincia<span class="text-danger"> *</span></label>
											<select class="form-control" name="Provincia" id="provincia" required>
												<option value="">Selecciona...</option>
												<?php 
												$selected='';
												for ($i=0 ; $i < $CantidadProvincias ; $i++) {
													if (!empty($_POST['Provincia'])  && $_POST['Provincia'] ==  $ListadoProvincias[$i]['ID_PROVINCIA'] ) {
														$selected = 'selected';
													}else {
														$selected='';
													}
													?>
													<option value="<?php echo $ListadoProvincias[$i]['ID_PROVINCIA']; ?>" <?php echo $selected; ?>  >
														<?php echo $ListadoProvincias[$i]['NOMBRE']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label>Localidad<span class="text-danger"> *</span></label>
											<select class="form-control" name="Localidad" id="localidad" required>
												<option value="">Selecciona...</option>
												<?php 
												$selected='';
												for ($i=0 ; $i < $CantidadLocalidades ; $i++) {
													if (!empty($_POST['Localidad'])  && $_POST['Localidad'] ==  $ListadoLocalidades[$i]['ID_LOCALIDAD'] ) {
														$selected = 'selected';
													}else {
														$selected='';
													}
													?>
													<option value="<?php echo $ListadoLocalidades[$i]['ID_LOCALIDAD']; ?>" <?php echo $selected; ?>  >
														<?php echo $ListadoLocalidades[$i]['NOMBRE']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label>Domicilio:<span class="text-danger"> *</span></label>
											<input class="form-control" type="text" name="Domicilio" id="domicilio" required
											value="<?php echo !empty($_POST['Domicilio']) ? $_POST['Domicilio'] : ''; ?>">
										</div>

										<div class="form-group">
											<label>Sexo:<span class="text-danger"> *</span></label>
											<br />
											<label class="radio-inline">
												<input type="radio" name="Sexo" id="SexoF" 
												value="F" 
												<?php echo (!empty($_POST['Sexo']) && $_POST['Sexo'] == 'F') ? 'checked':''; ?>  >Femenino
											</label>
											<label class="radio-inline">
												<input type="radio" name="Sexo" id="SexoM" 
												value="M" 
												<?php echo (!empty($_POST['Sexo']) && $_POST['Sexo'] == 'M') ? 'checked':''; ?> >Masculino
											</label>
											<label class="radio-inline">
												<input type="radio" name="Sexo" id="SexoO" 
												value="O"
												<?php echo (!empty($_POST['Sexo']) && $_POST['Sexo'] == 'O') ? 'checked':''; ?> >Otro
											</label>
										</div>
										<div class="form-group">
											<label>Condiciones del sitio:<span class="text-danger"> *</span></label>
											<br />
											<div class="checkbox">
												<label>
													<input type="checkbox" name="Condiciones"
													value="SI"
													<?php echo (!empty($_POST['Condiciones']) && $_POST['Condiciones'] == 'SI') ? 'checked':''; ?>
													>Acepto los términos y condiciones.
												</label>
											</div>
										</div>
										<div class="form-group">
										<button type="submit" class="btn btn-default" value="Registrar" name="BotonRegistrar">Registrarme</button>
										<button class="btn btn-outline btn-default" onclick="javascript:history.back()">Volver</button>
										</div>
									</div>
									<!-- /.row col-lg-6(nested) -->
								</div>
								<!-- /.row -->
							</form>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel panel-default -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.col-lg-12 -->							
	</div>
	<!-- /#wrapper -->

	<?php //require_once 'footer.inc.php'; ?>