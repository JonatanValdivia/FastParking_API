<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf3e4333e46c71d5ac0df316701762b0c
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf3e4333e46c71d5ac0df316701762b0c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf3e4333e46c71d5ac0df316701762b0c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf3e4333e46c71d5ac0df316701762b0c::$classMap;

        }, null, ClassLoader::class);
    }
}
