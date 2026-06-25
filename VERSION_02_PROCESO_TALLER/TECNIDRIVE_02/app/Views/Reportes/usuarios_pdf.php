<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Usuarios</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f8fafc;
        color: #334155;
        font-size: 13px;
        padding: 30px;
    }

    .encabezado {
        background-color: #0f172a;
        color: #ffffff;
        padding: 20px 25px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 6px solid #f97316;
    }

    .encabezado h1 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #ffffff;
    }

    .encabezado p {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }

    .encabezado .naranja { color: #f97316; }

    .tabla-resumen {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: separate;
        border-spacing: 10px 0;
    }

    .td-tarjeta {
        width: 50%;
        background-color: #1c2030;
        color: #ffffff;
        padding: 18px 15px;
        border-radius: 8px;
        text-align: center;
        border-bottom: 4px solid #f97316;
    }

    .td-tarjeta .icono {
        font-size: 22px;
        color: #f97316;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .td-tarjeta .rol-titulo {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #ffffff;
        margin-bottom: 4px;
    }

    .td-tarjeta .numero {
        font-size: 28px;
        font-weight: 700;
        color: #ffffff;
        margin: 6px 0;
    }

    .td-tarjeta .descripcion {
        font-size: 11px;
        color: #94a3b8;
    }

    .subtitulo {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        padding-bottom: 6px;
        border-bottom: 3px solid #f97316;
        display: block;
    }

    .tabla-usuarios {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 12px;
    }

    .tabla-usuarios th {
        background-color: #03277b;
        color: #ffffff;
        padding: 10px 12px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: 3px solid #f97316;
    }

    .tabla-usuarios td {
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: middle;
    }

    .tabla-usuarios tr:nth-child(even) td {
        background-color: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
    }

    .badge-propietario { background-color: #2ecc71; }
    .badge-mecanico    { background-color: #e74c3c; }

    .sin-datos {
        text-align: center;
        color: #94a3b8;
        padding: 30px;
        font-style: italic;
        font-size: 13px;
    }

    .pie {
        margin-top: 25px;
        text-align: right;
        font-size: 11px;
        color: #94a3b8;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }
</style>
</head>
<body>

    <!-- ENCABEZADO -->
    <div class="encabezado">
        <h1>Reporte de Usuarios Registrados</h1>
        <p>
            Periodo: <span class="naranja"><?= esc($periodoTexto) ?></span>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Generado: <span class="naranja"><?= esc($fechaGeneracion) ?></span>
        </p>
    </div>

    <!-- TARJETAS DE RESUMEN usando tabla para compatibilidad Dompdf -->
    <table class="tabla-resumen">
        <tr>
            <?php if ($totalPropietarios > 0 || true): /* mostrar siempre */ ?>
            <td class="td-tarjeta">
                <div class="rol-titulo">Propietarios</div>
                <div class="numero"><?= $totalPropietarios ?></div>
                <div class="descripcion">Número de propietarios</div>
            </td>
            <?php endif; ?>

            <?php if ($totalMecanicos > 0 || true): ?>
            <td class="td-tarjeta">
                <div class="rol-titulo">Mecánicos</div>
                <div class="numero"><?= $totalMecanicos ?></div>
                <div class="descripcion">Número de mecánicos</div>
            </td>
            <?php endif; ?>
        </tr>
    </table>

    <!-- TABLA DE USUARIOS -->
    <span class="subtitulo">Listado de usuarios</span>

    <?php if (empty($usuarios)): ?>
        <p class="sin-datos">No se encontraron usuarios en el período seleccionado.</p>
    <?php else: ?>
        <table class="tabla-usuarios">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= esc($u['primer_nombre'] . ' ' . $u['primer_apellido']) ?></td>
                    <td><?= esc($u['email']) ?></td>
                    <td>
                        <?php $rol = strtolower($u['texto_rol']); ?>
                        <span class="badge badge-<?= $rol === 'propietario' ? 'propietario' : 'mecanico' ?>">
                            <?= esc(ucfirst($u['texto_rol'])) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['fecha_registro'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- PIE -->
    <div class="pie">
        TECNIDRIVE &mdash; Reporte generado el <?= esc($fechaGeneracion) ?>
    </div>

</body>
</html>