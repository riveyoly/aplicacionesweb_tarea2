<?php
/**
 * CONTROLADOR: ClientesController
 *
 * Recibe las acciones del usuario, usa el modelo Cliente
 * y decide qué vista mostrar.
 */

require_once __DIR__ . '/../models/Cliente.php';

class ClientesController
{
    /** Página de bienvenida. */
    public function inicio(): void
    {
        $this->vista('inicio', ['titulo' => 'Inicio']);
    }

    /** READ: lista de clientes. */
    public function listar(): void
    {
        $clientes = Cliente::listar();

        $this->vista('clientes/index', [
            'titulo'   => 'Clientes',
            'clientes' => $clientes,
        ]);
    }

    /** Muestra el formulario vacío para un nuevo cliente. */
    public function nuevo(): void
    {
        $this->formulario(new Cliente(), [], 'guardar', 'Nuevo cliente');
    }

    /** CREATE: guarda el nuevo cliente. */
    public function guardar(): void
    {
        $this->soloPost();

        $cliente = new Cliente($_POST);
        $errores = $cliente->validar();

        if ($errores) {
            $this->formulario($cliente, $errores, 'guardar', 'Nuevo cliente');
            return;
        }

        $cliente->crear();
        $this->redirigir('Cliente registrado correctamente.');
    }

    /** Muestra el formulario con los datos del cliente a editar. */
    public function editar(): void
    {
        $cliente = $this->clienteSolicitado();
        $this->formulario($cliente, [], 'actualizar', 'Editar cliente');
    }

    /** UPDATE: guarda los cambios del cliente. */
    public function actualizar(): void
    {
        $this->soloPost();

        $cliente = $this->clienteSolicitado();
        $cliente->llenar($_POST + ['id' => $cliente->getId()]);

        $errores = $cliente->validar();

        if ($errores) {
            $this->formulario($cliente, $errores, 'actualizar', 'Editar cliente');
            return;
        }

        $cliente->actualizar();
        $this->redirigir('Cliente actualizado correctamente.');
    }

    /** DELETE: elimina el cliente. */
    public function eliminar(): void
    {
        $this->soloPost();

        $cliente = $this->clienteSolicitado();
        $cliente->eliminar();

        $this->redirigir('Cliente eliminado correctamente.');
    }

    // ---------------- Métodos de apoyo ----------------

    private function clienteSolicitado(): Cliente
    {
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = Cliente::buscarPorId($id);

        if ($cliente === null) {
            http_response_code(404);
            $this->vista('error', ['titulo' => 'No encontrado', 'mensaje' => 'El cliente solicitado no existe.']);
            exit;
        }

        return $cliente;
    }

    private function formulario(Cliente $cliente, array $errores, string $accion, string $titulo): void
    {
        $this->vista('clientes/form', compact('cliente', 'errores', 'accion', 'titulo'));
    }

    private function vista(string $nombre, array $datos = []): void
    {
        extract($datos);

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $nombre . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    private function redirigir(string $mensaje): void
    {
        $_SESSION['mensaje'] = $mensaje;
        header('Location: index.php?accion=listar');
        exit;
    }

    private function soloPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?accion=listar');
            exit;
        }
    }
}
