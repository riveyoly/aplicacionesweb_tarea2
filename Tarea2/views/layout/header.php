<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Clientes') ?> - CRUD Clientes</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<nav>
    <a href="index.php">Inicio</a>
    <a href="index.php?accion=listar">Clientes</a>
</nav>

<main>
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="mensaje"><?= e($_SESSION['mensaje']) ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
