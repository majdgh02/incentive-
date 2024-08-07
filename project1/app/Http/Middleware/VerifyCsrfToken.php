<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/login',
        'out',
        '/password/change' ,
        '/target/put',
        '/employee/create' ,
        '/employee/update' ,
        '/employee/update/status' ,
        '/employee/delete' ,
        '/employee/get',
        '/callnum',
        '/callnum/delete',
        '/callnum/get',
        '/probtic',
        '/probtic/delete',
        '/probtic/get',
        '/follow',
        '/follow/delete',
        '/follow/get',
        '/callquality',
        '/callquality/delete',
        '/callquality/get',
        '/commitment',
        '/commitment/delete',
        '/commitment/get',
        '/acceptancerate',
        '/acceptancerate/delete',
        '/acceptancerate/get',
        '/callreturn',
        '/callreturn/delete',
        '/callreturn/get',
        '/suggestion',
        'suggestion/delete',
        'suggestion/get',
        '/evaluation',
        '/evaluation/update',
        '/evaluation/delete',
        '/evaluation/get',
        'puttargetmaneger',
        'puttargetmaneger/delete',
        'puttargetmaneger/get',
    ];
}
