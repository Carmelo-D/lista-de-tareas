<?php

namespace App\Controllers;

use App\Models\PerfilModel;

class Perfiles extends BaseController
{
    // 1. Mostrar solo los perfiles del usuario logueado
    public function index()
    {
        $session = session();
        
        // Seguridad básica: si no hay sesión de cuenta, al login
        if (!$session->has('usuario_id')) {
            return redirect()->to(base_url('login'));
        }

        $modelo = new PerfilModel();
        
        // IMPORTANTE: Filtramos por el usuario de la sesión maestra
        $usuario_id = $session->get('usuario_id');
        $datos['perfiles'] = $modelo->where('usuario_id', $usuario_id)->findAll();
        
        return view('perfiles/seleccion', $datos);
    }

    // 2. Crear un perfil amarrado a la cuenta maestra
    public function crear()
    {
        $session = session();
        $modelo = new PerfilModel();
        
        // Validación de límite de 6 perfiles (Regla de negocio)
        $usuario_id = $session->get('usuario_id');
        $cantidad = $modelo->where('usuario_id', $usuario_id)->countAllResults();

        if ($cantidad >= 6) {
            return redirect()->to(base_url('perfiles'))->with('error', 'Límite alcanzado');
        }

        $nuevoPerfil = [
            'nombre'     => $this->request->getPost('nombre'),
            'pin'        => $this->request->getPost('pin'),
            'avatar'     => $this->request->getPost('avatar'),
            'usuario_id' => $usuario_id // <--- AQUÍ conectamos el perfil con el dueño de la cuenta
        ];
        
        $modelo->insert($nuevoPerfil);

        return redirect()->to(base_url('perfiles'));
    }

    // 3. Selección y verificación (se mantienen igual pero con limpieza)
    public function seleccionar($id)
    {
        $modelo = new PerfilModel();
        $datos['perfil'] = $modelo->find($id);
        return view('perfiles/login_pin', $datos);
    }

    public function verificar_pin()
    {
        $session = session();
        $modelo = new PerfilModel();
        
        $id = $this->request->getPost('id');
        $pin_ingresado = $this->request->getPost('pin');
        
        $perfil = $modelo->find($id);

        if ($perfil && $perfil->pin == $pin_ingresado) {
            $session->set([
                'perfil_id'     => $perfil->id,
                'perfil_nombre' => $perfil->nombre,
                'logueado_perfil' => true // Usamos un nombre distinto para no chocar con el login maestro
            ]);
            return redirect()->to(base_url('tareas'));
        } else {
            return redirect()->to(base_url('perfiles'))->with('error', 'PIN incorrecto');
        }
    }

    public function salir()
    {
        // Al "salir", solo quitamos los datos del PERFIL, para volver a la selección
        $session = session();
        $session->remove(['perfil_id', 'perfil_nombre', 'logueado_perfil']);
        return redirect()->to(base_url('perfiles'));
    }

    public function eliminar($id)
    {
        $modelo = new PerfilModel();
        $modelo->delete($id);
        return redirect()->to(base_url('perfiles'));
    }
}