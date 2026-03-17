<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model {
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'correo', 'password'];
    protected $returnType = 'object';
}