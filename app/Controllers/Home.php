<?php

namespace App\Controllers;


class Home extends BaseController
{
    public function getindex(): string
    {
        return view('welcome_message');
    }
}
