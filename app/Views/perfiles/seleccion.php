<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Quién eres? - Mis Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #141414; color: white; font-family: sans-serif; }
        .perfil-card {
            text-align: center;
            text-decoration: none;
            color: #808080;
            transition: transform 0.3s;
            position: relative; /* Importante para posicionar la X */
        }
        .perfil-card:hover {
            transform: scale(1.1);
            color: white;
        }
        .avatar-box {
            width: 150px;
            height: 150px;
            background-color: #333;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            margin-bottom: 10px;
            border: 3px solid transparent;
        }
        .perfil-card:hover .avatar-box {
            border-color: white;
        }
        /* Estilo para el botón de borrar */
        /* Estilo para el botón de borrar (La X) */
        .btn-borrar {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff0000 !important; /* Rojo puro para que resalte */
            color: white !important;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            z-index: 999; /* Asegura que esté por encima de todo */
            border: 2px solid white;
            text-decoration: none;
            box-shadow: 0px 0px 10px rgba(255,0,0,0.5);
        }

        .btn-borrar:hover {
            background-color: #b30000 !important;
            color: white;
            transform: scale(1.2);
        }
        .perfil-card:hover .btn-borrar {
            opacity: 1; /* Aparece al pasar el mouse por el perfil */
        }

        /* Animación de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contenedor-animado {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Efecto hover extra para los perfiles */
        .perfil-card:hover .avatar-box {
            border-color: white;
            box-shadow: 0px 0px 20px rgba(255,255,255,0.2);
        }
    </style>
</head>
<body class="position-relative">
    
    <div style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
        <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm" style="border-color: #333; color: #888;">
            <i class="fa-solid fa-arrow-left"></i> Salir de TaskFlix
        </a>
    </div>

    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center contenedor-animado">
        
        <h1 class="mb-5">¡Hola, <?php echo session()->get('username'); ?>! Elige un perfil</h1>

        <div class="d-flex flex-wrap justify-content-center gap-4">
            
            <?php if(!empty($perfiles)): ?>
                <?php foreach($perfiles as $p): ?>
                    <div style="position: relative;">
                        <a href="<?php echo base_url('perfiles/eliminar/'.$p->id); ?>" 
                           class="btn-borrar" 
                           onclick="return confirm('¿Eliminar este perfil?')">
                            <i class="fa-solid fa-xmark"></i>
                        </a>

                        <a href="<?php echo base_url('perfiles/seleccionar/'.$p->id); ?>" class="perfil-card">
                            <div class="avatar-box <?php echo $p->avatar; ?> text-white">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <h5><?php echo $p->nombre; ?></h5>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(count($perfiles) < 6): ?>
                <a href="#" class="perfil-card" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                    <div class="avatar-box bg-dark text-white" style="border: 2px dashed #444;">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h5>Añadir perfil</h5>
                </a>
            <?php endif; ?>

        </div>
    </div>

    <div class="modal fade text-dark" id="modalNuevo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('perfiles/crear'); ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark">Nombre del perfil</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej. Invitado" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark">PIN (4 dígitos)</label>
                            <input type="password" name="pin" class="form-control" maxlength="4" placeholder="0000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark">Color de perfil</label>
                            <select name="avatar" class="form-select">
                                <option value="bg-primary">Azul</option>
                                <option value="bg-danger">Rojo</option>
                                <option value="bg-success">Verde</option>
                                <option value="bg-warning">Amarillo</option>
                                <option value="bg-info">Cian</option>
                                <option value="bg-secondary">Gris</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>