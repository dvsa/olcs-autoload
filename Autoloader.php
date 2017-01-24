<?php

/**
 * Unfortunately Composer doesn't make it easy to extend it's autoloader
 *  so here we duplicate a bit of their code
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Autoloader
{
    private static $loader;

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        $vendorDir = __DIR__ . '/../../';

        self::$loader = $loader = new \Olcs\Autoload\ClassLoader();

        // Include paths only gets created when applicable
        if (file_exists($vendorDir . '/composer/include_paths.php')) {
            $includePaths = require($vendorDir . '/composer/include_paths.php');
            array_push($includePaths, get_include_path());
            set_include_path(join(PATH_SEPARATOR, $includePaths));
        }

        $map = require($vendorDir . '/composer/autoload_namespaces.php');
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }

        $map = require($vendorDir . '/composer/autoload_psr4.php');
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        if (file_exists(__DIR__ . '/../../../data/autoload/classmap.php')) {
            $dynamicClassMap = require(__DIR__ . '/../../../data/autoload/classmap.php');
            if ($dynamicClassMap) {
                $loader->addDynamicClassMap($dynamicClassMap);
            }
        }

        $classMap = require($vendorDir . '/composer/autoload_classmap.php');
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        if (file_exists($vendorDir . '/composer/autoload_files.php')) {
            $includeFiles = require $vendorDir . '/composer/autoload_files.php';
            foreach ($includeFiles as $file) {
                require_once($file);
            }
        }

        return $loader;
    }
}
