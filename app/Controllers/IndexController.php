<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class IndexController extends AbstractController
{
    public function index()
    {
        $method = $this->request->getMethod();
        $user = $this->request->input('user', 'Wang system');
        return [
            'method' => $method,
	        'message' => "Hello {$user}.",
        ];
    }
}
