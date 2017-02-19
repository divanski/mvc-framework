<?php
/**
 * Created by Ivan Zdravkov.
 * User: divanski
 * Email: izdravkov@victobox.com
 * Date: 19-Feb-17
 * Time: 15:13
 */

namespace src;

class Config
{
    /**
     * Singleton DP - make only one instance
     * @var null
     */
    private static $_instance = null;
    /**
     * @var array
     */
    private $_configArray = [];
    /**
     * @var null
     */
    private $_configFolder = null;

    /**
     * Singleton DP - stop create new instance
     * Config constructor.
     */
    private function __construct(){}

    public function setConfigFolder($configFolder)
    {
        if(!$configFolder){
            throw new \Exception('Empty config folder path');
        }

        $_configFolder = realpath($configFolder);

        if($_configFolder != false && is_dir($_configFolder) && is_readable($_configFolder)){
            $this->_configArray = []; // This clear old config data
            $this->_configFolder = $_configFolder . DIRECTORY_SEPARATOR;
//            $ns = $this->app['namespaces'];
//            if(is_array($ns)){
//                foreach ($ns as $k => $v)
//                {
//                    var_dump(Loader::registerNamespace($k, $v));
//                }
//            }
        }else{
            throw new \Exception('Config directory read error: ' . $configFolder);
        }
    }

    public function getConfigFolder()
    {
        return $this->_configFolder;
    }

    public function includeConfigFile($path)
    {
        if(!$path){
            //TODO Central Exception Handler
            throw new \Exception('Path is empty');
        }
        $_file = realpath($path);
        if($_file != null && is_file($_file) && is_readable($_file)){
            $_basename = explode('.php', basename($_file))[0];
            $this->_configArray[$_basename] = include $_file;
        }else{
            //TODO Central Exception Handler
            throw new \Exception('Config file read error: ' . $path);
        }
    }

    public function __get($name)
    {
        if(!isset($this->_configArray[$name])){
            $this->includeConfigFile($this->_configFolder . $name . '.php');
        }
        if(array_key_exists($name, $this->_configArray)){
            return $this->_configArray[$name];
        }
        return null;
    }

    /**
     * Create only one Instance
     * @return Config|null
     */
    public static function getInstance()
    {
        if(self::$_instance == null){
            self::$_instance = new Config();
        }
        return self::$_instance;
    }
}