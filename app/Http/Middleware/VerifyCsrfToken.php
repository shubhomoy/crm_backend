<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/auth/admin/signup',
        '/auth/customer/signup',
        '/auth/admin/signin',
        '/auth/customer/signin',
        '/c/contract',
        '/a/contract/*',
        '/c/contract/email/query/*',
        '/a/sendmail',
        '/a/maillist/create',
        '/a/maillist/send/*'
    ];
}
