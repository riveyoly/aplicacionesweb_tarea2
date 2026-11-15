-- =============================================================
-- BASE DE DATOS DE LA TAREA 2 (PostgreSQL)
--
-- 1) Crear la base de datos (en pgAdmin o en psql):
--        CREATE DATABASE tarea2;
--
-- 2) Conectado a la base "tarea2", ejecutar el resto del script.
-- =============================================================

CREATE TABLE IF NOT EXISTS clientes (
    id               SERIAL        PRIMARY KEY,
    cedula           VARCHAR(10)   NOT NULL UNIQUE,
    nombres          VARCHAR(100)  NOT NULL,
    apellidos        VARCHAR(100)  NOT NULL,
    email            VARCHAR(150)  NULL,
    telefono         VARCHAR(10)   NULL,
    direccion        TEXT          NULL,
    fecha_nacimiento DATE          NULL,
    activo           BOOLEAN       NOT NULL DEFAULT TRUE,
    fecha_registro   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Datos de ejemplo (opcional)
INSERT INTO clientes (cedula, nombres, apellidos, email, telefono, direccion, fecha_nacimiento, activo)
VALUES
    ('0912345675', 'Ana María', 'Pérez Torres', 'ana.perez@correo.com', '0991234567', 'Av. 9 de Octubre 123, Guayaquil', '1995-04-10', TRUE),
    ('1712345675', 'Carlos Andrés', 'Mora Vélez', 'carlos.mora@correo.com', '0987654321', 'Calle Amazonas N24-56, Quito', '1988-11-22', TRUE)
ON CONFLICT (cedula) DO NOTHING;
