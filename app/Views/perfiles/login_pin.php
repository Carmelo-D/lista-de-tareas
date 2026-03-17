<!DOCTYPE html>
<html>
<head>
    <title>Ingresa tu PIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #141414; color: white; }
        .pin-input { font-size: 2rem; text-align: center; letter-spacing: 1rem; }
    </style>
</head>
<body class="vh-100 d-flex align-items-center justify-content-center text-center">
    <div>
        <h2 class="mb-4">Ingresa el PIN de <?php echo $perfil->nombre; ?></h2>
        <form action="<?php echo base_url('perfiles/verificar_pin'); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $perfil->id; ?>">
            <input type="password" name="pin" class="form-control pin-input mb-4" maxlength="4" required autofocus>
            <button type="submit" class="btn btn-light">Entrar</button>
            <a href="<?php echo base_url('perfiles'); ?>" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>