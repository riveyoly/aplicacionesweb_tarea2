<?php
/**
 * CONFIGURACIÓN DE LA BASE DE DATOS
 *
 * Cambie aquí los datos de conexión a PostgreSQL.
 */

class Database
{
    // ---- Datos de conexión (modificar según su equipo) ----
    private const HOST     = '127.0.0.1';
    private const PUERTO   = '5432';
    private const BASE     = 'tarea2';
    private const USUARIO  = 'postgres';
    private const CLAVE    = 'TU_CONTRASEÑA';

    private static ?PDO $conexion = null;

    /**
     * Devuelve una única conexión PDO para toda la aplicación.
     */
    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            $dsn = 'pgsql:host=' . self::HOST . ';port=' . self::PUERTO . ';dbname=' . self::BASE;

            self::$conexion = new PDO($dsn, self::USUARIO, self::CLAVE, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return self::$conexion;
    }
}
