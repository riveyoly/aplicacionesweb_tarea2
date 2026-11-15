<?php
/**
 * MODELO: Cliente
 *
 * Representa a un cliente (objeto) y contiene los métodos del CRUD
 * que trabajan con la tabla "clientes" de la base de datos.
 */

require_once __DIR__ . '/../config/database.php';

class Cliente
{
    // ---------------- Atributos ----------------
    private ?int $id = null;
    private string $cedula = '';
    private string $nombres = '';
    private string $apellidos = '';
    private string $email = '';
    private string $telefono = '';
    private string $direccion = '';
    private ?string $fechaNacimiento = null;
    private bool $activo = true;
    private ?string $fechaRegistro = null;

    private PDO $db;

    // ---------------- Constructor ----------------
    public function __construct(array $datos = [])
    {
        $this->db = Database::conectar();

        if ($datos) {
            $this->llenar($datos);
        }
    }

    /**
     * Asigna los valores recibidos (de un formulario o de la base de datos).
     */
    public function llenar(array $datos): void
    {
        $this->id              = isset($datos['id']) && $datos['id'] !== '' ? (int) $datos['id'] : $this->id;
        $this->cedula          = trim((string) ($datos['cedula'] ?? ''));
        $this->nombres         = trim((string) ($datos['nombres'] ?? ''));
        $this->apellidos       = trim((string) ($datos['apellidos'] ?? ''));
        $this->email           = trim((string) ($datos['email'] ?? ''));
        $this->telefono        = trim((string) ($datos['telefono'] ?? ''));
        $this->direccion       = trim((string) ($datos['direccion'] ?? ''));
        $this->fechaNacimiento = !empty($datos['fecha_nacimiento']) ? (string) $datos['fecha_nacimiento'] : null;
        $this->activo          = !empty($datos['activo']) && $datos['activo'] !== 'f';
        $this->fechaRegistro   = $datos['fecha_registro'] ?? $this->fechaRegistro;
    }

    // ---------------- Getters ----------------
    public function getId(): ?int { return $this->id; }
    public function getCedula(): string { return $this->cedula; }
    public function getNombres(): string { return $this->nombres; }
    public function getApellidos(): string { return $this->apellidos; }
    public function getEmail(): string { return $this->email; }
    public function getTelefono(): string { return $this->telefono; }
    public function getDireccion(): string { return $this->direccion; }
    public function getFechaNacimiento(): ?string { return $this->fechaNacimiento; }
    public function isActivo(): bool { return $this->activo; }
    public function getFechaRegistro(): ?string { return $this->fechaRegistro; }

    public function getNombreCompleto(): string
    {
        return trim($this->apellidos . ' ' . $this->nombres);
    }

    // ---------------- Validación ----------------

    /**
     * Revisa los datos del cliente y devuelve un arreglo con los errores.
     */
    public function validar(): array
    {
        $errores = [];

        if (!preg_match('/^\d{10}$/', $this->cedula)) {
            $errores['cedula'] = 'La cédula debe tener 10 dígitos.';
        } elseif ($this->cedulaExiste()) {
            $errores['cedula'] = 'Ya existe un cliente con esta cédula.';
        }

        if ($this->nombres === '') {
            $errores['nombres'] = 'Los nombres son obligatorios.';
        }

        if ($this->apellidos === '') {
            $errores['apellidos'] = 'Los apellidos son obligatorios.';
        }

        if ($this->email !== '' && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El correo no tiene un formato válido.';
        }

        if ($this->telefono !== '' && !preg_match('/^\d{7,10}$/', $this->telefono)) {
            $errores['telefono'] = 'El teléfono debe tener entre 7 y 10 dígitos.';
        }

        return $errores;
    }

    private function cedulaExiste(): bool
    {
        $sql = 'SELECT 1 FROM clientes WHERE cedula = :cedula AND id <> :id';
        $consulta = $this->db->prepare($sql);
        $consulta->execute(['cedula' => $this->cedula, 'id' => $this->id ?? 0]);

        return (bool) $consulta->fetchColumn();
    }

    // ================= MÉTODOS DEL CRUD =================

    /**
     * CREATE: inserta el cliente en la base de datos.
     */
    public function crear(): bool
    {
        $sql = 'INSERT INTO clientes
                    (cedula, nombres, apellidos, email, telefono, direccion, fecha_nacimiento, activo)
                VALUES
                    (:cedula, :nombres, :apellidos, :email, :telefono, :direccion, :fecha_nacimiento, :activo)
                RETURNING id';

        $consulta = $this->db->prepare($sql);
        $consulta->execute($this->parametros());

        $this->id = (int) $consulta->fetchColumn();

        return $this->id > 0;
    }

    /**
     * READ: devuelve todos los clientes como objetos Cliente.
     *
     * @return Cliente[]
     */
    public static function listar(): array
    {
        $filas = Database::conectar()
            ->query('SELECT * FROM clientes ORDER BY apellidos, nombres')
            ->fetchAll();

        return array_map(fn(array $fila) => new Cliente($fila), $filas);
    }

    /**
     * READ: busca un cliente por su id. Devuelve null si no existe.
     */
    public static function buscarPorId(int $id): ?Cliente
    {
        $consulta = Database::conectar()->prepare('SELECT * FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();

        return $fila ? new Cliente($fila) : null;
    }

    /**
     * UPDATE: guarda los cambios del cliente.
     */
    public function actualizar(): bool
    {
        $sql = 'UPDATE clientes SET
                    cedula = :cedula,
                    nombres = :nombres,
                    apellidos = :apellidos,
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion,
                    fecha_nacimiento = :fecha_nacimiento,
                    activo = :activo
                WHERE id = :id';

        $consulta = $this->db->prepare($sql);

        return $consulta->execute($this->parametros() + ['id' => $this->id]);
    }

    /**
     * DELETE: elimina el cliente de la base de datos.
     */
    public function eliminar(): bool
    {
        $consulta = $this->db->prepare('DELETE FROM clientes WHERE id = :id');

        return $consulta->execute(['id' => $this->id]);
    }

    /**
     * Valores que se envían en INSERT y UPDATE.
     */
    private function parametros(): array
    {
        return [
            'cedula'           => $this->cedula,
            'nombres'          => $this->nombres,
            'apellidos'        => $this->apellidos,
            'email'            => $this->email !== '' ? $this->email : null,
            'telefono'         => $this->telefono !== '' ? $this->telefono : null,
            'direccion'        => $this->direccion !== '' ? $this->direccion : null,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'activo'           => $this->activo ? 'true' : 'false',
        ];
    }
}
