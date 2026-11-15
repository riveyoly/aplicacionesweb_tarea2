<div class="barra">
    <h1>Clientes</h1>
    <a class="boton" href="index.php?accion=nuevo">Nuevo</a>
</div>

<?php if (empty($clientes)): ?>
    <p>No hay clientes registrados.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha nac.</th>
                <th>Activo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= e($cliente->getId()) ?></td>
                <td><?= e($cliente->getCedula()) ?></td>
                <td><?= e($cliente->getNombres()) ?></td>
                <td><?= e($cliente->getApellidos()) ?></td>
                <td><?= e($cliente->getEmail()) ?></td>
                <td><?= e($cliente->getTelefono()) ?></td>
                <td><?= e($cliente->getDireccion()) ?></td>
                <td><?= e($cliente->getFechaNacimiento()) ?></td>
                <td><?= $cliente->isActivo() ? 'Sí' : 'No' ?></td>
                <td class="acciones">
                    <a href="index.php?accion=editar&id=<?= e($cliente->getId()) ?>">Editar</a>

                    <form method="post"
                          action="index.php?accion=eliminar&id=<?= e($cliente->getId()) ?>"
                          onsubmit="return confirm('¿Eliminar a <?= e($cliente->getNombreCompleto()) ?>?')">
                        <button type="submit" class="enlace peligro">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
