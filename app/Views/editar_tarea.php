<!DOCTYPE html>
<html>
<head>
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 500px;">
        <h3>Modificar Tarea</h3>
        <form action="<?php echo base_url('tareas/actualizar'); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $tarea->id; ?>">
            
            <div class="mb-3">
                <input type="text" name="titulo" class="form-control" value="<?php echo $tarea->titulo; ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="<?php echo base_url('tareas'); ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>