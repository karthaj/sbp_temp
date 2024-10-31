<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit305801428fe9f1545183b674f814e341
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\StockManager\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\StockManager\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\StockManager\\Database\\Seeders\\StockManagerDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/StockManagerDatabaseSeeder.php',
        'Modules\\StockManager\\Http\\Controllers\\StockManagerController' => __DIR__ . '/../..' . '/Http/Controllers/StockManagerController.php',
        'Modules\\StockManager\\Providers\\StockManagerServiceProvider' => __DIR__ . '/../..' . '/Providers/StockManagerServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit305801428fe9f1545183b674f814e341::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit305801428fe9f1545183b674f814e341::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit305801428fe9f1545183b674f814e341::$classMap;

        }, null, ClassLoader::class);
    }
}