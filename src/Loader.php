<?php
/**
 * Created by Ivan Zdravkov.
 * User: divanski
 * Email: izdravkov@victobox.com
 * Date: 19-Feb-17
 * Time: 13:22
 */

namespace src;
/**
 * Class Loader
 * @package src
 */
final class Loader
{
    private static $namespaces = [];

    private function __construct(){}

    /**
     * Split Autoload register
     */
    public static function registerAutoLoad()
    {
        spl_autoload_register(['\src\Loader', 'autoload']);
    }

    /**
     * Autoload class
     * @param $class
     */
    public static function autoload($class)
    {
        self::loadClass($class);
    }

    /**
     * Load Class
     * @param $class
     * @throws \Exception
     */
    private function loadClass($class)
    {
        foreach (self::$namespaces as $k => $v)
        {
            if(strpos($class, $k) === 0){
                $file = realpath(substr_replace(str_replace('\\',DIRECTORY_SEPARATOR,$class),$v,0,strlen($k)).'.php');
                if($file && is_readable($file)){
                    include $file;
                }else{
                    //TODO Central Exception Handler
                    throw new \Exception("File can't be included: " . $file);
                }
                break;
            }
        }
    }

    /**
     * Register Namespace
     * @param $namespace
     * @param $path
     * @throws \Exception
     */
    public static function registerNamespace($namespace, $path)
    {
        $namespace = trim($namespace);
        if(strlen($namespace) > 0){
            if(!$path){
                //TODO Central Exception Handler
                throw new \Exception('Invalid path');
            }

            $_path = realpath($path);

            if($_path && is_dir($_path) && is_readable($_path)){
                self::$namespaces[$namespace . '\\'] = $_path . DIRECTORY_SEPARATOR;
            }else{
                //TODO Central Exception Handler
                throw new \Exception('Namespace directory read error ' . $path);
            }
        }else{
            //TODO Central Exception Handler
            throw new \Exception('Invalid namespace: ' . $namespace);
        }
    }

    /**
     * Get All Namespaces
     * @return array
     */
    public static function getNamespaces()
    {
        return self::$namespaces;
    }

    /**
     * Remove Namespace on Namespaces Array
     * @param $namespace
     */
    public static function removeNamespaces($namespace)
    {
        unset(self::$namespaces[$namespace]);
    }

    /**
     * Clear all registered Namespace
     */
    public static function clearNamespaces()
    {
        self::$namespaces = [];
    }

}