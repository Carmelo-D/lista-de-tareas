<!DOCTYPE html>
<html>
<head>
    <title>TaskFlix - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #141414; color: white; font-family: sans-serif; }
        .login-card { background-color: rgba(0,0,0,0.75); padding: 60px; border-radius: 4px; max-width: 450px; width: 100%; }
        .btn-netflix { background-color: #e50914; color: white; font-weight: bold; border: none; }
        .btn-netflix:hover { background-color: #b30000; color: white; }
        .form-control { background-color: #333 !important; border: none !important; color: white !important; padding: 12px; }
        /* Estilo para las alertas tipo Netflix */
        .alert-taskflix { background-color: #e87c03; color: white; border: none; font-size: 14px; border-radius: 4px; }
        .alert-success-taskflix { background-color: #2b732b; color: white; border: none; font-size: 14px; border-radius: 4px; }
        /* Color del texto que escribes */
        .form-control { 
            background-color: #333 !important; 
            border: none !important; 
            color: white !important; /* Texto que escribes en blanco */
            padding: 12px; 
        }

        /* Color del placeholder (el texto de ayuda) */
        .form-control::placeholder {
            color: #e0e0e0 !important; /* Blanco claro / Gris muy claro */
            opacity: 1; /* Necesario para Firefox */
        }

        /* Para navegadores antiguos o específicos */
        .form-control:-ms-input-placeholder { color: #e0e0e0 !important; }
        .form-control::-ms-input-placeholder { color: #e0e0e0 !important; }
    </style>
</head>
<body class="vh-100 d-flex align-items-center justify-content-center">
    <div class="login-card">
        <h1 class="text-danger fw-bold mb-4 text-center">TaskFlix</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-taskflix mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('mensaje')): ?>
            <div class="alert alert-success-taskflix mb-4">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= session()->getFlashdata('mensaje'); ?>
            </div>
        <?php endif; ?>

        <h3 class="mb-4">Inicia sesión</h3>
        
        <form action="<?php echo base_url('auth/postLogin'); ?>" method="POST">
            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-netflix w-100 py-2 mb-3">Iniciar Sesión</button>
        </form>
        
        <p class="text-secondary small">¿Primera vez en TaskFlix? <a href="<?php echo base_url('registro'); ?>" class="text-white text-decoration-none">Regístrate ahora.</a></p>
    </div>
</body>
</html>