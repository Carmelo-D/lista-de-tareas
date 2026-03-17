<?php

namespace App\Models;

use CodeIgniter\Model;

class PerfilModel extends Model
{
    protected $table            = 'perfiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // Esto es para que trabajes con objetos como haces en la vista
    protected $allowedFields    = ['nombre', 'avatar', 'pin', 'usuario_id']; // Importante para poder insertar nuevos perfiles
}