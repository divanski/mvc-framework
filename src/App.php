<?php
/**
 * Created by Ivan Zdravkov.
 * User: divanski
 * Email: izdravkov@victobox.com
 * Date: 19-Feb-17
 * Time: 13:22
 */
namespace src;
include 'Loader.php';

/**
 * Class App - Starting point for Application
 * @package src
 */
class App
{
    /**
     * Singleton DP - make only one instance
     * @var null
     */
    private static $_instance = null;
    /**
     * Instance of Config class
     * @var Config|null
     */
    private $_config = null;

    /**
     * Instance of FrontController
     * @var null
     */
    private $_frontController = null;

    /**
     * Singleton DP - stop create new instance,
     * register Namespace and run autoload
     * App constructor.
     */
    private function __construct()
    {
        Loader::registerNamespace('src', dirname(__FILE__).DIRECTORY_SEPARATOR);
        Loader::registerAutoLoad();
        $this->_config = Config::getInstance();
    }

    /**
     * Set configuration folder
     * @param $path
     */
    public function setConfigFolder($path)
    {
        $this->_config->setConfigFolder($path);
    }


    /**
     * Run the App
     * 1. Set Configuration folder
     * 2. Dispatch FrontController
     */
    public function run()
    {
        if($this->_config->getConfigFolder() == null)
        {
            $this->setConfigFolder('../config');
        }
        $this->_frontController = FrontController::getInstance();
        $this->_frontController->dispatch();
    }

    /**
     * @return Config|null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Create only one Instance
     * @return \src\App
     */
    public static function getInstance()
    {
        if(self::$_instance == null){
            self::$_instance = new App();
        }
        return self::$_instance;
    }
}