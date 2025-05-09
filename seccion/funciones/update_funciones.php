<?php

function Cancelar_Pedido($vConexion, $vIdPedido) {
    // Consulta SQL
    $SQL = "UPDATE pedidos SET id_estado = 9 WHERE id_pedido = ?";

    // Preparar la declaración
    $stmt = $vConexion->prepare($SQL);
    if ($stmt === false) {
        // Error al preparar la consulta
        return false;
    }

    // Vincular los parámetros
    $stmt->bind_param("i", $vIdPedido);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        // Error al ejecutar la consulta
        return false;
    }

    // Cerrar la declaración
    $stmt->close();

    // Retornar true si la consulta fue exitosa
    return true;
}

function Eliminar_Articulo_Pedido($vConexion, $vIdPedido, $vIdArticulo) {

    // Validar que los IDs sean enteros
    if (!filter_var($vIdPedido, FILTER_VALIDATE_INT) || !filter_var($vIdArticulo, FILTER_VALIDATE_INT)) {
        return false;
    }

    // SQL: Eliminar el artículo del pedido
    $SQL = "DELETE FROM detalle_pedidos WHERE id_pedido = ? AND id_articulo = ?";

    // Preparar la consulta
    if ($stmt = $vConexion->prepare($SQL)) {
        
        // Enlazar los parámetros
        $stmt->bind_param("ii", $vIdPedido, $vIdArticulo);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $stmt->close(); // Cerrar el statement
            return true; // Eliminación exitosa
        } else {
            $stmt->close(); // Cerrar el statement en caso de error
            return false; // Error en la ejecución
        }
        
    } else {
        // Error en la preparación de la consulta
        return false;
    }
}

function Update_Pedido($vConexion, $vIdPedido, $articulos) {

    foreach ($articulos as $vIdArticulo => $cantidad) {

        //`detalle_pedidos`(`id_detalle`, `id_pedido`, `id_articulo`, `cantidad`)

        // Construimos la consulta 
        $SQL = "UPDATE detalle_pedidos SET cantidad = ? WHERE id_pedido = ? AND id_articulo = ?";

        // Preparamos la consulta
        $stmtUsuarios = $vConexion->prepare($SQL);

        if ($stmtUsuarios === false) {
            return false; // Error en la preparación de la consulta
        }

        // Asignar los parámetros
        $stmtUsuarios->bind_param("iii", $cantidad, $vIdPedido, $vIdArticulo);

        // Ejecutamos la consulta para cada artículo
        if (!$stmtUsuarios->execute()) {
            $stmtUsuarios->close();
            return false; // Error en la consulta
        }

        // Cerramos la declaración preparada después de ejecutarla
        $stmtUsuarios->close();
    }

    return true; // Retornamos true solo cuando todos los artículos se hayan actualizado correctamente
}

function Actualizar_Articulos($vConexion, $articulos) {

    $resultados = []; 
    
    foreach ($articulos as $index => $articulo) {
        $nuevoNombre = $articulo['nombre'];
        $nuevoPrecio = $articulo['precio'];
        $nuevoEstado = $articulo['estado'];
        $nuevoTipo = $articulo['tipo'];
        $idArticulo = $index;
        
        if (!empty($articulo['imagen']['name'])) {
            $SQL = "UPDATE articulos SET nombre = ?, precio_unitario = ?, id_estado = ?, imagen = ?, id_tipo = ? WHERE id_articulo = ?";
            $stmt = $vConexion->prepare($SQL);
            if (!$stmt->bind_param('sdisii', $nuevoNombre, $nuevoPrecio, $nuevoEstado, $articulo['imagen']['name'], $nuevoTipo, $idArticulo)) {
                $resultados[$idArticulo] = 'Error en bind_param: ' . $stmt->error;
                continue;
            }
        } else {
            $SQL = "UPDATE articulos SET nombre = ?, precio_unitario = ?, id_estado = ?, id_tipo = ? WHERE id_articulo = ?";
            $stmt = $vConexion->prepare($SQL);
            if (!$stmt->bind_param('sdiii', $nuevoNombre, $nuevoPrecio, $nuevoEstado, $nuevoTipo, $idArticulo)) {
                $resultados[$idArticulo] = 'Error en bind_param: ' . $stmt->error;
                continue;
            }
        }

        if (!$stmt->execute()) {
            $resultados[$idArticulo] = 'Error en execute: ' . $stmt->error;
        } else {
            $resultados[$idArticulo] = 'Actualización exitosa';
        }
        $stmt->close();
    }

    if(empty($resultados)){
        return true;
    }else{
        return $resultados; // Devuelvo los resultados con los errores o éxitos
    }
}


?>