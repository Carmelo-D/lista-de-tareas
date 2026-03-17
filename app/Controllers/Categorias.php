<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class Categorias extends BaseController
{
    public function postCrear()
    {
        $session = session();
        $modelo = new CategoriaModel();

        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'perfil_id' => $session->get('perfil_id'),
            'color'     => $this->request->getPost('color') ?? '#0d6efd'
        ];

        $modelo->insert($data);
        return redirect()->to(base_url('tareas'));
    }

    public function getEliminar($id)
    {
    $modelo = new \App\Models\CategoriaModel();
    $session = session();
    
    // Verificamos que la pestaña pertenezca al usuario logueado por seguridad
    $perfil_id = $session->get('perfil_id');
    
    if ($modelo->where('id', $id)->where('perfil_id', $perfil_id)->delete()) {
        return redirect()->to(base_url('tareas'))->with('mensaje', 'Pestaña eliminada correctamente');
    }

    return redirect()->to(base_url('tareas'))->with('error', 'No se pudo eliminar la pestaña');
    }
}