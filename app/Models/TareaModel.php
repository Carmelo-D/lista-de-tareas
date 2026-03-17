<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    // 1. Tabla que maneja este modelo
    protected $table = 'tareas'; 

    // 2. Llave primaria
    protected $primaryKey = 'id';

    // 3. Campos permitidos para inserción y edición
    // Agregamos 'completada' y 'fecha_creacion'
    protected $allowedFields = ['titulo', 'estado', 'perfil_id', 'completada', 'fecha_creacion', 'fecha_recordatorio', 'categoria_id'];
    // 4. Retornar datos como objetos (facilita el uso de $tarea->titulo)
    protected $returnType = 'object';

    // 5. Opcional: Autogestión de fechas
    // Si quieres que CodeIgniter maneje los tiempos automáticamente:
    protected $useTimestamps = false; // Lo dejamos en false si prefieres controlarlo tú manualmente
}