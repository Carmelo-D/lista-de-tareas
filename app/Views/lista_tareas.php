<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <title>Mi Lista de Tareas</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f4f4; }
        .contenedor { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .vacio { color: #888; font-style: italic; }
        .badge-count { font-size: 0.8rem; padding: 5px 10px; border-radius: 20px; }
        .btn-calendar { color: #6366f1; transition: transform 0.2s; cursor: pointer; border: none; background: none; }
        .btn-calendar:hover { transform: scale(1.2); color: #4338ca; }
        
        /* --- ESTILOS PRO PARA LA BARRA DE PROGRESO --- */
        .progress {
            height: 28px;
            background-color: #e9ecef;
            border-radius: 50px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        #barra-progreso-dinamica {
            font-weight: bold;
            font-size: 0.9rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1), background 0.5s ease;
            background: linear-gradient(90deg, #28a745 0%, #34ce57 100%);
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #barra-progreso-dinamica span {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            white-space: nowrap;
        }

        /* Estilos para los filtros */
        .filtro-btn.active { background-color: #333 !important; color: white !important; border-color: #333 !important; }
        
        /* Estilos para pestañas */
        .nav-tabs .nav-link { border: none; color: #666; font-size: 0.95rem; }
        .nav-tabs .nav-link.active { border-bottom: 3px solid #333; color: #000 !important; background: none; }
    </style>
</head>
<body>

<div class="contenedor">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 style="margin: 0;">Mis Pendientes</h1>
        <a href="<?php echo base_url('perfiles/salir'); ?>" class="btn btn-outline-danger btn-sm">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
        </a>
    </div>

    <div class="mb-4">
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">
            Conectado como: <strong><?php echo session()->get('perfil_nombre'); ?></strong>
        </p>
        
        <div class="progress mb-3">
            <div id="barra-progreso-dinamica" 
                class="progress-bar progress-bar-striped progress-bar-animated" 
                role="progressbar" style="width: 0%;" 
                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <span>0%</span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span id="badge-pendientes" class="badge bg-warning text-dark badge-count">Pendientes: 0</span>
                <span id="badge-completadas" class="badge bg-success badge-count">Completadas: 0</span>
            </div>
            
            <div class="d-flex gap-2 align-items-center">
                <form action="<?= base_url('tareas/limpiar-historial') ?>" method="POST" id="form-limpiar-historial" style="display: none;">
                    <input type="hidden" name="categoria_id" id="limpiar-cat-id" value="todas">
                </form>

                <div id="contenedor-btn-limpiar" style="display: none;">
                    <button type="button" class="btn btn-outline-danger btn-sm shadow-sm" onclick="abrirModalLimpiarHistorial()">
                        <i class="fa-solid fa-broom"></i> Limpiar Historial
                    </button>
                </div>

                <div class="btn-group shadow-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm filtro-btn active" onclick="filtrarTareas('todas', this)">Todas</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm filtro-btn" onclick="filtrarTareas('pendiente', this)">Pendientes</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm filtro-btn" onclick="filtrarTareas('vencida', this)">Vencidas</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm filtro-btn" onclick="filtrarTareas('completado', this)">Completadas</button>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3" id="pestañasCategorias" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold" onclick="filtrarPorCategoria('todas', this)" type="button">
                    <i class="fa-solid fa-layer-group"></i> General
                </button>
            </li>
            <?php if(!empty($categorias)): ?>
                <?php foreach($categorias as $cat): ?>
                    <li class="nav-item d-flex align-items-center">
                        <button class="nav-link fw-bold" style="color: <?= $cat->color ?>;" 
                                onclick="filtrarPorCategoria(<?= $cat->id ?>, this)" type="button">
                            <?= $cat->nombre ?>
                        </button>
                        <a href="javascript:void(0)" 
                           class="text-danger ms-2" 
                           style="text-decoration: none; font-size: 0.8rem;"
                           onclick="abrirModalBorrarPestaña(<?= $cat->id ?>, '<?= addslashes($cat->nombre) ?>')">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
            <li class="nav-item">
                <button class="nav-link text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
                    <i class="fa-solid fa-plus-circle"></i> Nueva
                </button>
            </li>
        </ul>
    </div>

    <form action="<?php echo base_url('tareas/crear'); ?>" method="POST" class="row g-2 mb-4">
        <div class="col-md-7">
            <input type="text" name="titulo" class="form-control" placeholder="¿Qué tienes que hacer?" required>
        </div>
        <div class="col-md-3">
            <select name="categoria_id" class="form-select">
                <option value="">Pestaña: General</option>
                <?php if(!empty($categorias)): ?>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat->id ?>"><?= $cat->nombre ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">Agregar</button>
        </div>
    </form>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Tarea</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="text-end" style="padding-right: 20px;">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-tareas">
            <?php if(!empty($listado)): ?>
                <?php foreach($listado as $tarea): ?>
                    <tr class="fila-tarea" 
                        data-estado="<?= $tarea->estado ?>" 
                        data-vencida="false"
                        data-categoria="<?= $tarea->categoria_id ?? 'todas' ?>">
                        <td style="<?php echo ($tarea->estado == 'completado') ? 'text-decoration: line-through; color: gray; opacity: 0.7;' : ''; ?>">
                            <div><?php echo $tarea->titulo; ?></div>
                            <?php if(!empty($tarea->fecha_recordatorio) && $tarea->estado == 'pendiente'): ?>
                                <small class="fw-bold recordatorio-js" style="font-size: 0.75rem;" data-fecha="<?= $tarea->fecha_recordatorio ?>">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Sincronizando...
                                </small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="color: <?php echo ($tarea->estado == 'pendiente') ? 'orange' : 'green'; ?>">
                                <i class="fa-solid <?php echo ($tarea->estado == 'pendiente') ? 'fa-clock' : 'fa-check-double'; ?>"></i>
                                <?php echo ucfirst($tarea->estado); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($tarea->fecha_creacion)); ?></td>
                        
                        <td class="text-end"> 
                            <?php if($tarea->estado == 'pendiente'): ?>
                                <button type="button" class="btn-calendar me-2" onclick="abrirModalCalendario(<?= $tarea->id ?>, '<?= addslashes($tarea->titulo) ?>')">
                                    <i class="fa-solid fa-calendar-check fa-lg"></i>
                                </button>
                                <a href="<?php echo base_url('tareas/completar/'.$tarea->id); ?>" class="btn btn-success btn-sm me-1"><i class="fa-solid fa-circle-check"></i></a>
                                <a href="<?php echo base_url('tareas/editar/'.$tarea->id); ?>" class="btn btn-primary btn-sm me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <?php endif; ?>
                            <a href="<?php echo base_url('tareas/eliminar/'.$tarea->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro?')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="mensaje-vacio"><td colspan="4" class="vacio text-center">No hay tareas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalNuevaCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm text-dark">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title">Nueva Pestaña</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('categorias/crear') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nombre</label>
                        <input type="text" name="nombre" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark">Color</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#0d6efd">
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <button type="submit" class="btn btn-success btn-sm w-100">Crear Espacio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCalendario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-dark">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fa-solid fa-clock me-2"></i> Programar Recordatorio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('tareas/programar') ?>" method="POST">
                <div class="modal-body p-4 text-dark">
                    <input type="hidden" name="id" id="tarea_id">
                    <p class="text-muted small mb-4">Configurar alerta para: <strong id="tarea_titulo" class="text-dark"></strong></p>
                    <div class="form-group">
                        <label class="form-label fw-bold">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_recordatorio" class="form-control form-control-lg border-primary text-dark" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarBorrar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm text-dark">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title text-white"><i class="fa-solid fa-triangle-exclamation"></i> ¿Confirmar?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-1 text-dark">¿Estás seguro de eliminar <strong id="nombre_pestaña_borrar"></strong>?</p>
                <small class="text-muted">Las tareas volverán a <strong>General</strong>.</small>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-light btn-sm px-3 text-dark" data-bs-dismiss="modal">Cancelar</button>
                <a id="enlace_confirmar_borrado" href="#" class="btn btn-danger btn-sm px-3 text-white" style="text-decoration:none">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarLimpiar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm text-dark">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title text-white"><i class="fa-solid fa-broom"></i> ¿Vaciar Historial?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4 text-dark">
                <p class="mb-1 text-dark">¿Estás seguro de eliminar permanentemente todas las tareas completadas de esta pestaña?</p>
                <small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-light btn-sm px-3 text-dark" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-ejecutar-limpieza" class="btn btn-danger btn-sm px-3">Limpiar Todo</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let categoriaActual = 'todas';
let estadoActual = 'todas';

function actualizarProgresoVisual() {
    const filas = document.querySelectorAll('.fila-tarea');
    let totalVisibles = 0;
    let completadasVisibles = 0;

    filas.forEach(fila => {
        const catFila = fila.getAttribute('data-categoria');
        const estadoFila = fila.getAttribute('data-estado');

        if (categoriaActual === 'todas' || catFila == categoriaActual) {
            totalVisibles++;
            if (estadoFila === 'completado') {
                completadasVisibles++;
            }
        }
    });

    const porcentaje = (totalVisibles > 0) ? Math.round((completadasVisibles / totalVisibles) * 100) : 0;
    const barra = document.getElementById('barra-progreso-dinamica');
    
    if (barra) {
        barra.style.width = porcentaje + '%';
        barra.setAttribute('aria-valuenow', porcentaje);

        if (porcentaje === 100 && totalVisibles > 0) {
            barra.innerHTML = `<span><i class="fa-solid fa-crown me-2"></i> Completado 100%</span>`;
            barra.style.background = 'linear-gradient(90deg, #ffc107 0%, #ffdb4d 100%)';
            barra.style.color = '#000';
        } else {
            barra.innerHTML = `<span><i class="fa-solid fa-bolt me-2"></i> ${porcentaje}%</span>`;
            barra.style.background = 'linear-gradient(90deg, #28a745 0%, #34ce57 100%)';
            barra.style.color = '#fff';
        }
    }

    document.getElementById('badge-pendientes').innerText = `Pendientes: ${totalVisibles - completadasVisibles}`;
    document.getElementById('badge-completadas').innerText = `Completadas: ${completadasVisibles}`;
}

function actualizarRelojesYVencimientos() {
    document.querySelectorAll('.recordatorio-js').forEach(el => {
        const fechaDB = el.getAttribute('data-fecha');
        if (!fechaDB) return;
        const recordatorio = new Date(fechaDB);
        const ahora = new Date();
        const fila = el.closest('.fila-tarea');
        const opciones = { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit', hour12: true };
        const fechaTexto = recordatorio.toLocaleString(undefined, opciones);
        if (recordatorio < ahora) {
            el.style.color = '#dc3545';
            el.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${fechaTexto} (VENCIDO)`;
            fila.setAttribute('data-vencida', 'true');
        } else {
            el.style.color = '#0d6efd';
            el.innerHTML = `<i class="fa-solid fa-bell"></i> ${fechaTexto}`;
        }
    });
}

function aplicarFiltrosCombinados() {
    let hayCompletadasEnEstaPestaña = false;

    const filas = document.querySelectorAll('.fila-tarea');
    filas.forEach(fila => {
        const catFila = fila.getAttribute('data-categoria');
        const estadoFila = fila.getAttribute('data-estado');
        const esVencida = fila.getAttribute('data-vencida') === 'true';

        const cumpleCategoria = (categoriaActual === 'todas' || catFila == categoriaActual);
        
        if (cumpleCategoria && estadoFila === 'completado') {
            hayCompletadasEnEstaPestaña = true;
        }

        let cumpleEstado = false;
        if (estadoActual === 'todas') cumpleEstado = true;
        else if (estadoActual === 'pendiente') cumpleEstado = (estadoFila === 'pendiente');
        else if (estadoActual === 'completado') cumpleEstado = (estadoFila === 'completado');
        else if (estadoActual === 'vencida') cumpleEstado = (esVencida && estadoFila === 'pendiente');

        fila.style.display = (cumpleCategoria && cumpleEstado) ? '' : 'none';
    });

    // LÓGICA DEL BOTÓN LIMPIAR (CORREGIDA)
    const contenedorBtn = document.getElementById('contenedor-btn-limpiar');
    const inputLimpiarCat = document.getElementById('limpiar-cat-id');

    if (estadoActual === 'completado' && hayCompletadasEnEstaPestaña) {
        contenedorBtn.style.display = 'block';
        inputLimpiarCat.value = categoriaActual;
    } else {
        contenedorBtn.style.display = 'none';
    }

    actualizarProgresoVisual();
}

function filtrarPorCategoria(catId, btn) {
    document.querySelectorAll('#pestañasCategorias .nav-link').forEach(l => l.classList.remove('active'));
    btn.classList.add('active');
    categoriaActual = catId;
    aplicarFiltrosCombinados();
}

function filtrarTareas(filtro, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    estadoActual = filtro;
    aplicarFiltrosCombinados();
}

window.onload = function() {
    actualizarRelojesYVencimientos();
    aplicarFiltrosCombinados();
};

function abrirModalCalendario(id, titulo) {
    document.getElementById('tarea_id').value = id;
    document.getElementById('tarea_titulo').innerText = titulo;
    var myModal = new bootstrap.Modal(document.getElementById('modalCalendario'));
    myModal.show();
}

function abrirModalBorrarPestaña(id, nombre) {
    document.getElementById('nombre_pestaña_borrar').innerText = nombre;
    const url = "<?= base_url('categorias/eliminar/') ?>/" + id;
    document.getElementById('enlace_confirmar_borrado').setAttribute('href', url);
    var myModal = new bootstrap.Modal(document.getElementById('modalConfirmarBorrar'));
    myModal.show();
}

function abrirModalLimpiarHistorial() {
    var myModal = new bootstrap.Modal(document.getElementById('modalConfirmarLimpiar'));
    myModal.show();
}

// ESCUCHADOR INFALIBLE PARA LIMPIAR (NUEVO)
document.getElementById('btn-ejecutar-limpieza').addEventListener('click', function() {
    const formulario = document.getElementById('form-limpiar-historial');
    if (formulario) {
        formulario.submit();
    }
});
</script>

</body>
</html>