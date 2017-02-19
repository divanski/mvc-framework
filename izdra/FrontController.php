<?php
/**
 * Created by Ivan Zdravkov.
 * User: izdra
 * Email: izdravkov@victobox.com
 * Date: 19-Feb-17
 * Time: 18:55
 */

namespace izdra;

use izdra\Routers\DefaultRouter;

class FrontController
{
    private static $_instance = null;

    private function __construct(){}

    public function dispatch()
    {
        $a = new DefaultRouter();
        $a->parse();
    }

    /**
     * Create only one instance of FrontController
     * @return FrontController|null
     */
    public static function getInstance()
    {
        if(self::$_instance == null){
            self::$_instance = new FrontController();
        }
        return self::$_instance;
    }
}