<?php
namespace Adive;

class Procedures extends \Adive\Kernel
{
    /**
     * @var resource
     */
    protected $autoloadPath;
    
    /**
     * @var resource
     */
    public $routingPath;
    
    /**
     * @var resource
     */
    protected $configPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->autoloadPath = 'Procedures/Autoload.php';
        $this->routingPath = 'Procedures/Routing.php';
        $this->configPath = 'Config/Config.php';
        $this->load();
        $this->loadConfig();
        $this->loadRouting();
    }

    /**
     * AUTOLOAD of Procedures
     */
    public function load()
    {
        return require_once $this->autoloadPath;
    }
    
    /**
     * LOAD Configuration
     */
    public static function loadConfig()
    {
        return 'Adive/Config/Config.php';
    }
    
    /**
     * LOAD WEB of Procedures
     */
    public static function loadRouting()
    {
        return 'Adive/Procedures/Routing.php';
    }
    
    public function call()
    {
        $this->next->call();
    }
}
