<?php
/**
 * PUNTO DE ENTRADA DE LA APLICACIÓN (Front Controller)
 *
 * Todas las peticiones llegan aquí y se envían al controlador
 * según el parámetro "accion" de la URL.
 *
 * Ejemplos:
 *   index.php                      -> inicio
 *   index.php?accion=listar        -> lista de clientes
 *   index.php?accion=nuevo         -> formulario de nuevo cliente
 *   index.php?accion=editar&id=3   -> formulario de edición
 */

session_start();

require_once __DIR__ . '/controllers/ClientesController.php';

/** Escapa texto antes de mostrarlo en HTML. */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$acciones = ['inicio', 'listar', 'nuevo', 'guardar', 'editar', 'actualizar', 'eliminar'];
$accion   = $_GET['accion'] ?? 'inicio';

if (!in_array($accion, $acciones, true)) {
    $accion = 'inicio';
}

$controlador = new ClientesController();

try {
    $controlador->$accion();
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);

    $titulo  = 'Error';
    $mensaje = 'No se pudo conectar con la base de datos. Revise la configuración en config/database.php.';

    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/error.php';
    require __DIR__ . '/views/layout/footer.php';
}
