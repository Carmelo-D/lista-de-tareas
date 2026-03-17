<?php

namespace App\Controllers;

use App\Models\TareaModel;

class Tareas extends BaseController
{
    public function getIndex()
    {
    $session = session();
    if (!$session->has('perfil_id')) {
        return redirect()->to(base_url('perfiles'));
    }

    $modeloTareas = new \App\Models\TareaModel();
    $modeloCats = new \App\Models\CategoriaModel(); // <--- Nuevo
    
    $perfil_id = $session->get('perfil_id');

    $datos['listado'] = $modeloTareas->where('perfil_id', $perfil_id)
                                     ->orderBy('completada', 'ASC')
                                     ->orderBy('id', 'DESC')
                                     ->findAll();

    // Traemos las categorías del usuario
    $datos['categorias'] = $modeloCats->where('perfil_id', $perfil_id)->findAll(); // <--- Nuevo

    $datos['nombre_usuario'] = $session->get('perfil_nombre');

    return view('lista_tareas', $datos);
    }

    public function postCrear()
    {
    $modelo = new TareaModel();
    $session = session();

    // Capturamos la categoría seleccionada (si no hay, queda en NULL/General)
    $categoria_id = $this->request->getPost('categoria_id');
    if (empty($categoria_id)) {
        $categoria_id = null;
    }

    $nuevaTarea = [
        'titulo'            => $this->request->getPost('titulo'),
        'estado'            => 'pendiente',
        'completada'        => 0,
        'perfil_id'         => $session->get('perfil_id'),
        'categoria_id'      => $categoria_id // <--- ESTA LÍNEA ES LA CLAVE
    ];

    $modelo->insert($nuevaTarea);
    return redirect()->to(base_url('tareas'));
    }

    public function getCompletar($id)
    {
        $modelo = new TareaModel();
        $perfil_id = session()->get('perfil_id');

        // Validamos que la tarea pertenezca al perfil activo antes de marcarla
        $tarea = $modelo->where('id', $id)->where('perfil_id', $perfil_id)->first();

        if ($tarea) {
            $modelo->update($id, [
                'estado'     => 'completado',
                'completada' => 1 // <--- Importante para el ordenamiento
            ]);
        }
        
        return redirect()->to(base_url('tareas'));
    }

    public function getEliminar($id)
    {
        $modelo = new TareaModel();
        $perfil_id = session()->get('perfil_id');

        // Solo eliminamos si la tarea es del perfil actual
        $modelo->where('perfil_id', $perfil_id)->delete($id);
        
        return redirect()->to(base_url('tareas'));
    }

    public function postProgramar()
    {
        $modelo = new TareaModel();
        $id = $this->request->getPost('id');
        $fecha = $this->request->getPost('fecha_recordatorio');

        // Validamos que tengamos ID y Fecha antes de intentar nada
        if (!$id || !$fecha) {
            return redirect()->back()->with('error', 'Datos incompletos');
        }

        // Solo intentamos actualizar si el modelo tiene qué enviar
        $datos = ['fecha_recordatorio' => $fecha];
        
        if ($modelo->update($id, $datos)) {
            return redirect()->to(base_url('tareas'))->with('mensaje', 'Recordatorio guardado');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar');
        }
    }

    public function postLimpiarHistorial()
    {
    $modelo = new TareaModel();
    $session = session();
    $perfil_id = $session->get('perfil_id');
    $categoria_id = $this->request->getPost('categoria_id');

    // Construimos la consulta
    $query = $modelo->where('perfil_id', $perfil_id)
                    ->where('estado', 'completado');

    // Si no es "todas" (General), filtramos por la categoría específica
    if ($categoria_id !== 'todas') {
        $query->where('categoria_id', $categoria_id);
    } else {
        $query->where('categoria_id', null);
    }

    $query->delete();

    return redirect()->to(base_url('tareas'))->with('mensaje', 'Historial limpio');
    }   

    public function getEditar($id)
    {
        $session = session();
        $perfil_id = $session->get('perfil_id');
        $modeloTareas = new TareaModel();
        $modeloCats = new \App\Models\CategoriaModel();

        // Buscamos la tarea asegurándonos de que sea del usuario actual
        $tarea = $modeloTareas->where('id', $id)
                              ->where('perfil_id', $perfil_id)
                              ->first();

        if (!$tarea) {
            return redirect()->to(base_url('tareas'))->with('error', 'Tarea no encontrada');
        }

        $datos['tarea'] = $tarea;
        $datos['categorias'] = $modeloCats->where('perfil_id', $perfil_id)->findAll();

        return view('editar_tarea', $datos);
    }

    public function postActualizar()
    {
        $modelo = new TareaModel();
        $id = $this->request->getPost('id');
        
        $datos = [
            'titulo'       => $this->request->getPost('titulo'),
            'categoria_id' => $this->request->getPost('categoria_id') ?: null
        ];

        $modelo->update($id, $datos);
        return redirect()->to(base_url('tareas'))->with('mensaje', 'Tarea actualizada');
    }


}







