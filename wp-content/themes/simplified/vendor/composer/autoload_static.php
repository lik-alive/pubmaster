<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7f4495e54e13ba59d84196989515385e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPHtmlParser\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPHtmlParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/paquettg/php-html-parser/src/PHPHtmlParser',
        ),
    );

    public static $prefixesPsr0 = array (
        's' => 
        array (
            'stringEncode' => 
            array (
                0 => __DIR__ . '/..' . '/paquettg/string-encode/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7f4495e54e13ba59d84196989515385e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7f4495e54e13ba59d84196989515385e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit7f4495e54e13ba59d84196989515385e::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}