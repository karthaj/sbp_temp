<?php

namespace Shopbox\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Shopbox\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Shopbox\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Shopbox\Http\Middleware\VerifyCsrfToken::class,
        ],

        'tenant' => [
            \Shopbox\Http\Middleware\Tenant\Tenant::class,
            \Shopbox\Http\Middleware\Tenant\Config::class,
            \Shopbox\Http\Middleware\Tenant\Freeze::class,
            \Shopbox\Http\Middleware\GenerateMenus::class
        ],

        'shop' => [
            \Shopbox\Http\Middleware\Store\Shop::class,
            \Shopbox\Http\Middleware\Store\Freeze::class,
            \Shopbox\Http\Middleware\Store\Config::class,
            \Shopbox\Http\Middleware\Store\Digest::class,
            \Shopbox\Http\Middleware\Store\GlobalVariables::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Shopbox\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'permission' => \Shopbox\Http\Middleware\PermissionMiddleware::class,
        'confirmation_token.expired' => \Shopbox\Http\Middleware\ChecksExpiredConfirmationTokens::class,
        'verification_token.expired' => \Modules\Customer\Http\Middleware\ChecksExpiredVerificationTokens::class,
        'menu' => \Shopbox\Http\Middleware\GenerateMenus::class,
        'checkout' => \Shopbox\Http\Middleware\Store\Checkout::class,
        'account.status' => \Shopbox\Http\Middleware\CheckAccountStatus::class,
        'account' => \Shopbox\Http\Middleware\Store\Account::class,
        'analytics' => \Shopbox\Http\Middleware\Analytics::class,
        'agreement' => \Shopbox\Http\Middleware\Store\Agreement::class,
        'has.billing.address' => \Shopbox\Http\Middleware\Tenant\CheckBillingAddressFilled::class,
        'account.limts' => \Shopbox\Http\Middleware\Tenant\CheckAccountLimits::class,
        'auth.subdomain' =>  \Shopbox\Http\Middleware\Tenant\AuthSubdomain::class,
        'plan.switch' =>  \Shopbox\Http\Middleware\Tenant\BlockChangePlanOnExpiry::class,
    ];
}
