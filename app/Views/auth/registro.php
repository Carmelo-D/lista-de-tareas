<!DOCTYPE html>
<html>
<head>
    <title>TaskFlix - Únete ahora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #141414; color: white; font-family: sans-serif; }
        .register-card { background-color: rgba(0,0,0,0.75); padding: 40px 60px; border-radius: 8px; max-width: 500px; width: 100%; }
        .btn-netflix { background-color: #e50914; color: white; font-weight: bold; border: none; }
        .btn-netflix:hover { background-color: #b30000; color: white; }
        
        .form-control { 
            background-color: #333 !important; 
            border: none !important; 
            color: white !important; 
            padding: 12px; 
        }

        .form-control::placeholder {
            color: #e0e0e0 !important; 
            opacity: 1; 
        }

        /* Estilo para las alertas tipo Netflix que ya usamos en login */
        .alert-taskflix { background-color: #e87c03; color: white; border: none; font-size: 14px; border-radius: 4px; }
    </style>
</head>
<body class="vh-100 d-flex align-items-center justify-content-center">
    <div class="register-card">
        <h1 class="text-danger fw-bold mb-4 text-center">TaskFlix</h1>
        <h3 class="mb-4">Crea tu cuenta maestra</h3>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-taskflix mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo base_url('auth/postRegistrar'); ?>" method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Nombre de usuario" value="<?= old('username') ?>" required>
            </div>

            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" value="<?= old('correo') ?>" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <small class="text-secondary" style="font-size: 11px;">
            Mínimo 8 caracteres, con mayúsculas, minúsculas y números.
            </small>

            <div class="mb-4">
                <input type="password" name="confirm_password" class="form-control" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-netflix w-100 py-2 mb-3">Registrarse</button>
        </form>
        
        <p class="text-secondary small text-center">
            ¿Ya tienes cuenta? <a href="<?php echo base_url('login'); ?>" class="text-white text-decoration-none">Inicia sesión aquí.</a>
        </p>
    </div>
</body>
</html>