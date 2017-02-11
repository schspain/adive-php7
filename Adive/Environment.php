<?php
namespace Adive;

class Environment implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var \Adive\Environment
     */
    protected static $environment;

    /**
     * Get environment instance (singleton)
     *
     * This creates and/or returns an environment instance (singleton)
     * derived from $_SERVER variables. You may override the global server
     * variables by using `\Adive\Environment::mock()` instead.
     *
     * @param  bool             $refresh Refresh properties using global server variables?
     * @return \Adive\Environment
     */
    public static function getInstance($refresh = false)
    {
        if (is_null(self::$environment) || $refresh) {
            self::$environment = new self();
        }

        return self::$environment;
    }

    /**
     * Get mock environment instance
     *
     * @param  array       $userSettings
     * @return \Adive\Environment
     */
    public static function mock($userSettings = array())
    {
        $defaults = array(
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '',
            'PATH_INFO' => '',
            'QUERY_STRING' => '',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 80,
            'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
            'ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
            'USER_AGENT' => 'Adive Framework',
            'REMOTE_ADDR' => '127.0.0.1',
            'adive.url_scheme' => 'http',
            'adive.input' => '',
            'adive.errors' => @fopen('php://stderr', 'w')
        );
        self::$environment = new self(array_merge($defaults, $userSettings));

        return self::$environment;
    }

    /**
     * Constructor (private access)
     *
     * @param  array|null $settings If present, these are used instead of global server variables
     */
    private function __construct($settings = null)
    {
        if ($settings) {
            $this->properties = $settings;
        } else {
            $env = array();

            //The HTTP request method
            $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];

            //The IP
            $env['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

            // Server params
            $scriptName = $_SERVER['SCRIPT_NAME']; // <-- "/foo/index.php"
            $requestUri = $_SERVER['REQUEST_URI']; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
            $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; // <-- "test=abc" or ""

            // Physical path
            if (strpos($requestUri, $scriptName) !== false) {
                $physicalPath = $scriptName; // <-- Without rewriting
            } else {
                $physicalPath = str_replace('\\', '', dirname($scriptName)); // <-- With rewriting
            }
            $env['SCRIPT_NAME'] = rtrim($physicalPath, '/'); // <-- Remove trailing slashes

            // Virtual path
            $env['PATH_INFO'] = substr_replace($requestUri, '', 0, strlen($physicalPath)); // <-- Remove physical path
            $env['PATH_INFO'] = str_replace('?' . $queryString, '', $env['PATH_INFO']); // <-- Remove query string
            $env['PATH_INFO'] = '/' . ltrim($env['PATH_INFO'], '/'); // <-- Ensure leading slash

            // Query string (without leading "?")
            $env['QUERY_STRING'] = $queryString;

            //Name of server host that is running the script
            $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

            //Number of server port that is running the script
            $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];

            //HTTP request headers (retains HTTP_ prefix to match $_SERVER)
            $headers = \Adive\Http\Headers::extract($_SERVER);
            foreach ($headers as $key => $value) {
                $env[$key] = $value;
            }

            //Is the application running under HTTPS or HTTP protocol?
            $env['adive.url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

            //Input stream (readable one time only; not available for multipart/form-data requests)
            $rawInput = @file_get_contents('php://input');
            if (!$rawInput) {
                $rawInput = '';
            }
            $env['adive.input'] = $rawInput;

            //Error stream
            $env['adive.errors'] = @fopen('php://stderr', 'w');

            $this->properties = $env;
        }
    }

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset)
    {
        if (isset($this->properties[$offset])) {
            return $this->properties[$offset];
        } else {
            return null;
        }
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value)
    {
        $this->properties[$offset] = $value;
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }

    /**
     * IteratorAggregate
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }
}
