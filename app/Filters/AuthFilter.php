<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no existe 'usuario_id' en la sesión, rebotar al login
        if (!session()->get('usuario_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debes iniciar sesión primero');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}