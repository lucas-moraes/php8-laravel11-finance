<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * As rotas que devem ser excluídas da verificação CSRF.
     *
     * @var array
     */
    protected $except = [
        '/doc',
        '/movement/getAll',
        '/movement/filter',
        '/movement/create',
        '/movement/*',
        '/category/getAll',
        '/category/create',
    ];
}
