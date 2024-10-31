<?php

namespace Shopbox\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/response',
        '/logout',
        '/merchant/logout',
        '/checkout/auth/customer',
        '/merchant/admin/response',
        '/merchant/admin/client/response',
        '/merchant/image/upload',
        '/merchant/imageupload',
        '/merchant/image/manager',
        '/store/password',
    ];
}
