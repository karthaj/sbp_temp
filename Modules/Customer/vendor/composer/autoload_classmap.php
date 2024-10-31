<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\Customer\\Database\\Seeders\\CustomerDatabaseSeeder' => $baseDir . '/Database/Seeders/CustomerDatabaseSeeder.php',
    'Modules\\Customer\\Emails\\VerificationEmail' => $baseDir . '/Emails/VerificationEmail.php',
    'Modules\\Customer\\Entities\\Address' => $baseDir . '/Entities/Address.php',
    'Modules\\Customer\\Entities\\Customer' => $baseDir . '/Entities/Customer.php',
    'Modules\\Customer\\Entities\\Group' => $baseDir . '/Entities/Group.php',
    'Modules\\Customer\\Entities\\GroupReduction' => $baseDir . '/Entities/GroupReduction.php',
    'Modules\\Customer\\Entities\\Guest' => $baseDir . '/Entities/Guest.php',
    'Modules\\Customer\\Events\\Auth\\CustomerRegistered' => $baseDir . '/Events/Auth/CustomerRegistered.php',
    'Modules\\Customer\\Http\\Controllers\\AddressController' => $baseDir . '/Http/Controllers/AddressController.php',
    'Modules\\Customer\\Http\\Controllers\\CustomerController' => $baseDir . '/Http/Controllers/CustomerController.php',
    'Modules\\Customer\\Http\\Controllers\\DataTable\\AddressController' => $baseDir . '/Http/Controllers/DataTable/AddressController.php',
    'Modules\\Customer\\Http\\Controllers\\DataTable\\CustomerController' => $baseDir . '/Http/Controllers/DataTable/CustomerController.php',
    'Modules\\Customer\\Http\\Controllers\\DataTable\\GroupController' => $baseDir . '/Http/Controllers/DataTable/GroupController.php',
    'Modules\\Customer\\Http\\Controllers\\GroupController' => $baseDir . '/Http/Controllers/GroupController.php',
    'Modules\\Customer\\Http\\Middleware\\ChecksExpiredVerificationTokens' => $baseDir . '/Http/Middleware/ChecksExpiredVerificationTokens.php',
    'Modules\\Customer\\Http\\Requests\\Customer\\Address\\AddressEditFormRequest' => $baseDir . '/Http/Requests/Customer/Address/AddressEditFormRequest.php',
    'Modules\\Customer\\Http\\Requests\\Customer\\Address\\AddressFormRequest' => $baseDir . '/Http/Requests/Customer/Address/AddressFormRequest.php',
    'Modules\\Customer\\Http\\Requests\\Customer\\CustomerEditFormRequest' => $baseDir . '/Http/Requests/Customer/CustomerEditFormRequest.php',
    'Modules\\Customer\\Http\\Requests\\Customer\\CustomerFormRequest' => $baseDir . '/Http/Requests/Customer/CustomerFormRequest.php',
    'Modules\\Customer\\Http\\Requests\\Customer\\Group\\GroupFormRequest' => $baseDir . '/Http/Requests/Customer/Group/GroupFormRequest.php',
    'Modules\\Customer\\Listeners\\Auth\\SendVerificationEmail' => $baseDir . '/Listeners/Auth/SendVerificationEmail.php',
    'Modules\\Customer\\Providers\\CustomerServiceProvider' => $baseDir . '/Providers/CustomerServiceProvider.php',
);