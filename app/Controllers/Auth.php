<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    // --- 1. ESTO ES LO QUE FALTABA: Muestra la vista del Login ---
    public function login()
    {
        // Si ya está logueado, lo mandamos directo a perfiles
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('perfiles'));
        }
        return view('auth/login');
    }

    public function registro()
    {
        return view('auth/registro');
    }

    public function postRegistrar()
    {
        $validation = \Config\Services::validation();
        $modelo = new \App\Models\UsuarioModel(); 
        
        $correo = $this->request->getPost('correo');

        // 1. Verificamos duplicado manualmente
        $usuarioExistente = $modelo->where('correo', $correo)->first();
        if ($usuarioExistente) {
            return redirect()->back()->withInput()->with('error', 'Este correo ya está registrado en TaskFlix.');
        }

        // 2. Reglas de Validación nativas
        $rules = [
            'username' => 'required|min_length[3]',
            'correo'   => [
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'El correo es obligatorio.',
                    'valid_email' => 'El formato del correo no es válido.'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[8]|regex_match[/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/]',
                'errors' => [
                    'min_length'  => 'La contraseña debe tener al menos 8 caracteres.',
                    'regex_match' => 'La contraseña debe incluir al menos una mayúscula, una minúscula y un número.'
                ]
            ],
            'confirm_password' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        // 3. Validación de Dominios (Gmail/Hotmail)
        $permitidos = ['@gmail.com', '@hotmail.com', '@outlook.com'];
        $dominioValido = false;

        foreach ($permitidos as $d) {
            if (str_contains(strtolower($correo), $d)) {
                $dominioValido = true;
                break;
            }
        }

        if (!$dominioValido) {
            return redirect()->back()->withInput()->with('error', 'Solo se permiten correos de Gmail, Hotmail o Outlook.');
        }

        // 4. Si todo está bien, guardamos
        $modelo->insert([
            'username' => $this->request->getPost('username'),
            'correo'   => $correo,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
        ]);

        return redirect()->to(base_url('login'))->with('mensaje', '¡Cuenta de TaskFlix creada con éxito!');
    }

    public function postLogin()
    {
        $session = session();
        $modelo = new UsuarioModel();
        
        $correo = $this->request->getPost('correo');
        $password = $this->request->getPost('password');

        $usuario = $modelo->where('correo', $correo)->first();

        if ($usuario && password_verify($password, $usuario->password)) {
            $session->set([
                'usuario_id' => $usuario->id,
                'username'   => $usuario->username, 
                'correo'     => $usuario->correo,
                'isLoggedIn' => true
            ]);
            return redirect()->to(base_url('perfiles'));
        } else {
            return redirect()->to(base_url('login'))->with('error', 'Datos incorrectos');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}