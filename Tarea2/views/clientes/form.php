<?php
/** Muestra el mensaje de error de un campo, si existe. */
$error = fn(string $campo) => isset($errores[$campo])
    ? '<small class="error">' . e($errores[$campo]) . '</small>'
    : '';

$destino = 'index.php?accion=' . $accion
    . ($cliente->getId() ? '&id=' . $cliente->getId() : '');
?>
<h1><?= e($titulo) ?></h1>

<form method="post" action="<?= e($destino) ?>">
    <div class="campo">
        <label for="cedula">Cédula *</label>
        <input type="text" id="cedula" name="cedula" maxlength="10" required
               value="<?= e($cliente->getCedula()) ?>">
        <?= $error('cedula') ?>
    </div>

    <div class="campo">
        <label for="nombres">Nombres *</label>
        <input type="text" id="nombres" name="nombres" maxlength="100" required
               value="<?= e($cliente->getNombres()) ?>">
        <?= $error('nombres') ?>
    </div>

    <div class="campo">
        <label for="apellidos">Apellidos *</label>
        <input type="text" id="apellidos" name="apellidos" maxlength="100" required
               value="<?= e($cliente->getApellidos()) ?>">
        <?= $error('apellidos') ?>
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" maxlength="150"
               value="<?= e($cliente->getEmail()) ?>">
        <?= $error('email') ?>
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" maxlength="10"
               value="<?= e($cliente->getTelefono()) ?>">
        <?= $error('telefono') ?>
    </div>

    <div class="campo">
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3"><?= e($cliente->getDireccion()) ?></textarea>
    </div>

    <div class="campo">
        <label for="fecha_nacimiento">Fecha de nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
               value="<?= e($cliente->getFechaNacimiento()) ?>">
    </div>

    <div class="campo campo-check">
        <input type="checkbox" id="activo" name="activo" value="1" <?= $cliente->isActivo() ? 'checked' : '' ?>>
        <label for="activo">Activo</label>
    </div>

    <div class="botones">
        <button type="submit" class="boton">Guardar</button>
        <a href="index.php?accion=listar">Cancelar</a>
    </div>
</form>
