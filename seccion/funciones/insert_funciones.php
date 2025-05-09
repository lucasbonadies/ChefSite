<?php
//Funciones para la insercion de platos, bebidas o postres Nivel 3 (CHEF) y Nivel 5 (Admin)

function InsertarArticulo($vConexion){

    //INSERT INTO `articulos`(`id_articulo`, `nombre`, `precio_unitario`, `descripcion`, `imagen`, `id_tipo`, `id_estado`)
    // Primero validamos si los datos están correctamente establecidos
    if (!isset($_POST['Nombre'], $_POST['Precio_unitario'], $_POST['Tipo'])) {
        die('Faltan datos en el formulario.');
    }

    // Validación y limpieza de datos
    $nombre = mysqli_real_escape_string($vConexion, $_POST['Nombre']);
    $precio = mysqli_real_escape_string($vConexion, $_POST['Precio_unitario']);
    $descripcion = isset($_POST['Descripcion']) ? mysqli_real_escape_string($vConexion, $_POST['Descripcion']) : NULL;
    $tipo = mysqli_real_escape_string($vConexion, $_POST['Tipo']);
    $ruta_imagen = $_FILES['Imagen_Menu']['name'];
    $valor_estado= 11;

    // Consulta preparada
    $SQL_Insert = $vConexion->prepare("INSERT INTO articulos (id_articulo, nombre, precio_unitario, descripcion, imagen, id_tipo, id_estado)
                                        VALUES (NULL, ?, ?, ?, ?, ?, ?)");
    $SQL_Insert->bind_param("sssssi", $nombre, $precio, $descripcion, $ruta_imagen, $tipo, $valor_estado);

    // Ejecutamos la consulta
    if (!$SQL_Insert->execute()) {
        echo "Error: " . $SQL_Insert->error;
        die('<h4>Error al intentar insertar el registro.</h4>');
    }

    $vConexion->close();
    
    return true;
}

function InsertarPedido($vConexion, $vIdPersona) {
	
    //INSERT INTO `pedidos`(`id_pedido`, `fecha_pedido`, `id_estado`, `id_persona`)
	$SQL_Insert="INSERT INTO pedidos (id_pedido, fecha_pedido, id_estado, id_persona)
    VALUES ( NULL , NOW() , 6, $vIdPersona)";

    // NOW() sirve para estampar la fecha en que se creo el registro actual

    if (!mysqli_query($vConexion, $SQL_Insert)) {
        //si surge un error, finalizo la ejecucion del script con un mensaje
        echo "Error: " .$SQL_Insert. "<br>" . mysqli_error($vConexion);
        die('<h4>Error al intentar insertar el registro.</h4>');
    }

    return true;
}

function InsertarDetalle($vConexion, $CantidadArticulos) {
	
	//obtengo el ultimo id insertado en la tabla pedidos
    $id_pedido = $vConexion->insert_id;
    $insercionesExitosas = 0;

    for ($i=0 ; $i < $CantidadArticulos ; $i++) {

        if (!empty($_POST["Articulo$i"]) && !empty($_POST["Cantidad$i"])) {
            //INSERT INTO `detalle_pedidos`(`id_detalle`, `id_pedido`, `id_articulo`, `cantidad`)

            $SQL_Insert="INSERT INTO detalle_pedidos (id_detalle, id_pedido, id_articulo, cantidad)
            VALUES ( NULL , $id_pedido , '{$_POST["Articulo$i"]}' , '{$_POST["Cantidad$i"]}' )";
    
            if (!mysqli_query($vConexion, $SQL_Insert)) {
                //muestro el error con un msj detallando la falla 
                echo "Error: " .$SQL_Insert. "<br>" . mysqli_error($vConexion);
                //finalizo la ejecucion del script con un mensaje
                die('<h4>Error al intentar insertar el registro.</h4>');
            }else{
                $insercionesExitosas++;
            }
        }   
    }

    if($insercionesExitosas>0){
        return true;
    }else{
        return false;
    }
    
}

function InsertarReserva($vConexion){
	
    // Tabla reservas
    $SQL_Insert="INSERT INTO reservas(id_reserva, fecha_hora_reserva, fecha_registro_reserva, cantidad_personas, id_estado, id_mesa)
    VALUES ( NULL ,'".$_POST['FechaHoraReserva']."', NOW() ,'".$_POST['CantPersonas']."', 3 , 1 )";

    // NOW() sirve para estampar la fecha en que se creo el registro actual

    if (!mysqli_query($vConexion, $SQL_Insert)) {
        //muestro el error con un msj detallando la falla 
        echo "Error: " .$SQL_Insert. "<br>" . mysqli_error($vConexion);
        //finalizo la ejecucion del script con un mensaje
        die('<h4>Error al intentar insertar el registro.</h4>');
    }

    return true;
	
}

function InsertarPersona($vConexion){

    //INSERT INTO `personas`(`id_persona`, `dni`, `nombre`, `apellido`, `fecha_nacimiento`, `nro_telefono`, `calle`, `sexo`, `imagen`, `fecha_registro`,
    // `id_pais`, `id_provincia`, `id_localidad`)
    $SQL_Insert="INSERT INTO personas (id_persona, dni, nombre, apellido, fecha_nacimiento, nro_telefono, calle, sexo, imagen, fecha_registro, id_pais, id_provincia, id_localidad)
    VALUES ( NULL , '".$_POST['Dni']."' , '".$_POST['Nombre']."' , '".$_POST['Apellido']."' , '".$_POST['FechaNacimiento']."', '".$_POST['Telefono']."' , 
    '".$_POST['Domicilio']."' , '".$_POST['Sexo']."' ,  NULL , NOW() ,'".$_POST['Pais']."', '".$_POST['Provincia']."', '".$_POST['Localidad']."')";

    if (!mysqli_query($vConexion, $SQL_Insert)) {
        //si surge un error, finalizo la ejecucion del script con un mensaje
        echo "Error: " .$SQL_Insert. "<br>" . mysqli_error($vConexion);
        die('<h4>Error al intentar insertar el registro.</h4>');
    }

    return true;
}

function InsertarUsuario($vConexion){
	
    //obtengo el ultimo id insertado en la tabla personas
    $id_persona = $vConexion->insert_id;
    
    //`usuarios`(`id_usuario`, `clave`, `email`, `id_nivel`, `id_persona`, `id_estado`)
    $SQL_Insert="INSERT INTO usuarios (id_usuario, clave, email, id_nivel, id_persona, id_estado)
    VALUES ( NULL , MD5('".$_POST['Clave']."') ,'".$_POST['Email']."', 1 , '".$id_persona."', 1 )";

    // NOW() sirve para estampar la fecha en que se creo el registro actual

    if (!mysqli_query($vConexion, $SQL_Insert)) {
        //muestro el error con un msj detallando la falla 
        echo "Error: " .$SQL_Insert. "<br>" . mysqli_error($vConexion);
        //finalizo la ejecucion del script con un mensaje
        die('<h4>Error al intentar insertar el registro.</h4>');
    }

    return true;
    
}

?>