<?php
namespace Adive;

// Ensure mcrypt constants are defined even if mcrypt extension is not loaded
if (!extension_loaded('mcrypt')) {
    define('MCRYPT_MODE_CBC', 0);
    define('MCRYPT_RIJNDAEL_256', 0);
}

class Adive
{
    /**
     * @const string
     */
    const VERSION = '2.0.4';

    /**
     * @var \Adive\Security\Set
     */
    public $container;

    /**
     * @var array[\Adive]
     */
    protected static $APIs = array();

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $kernel;

    /**
     * @var mixed Callable to be invoked if application error
     */
    protected $error;

    /**
     * @var mixed Callable to be invoked if no matching routes are found
     */
    protected $notFound;

    /**
     * @var array
     */
    protected $hooks = array(
        'adive.before' => array(array()),
        'adive.before.router' => array(array()),
        'adive.before.dispatch' => array(array()),
        'adive.after.dispatch' => array(array()),
        'adive.after.router' => array(array()),
        'adive.after' => array(array())
    );

    /********************************************************************************
    * Instantiation and Configuration
    *******************************************************************************/

    /**
     * Constructor
     * @param  array $userSettings Associative array of application settings
     */
    public function __construct(array $userSettings = array())
    {
        // Setup IoC container
        $this->container = new \Adive\Security\Set();
        $this->container['settings'] = array_merge(static::getDefaultSettings(), $userSettings);

        // Default environment
        $this->container->singleton('environment', function ($c) {
            return \Adive\Environment::getInstance();
        });

        // Default request
        $this->container->singleton('request', function ($c) {
            return new \Adive\Http\Request($c['environment']);
        });

        // Default response
        $this->container->singleton('response', function ($c) {
            return new \Adive\Http\Response();
        });

        // Default router
        $this->container->singleton('router', function ($c) {
            return new \Adive\Router();
        });

        // Default view
        $this->container->singleton('view', function ($c) {
            $viewClass = $c['settings']['view'];
            $templatesPath = $c['settings']['templates.path'];

            $view = ($viewClass instanceOf \Adive\View) ? $viewClass : new $viewClass;
            $view->setTemplatesDirectory($templatesPath);
            return $view;
        });

        // Default log writer
        $this->container->singleton('logWriter', function ($c) {
            $logWriter = $c['settings']['log.writer'];

            return is_object($logWriter) ? $logWriter : new \Adive\LogWriter($c['environment']['adive.errors']);
        });

        // Default log
        $this->container->singleton('log', function ($c) {
            $log = new \Adive\Log($c['logWriter']);
            $log->setEnabled($c['settings']['log.enabled']);
            $log->setLevel($c['settings']['log.level']);
            $env = $c['environment'];
            $env['adive.log'] = $log;

            return $log;
        });

        // Default mode
        $this->container['mode'] = function ($c) {
            $mode = $c['settings']['mode'];

            if (isset($_ENV['SLIM_MODE'])) {
                $mode = $_ENV['SLIM_MODE'];
            } else {
                $envMode = getenv('SLIM_MODE');
                if ($envMode !== false) {
                    $mode = $envMode;
                }
            }

            return $mode;
        };

        // Define default kernel stack
        $this->kernel = array($this);
        $this->add(new \Adive\Kernel\Flash());
        $this->add(new \Adive\Kernel\MethodOverride());
        $this->add(new \Adive\Procedures());
        $this->add(new \Adive\RestAuth());

        // Make default if first instance
        if (is_null(static::getInstance())) {
            $this->setName('default');
        }
    }
    
        /**
     * PSR-0 autoloader
     */
    public static function autoload($className)
    {
        $thisClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);

        $baseDir = __DIR__;

        if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        }

        $className = ltrim($className, '\\');
        $fileName  = $baseDir;
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)) {
            require $fileName;
        }
    }

    /**
     * Register Adive's PSR-0 autoloader
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . "\\Adive::autoload");
    }
    
    /**
     * Adive ENV Procedures
     */
    public function __get($name)
    {
        //pathActive($name);
        return $this->container[$name];
    }

    public function __set($name, $value)
    {
        $this->container[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->container[$name]);
    }

    public function __unset($name)
    {
        unset($this->container[$name]);
    }

    /**
     * Get application instance by name
     * @param  string    $name The name of the application
     * @return \Adive\Adive|null
     */
    public static function getInstance($name = 'default')
    {
        return isset(static::$APIs[$name]) ? static::$APIs[$name] : null;
    }

    /**
     * Set Adive application name
     * @param  string $name The name of this Adive application
     */
    public function setName($name)
    {
        $this->name = $name;
        static::$APIs[$name] = $this;
    }

    /**
     * Get Adive application name
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get default application settings
     * @return array
     */
    public static function getDefaultSettings()
    {
        return array(
            // Application
            'mode' => 'development',
            // Debugging
            'debug' => true,
            // Logging
            'log.writer' => null,
            'log.level' => \Adive\Log::DEBUG,
            'log.enabled' => true,
            // View
            'templates.path' => 'Views',
            'view' => '\Adive\View',
            // Cookies
            'cookies.encrypt' => false,
            'cookies.lifetime' => '20 minutes',
            'cookies.path' => '/',
            'cookies.domain' => null,
            'cookies.secure' => false,
            'cookies.httponly' => false,
            // Encryption
            'cookies.secret_key' => 'CHANGE_ME',
            'cookies.cipher' => MCRYPT_RIJNDAEL_256,
            'cookies.cipher_mode' => MCRYPT_MODE_CBC,
            // HTTP
            'http.version' => '1.1',
            // Routing
            'routes.case_sensitive' => true
        );
    }

    /**
     * Adive Configurator
     *
     * @param  string|array $name  If a string, the name of the setting to set or retrieve. Else an associated array of setting names and values
     * @param  mixed        $value If name is a string, the value of the setting identified by $name
     * @return mixed        The value of a setting if only one argument is a string
     */
    public function config($name, $value = null)
    {
        $c = $this->container;
        
        if (is_array($name)) {
            if (true === $value) {
                $c['settings'] = array_merge_recursive($c['settings'], $name);
            } else {
                $c['settings'] = array_merge($c['settings'], $name);
            }
            
            $_SESSION['templates.path']=$c['settings']['templates.path'];
            
        } elseif (func_num_args() === 1) {
            return isset($c['settings'][$name]) ? $c['settings'][$name] : null;
        } else {
            $settings = $c['settings'];
            $settings[$name] = $value;
            $c['settings'] = $settings;
        }
    }

    /********************************************************************************
    * Application Modes
    *******************************************************************************/

    /**
     * Get application mode
     *
     * This method determines the application mode. It first inspects the $_ENV
     * superglobal for key `SLIM_MODE`. If that is not found, it queries
     * the `getenv` function. Else, it uses the application `mode` setting.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Configure Adive for a given mode
     *
     * This method will immediately invoke the callable if
     * the specified mode matches the current application mode.
     * Otherwise, the callable is ignored. This should be called
     * only _after_ you initialize your Adive app.
     *
     * @param  string $mode
     * @param  mixed  $callable
     * @return void
     */
    public function configureMode($mode, $callable)
    {
        if ($mode === $this->getMode() && is_callable($callable)) {
            call_user_func($callable);
        }
    }

    /********************************************************************************
    * Logging
    *******************************************************************************/

    /**
     * Get application log
     * @return \Adive\Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /********************************************************************************
    * Routing
    *******************************************************************************/

    /**
     * Add GET|POST|PUT|PATCH|DELETE route
     *
     * Adds a new route to the router with associated callable. This
     * route will only be invoked when the HTTP request's method matches
     * this route's method.
     *
     * ARGUMENTS:
     *
     * First:       string  The URL pattern (REQUIRED)
     * In-Between:  mixed   Anything that returns TRUE for `is_callable` (OPTIONAL)
     * Last:        mixed   Anything that returns TRUE for `is_callable` (REQUIRED)
     *
     * The first argument is required and must always be the
     * route pattern (ie. '/books/{id}').
     *
     * The last argument is required and must always be the callable object
     * to be invoked when the route matches an HTTP request.
     *
     * You may also provide an unlimited number of in-between arguments;
     * each interior argument must be callable and will be invoked in the
     * order specified before the route's callable is invoked.
     *
     * USAGE:
     *
     * Adive::get('/foo'[, kernel, kernel, ...], callable);
     *
     * @param   array (See notes above)
     * @return  \Adive\Route
     */
    protected function mapRoute($args)
    {
        $pattern = array_shift($args);
        $callable = array_pop($args);
        $route = new \Adive\Route($pattern, $callable, $this->settings['routes.case_sensitive']);
        $this->router->map($route);
        if (count($args) > 0) {
            $route->setKernel($args);
        }

        return $route;
    }

    /**
     * Add generic route without associated HTTP method
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function map()
    {
        $args = func_get_args();

        return $this->mapRoute($args);
    }

    /**
     * Add GET route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function get()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_GET, \Adive\Http\Request::METHOD_HEAD);
    }

    /**
     * Add POST route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function post()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_POST);
    }

    /**
     * Add PUT route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function put()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_PUT);
    }

    /**
     * Add PATCH route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function patch()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_PATCH);
    }

    /**
     * Add DELETE route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function delete()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_DELETE);
    }

    /**
     * Add OPTIONS route
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function options()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Adive\Http\Request::METHOD_OPTIONS);
    }

    /**
     * Route Groups
     *
     * This method accepts a route pattern and a callback all Route
     * declarations in the callback will be prepended by the group(s)
     * that it is in
     *
     * Accepts the same parameters as a standard route so:
     * (pattern, kernel1, kernel2, ..., $callback)
     */
    public function group()
    {
        $args = func_get_args();
        $pattern = array_shift($args);
        $callable = array_pop($args);
        $this->router->pushGroup($pattern, $args);
        if (is_callable($callable)) {
            call_user_func($callable);
        }
        $this->router->popGroup();
    }

    /*
     * Add route for any HTTP method
     * @see    mapRoute()
     * @return \Adive\Route
     */
    public function any()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via("ANY");
    }

    /**
     * Not Found Handler
     *
     * This method defines or invokes the application-wide Not Found handler.
     * There are two contexts in which this method may be invoked:
     *
     * 1. When declaring the handler:
     *
     * If the $callable parameter is not null and is callable, this
     * method will register the callable to be invoked when no
     * routes match the current HTTP request. It WILL NOT invoke the callable.
     *
     * 2. When invoking the handler:
     *
     * If the $callable parameter is null, Adive assumes you want
     * to invoke an already-registered handler. If the handler has been
     * registered and is callable, it is invoked and sends a 404 HTTP Response
     * whose body is the output of the Not Found handler.
     *
     * @param  mixed $callable Anything that returns true for is_callable()
     */
    public function notFound ($callable = null)
    {
        if (is_callable($callable)) {
            $this->notFound = $callable;
        } else {
            ob_start();
            if (is_callable($this->notFound)) {
                call_user_func($this->notFound);
            } else {
                call_user_func(array($this, 'defaultNotFound'));
            }
            $this->halt(404, ob_get_clean());
        }
    }

    /**
     * Error Handler
     *
     * This method defines or invokes the application-wide Error handler.
     * There are two contexts in which this method may be invoked:
     *
     * 1. When declaring the handler:
     *
     * If the $argument parameter is callable, this
     * method will register the callable to be invoked when an uncaught
     * Exception is detected, or when otherwise explicitly invoked.
     * The handler WILL NOT be invoked in this context.
     *
     * 2. When invoking the handler:
     *
     * If the $argument parameter is not callable, Adive assumes you want
     * to invoke an already-registered handler. If the handler has been
     * registered and is callable, it is invoked and passed the caught Exception
     * as its one and only argument. The error handler's output is captured
     * into an output buffer and sent as the body of a 500 HTTP Response.
     *
     * @param  mixed $argument Callable|\Exception
     */
    public function error($argument = null)
    {
        if (is_callable($argument)) {
            //Register error handler
            $this->error = $argument;
        } else {
            //Invoke error handler
            $this->response->status(500);
            $this->response->body('');
            $this->response->write($this->callErrorHandler($argument));
            $this->stop();
        }
    }

    /**
     * Call error handler
     *
     * This will invoke the custom or default error handler
     * and RETURN its output.
     *
     * @param  \Exception|null $argument
     * @return string
     */
    protected function callErrorHandler($argument = null)
    {
        ob_start();
        if (is_callable($this->error)) {
            call_user_func_array($this->error, array($argument));
        } else {
            call_user_func_array(array($this, 'defaultError'), array($argument));
        }

        return ob_get_clean();
    }

    /********************************************************************************
    * Application Accessors
    *******************************************************************************/

    /**
     * Get a reference to the Environment object
     * @return \Adive\Environment
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * Get the Request object
     * @return \Adive\Http\Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Get the Response object
     * @return \Adive\Http\Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get the Router object
     * @return \Adive\Router
     */
    public function router()
    {
        return $this->router;
    }

    /**
     * Get and/or set the View
     *
     * This method declares the View to be used by the Adive application.
     * If the argument is a string, Adive will instantiate a new object
     * of the same class. If the argument is an instance of View or a subclass
     * of View, Adive will use the argument as the View.
     *
     * If a View already exists and this method is called to create a
     * new View, data already set in the existing View will be
     * transferred to the new View.
     *
     * @param  string|\Adive\View $viewClass The name or instance of a \Adive\View subclass
     * @return \Adive\View
     */
    public function view($viewClass = null)
    {
        if (!is_null($viewClass)) {
            $existingData = is_null($this->view) ? array() : $this->view->getData();
            if ($viewClass instanceOf \Adive\View) {
                $this->view = $viewClass;
            } else {
                $this->view = new $viewClass();
            }
            $this->view->appendData($existingData);
            $this->view->setTemplatesDirectory($this->config('templates.path'));
        }

        return $this->view;
    }

    /********************************************************************************
    * Rendering
    *******************************************************************************/

    /**
     * Render a template
     *
     * Call this method within a GET, POST, PUT, PATCH, DELETE, NOT FOUND, or ERROR
     * callable to render a template whose output is appended to the
     * current HTTP response body. How the template is rendered is
     * delegated to the current View.
     *
     * @param  string $template The name of the template passed into the view's render() method
     * @param  array  $data     Associative array of data made available to the view
     * @param  int    $status   The HTTP response status code to use (optional)
     */
    public function render($template, $data = array(), $status = null)
    {
        if (!is_null($status)) {
            $this->response->status($status);
        }
        $this->view->appendData($data);
        $this->view->display($template);
    }

    /********************************************************************************
    * HTTP Caching
    *******************************************************************************/

    /**
     * Set Last-Modified HTTP Response Header
     *
     * Set the HTTP 'Last-Modified' header and stop if a conditional
     * GET request's `If-Modified-Since` header matches the last modified time
     * of the resource. The `time` argument is a UNIX timestamp integer value.
     * When the current request includes an 'If-Modified-Since' header that
     * matches the specified last modified time, the application will stop
     * and send a '304 Not Modified' response to the client.
     *
     * @param  int                       $time The last modified UNIX timestamp
     * @throws \InvalidArgumentException If provided timestamp is not an integer
     */
    public function lastModified($time)
    {
        if (is_integer($time)) {
            $this->response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s T', $time));
            if ($time === strtotime($this->request->headers->get('IF_MODIFIED_SINCE'))) {
                $this->halt(304);
            }
        } else {
            throw new \InvalidArgumentException('Adive::lastModified only accepts an integer UNIX timestamp value.');
        }
    }

    /**
     * Set ETag HTTP Response Header
     *
     * Set the etag header and stop if the conditional GET request matches.
     * The `value` argument is a unique identifier for the current resource.
     * The `type` argument indicates whether the etag should be used as a strong or
     * weak cache validator.
     *
     * When the current request includes an 'If-None-Match' header with
     * a matching etag, execution is immediately stopped. If the request
     * method is GET or HEAD, a '304 Not Modified' response is sent.
     *
     * @param  string                    $value The etag value
     * @param  string                    $type  The type of etag to create; either "strong" or "weak"
     * @throws \InvalidArgumentException If provided type is invalid
     */
    public function etag($value, $type = 'strong')
    {
        //Ensure type is correct
        if (!in_array($type, array('strong', 'weak'))) {
            throw new \InvalidArgumentException('Invalid Adive::etag type. Expected "strong" or "weak".');
        }

        //Set etag value
        $value = '"' . $value . '"';
        if ($type === 'weak') {
            $value = 'W/'.$value;
        }
        $this->response['ETag'] = $value;

        //Check conditional GET
        if ($etagsHeader = $this->request->headers->get('IF_NONE_MATCH')) {
            $etags = preg_split('@\s*,\s*@', $etagsHeader);
            if (in_array($value, $etags) || in_array('*', $etags)) {
                $this->halt(304);
            }
        }
    }

    /**
     * Set Expires HTTP response header
     *
     * The `Expires` header tells the HTTP client the time at which
     * the current resource should be considered stale. At that time the HTTP
     * client will send a conditional GET request to the server; the server
     * may return a 200 OK if the resource has changed, else a 304 Not Modified
     * if the resource has not changed. The `Expires` header should be used in
     * conjunction with the `etag()` or `lastModified()` methods above.
     *
     * @param string|int    $time   If string, a time to be parsed by `strtotime()`;
     *                              If int, a UNIX timestamp;
     */
    public function expires($time)
    {
        if (is_string($time)) {
            $time = strtotime($time);
        }
        $this->response->headers->set('Expires', gmdate('D, d M Y H:i:s T', $time));
    }

    /********************************************************************************
    * HTTP Cookies
    *******************************************************************************/

    /**
     * Set HTTP cookie to be sent with the HTTP response
     *
     * @param string     $name      The cookie name
     * @param string     $value     The cookie value
     * @param int|string $time      The duration of the cookie;
     *                                  If integer, should be UNIX timestamp;
     *                                  If string, converted to UNIX timestamp with `strtotime`;
     * @param string     $path      The path on the server in which the cookie will be available on
     * @param string     $domain    The domain that the cookie is available to
     * @param bool       $secure    Indicates that the cookie should only be transmitted over a secure
     *                              HTTPS connection to/from the client
     * @param bool       $httponly  When TRUE the cookie will be made accessible only through the HTTP protocol
     */
    public function setCookie($name, $value, $time = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $settings = array(
            'value' => $value,
            'expires' => is_null($time) ? $this->config('cookies.lifetime') : $time,
            'path' => is_null($path) ? $this->config('cookies.path') : $path,
            'domain' => is_null($domain) ? $this->config('cookies.domain') : $domain,
            'secure' => is_null($secure) ? $this->config('cookies.secure') : $secure,
            'httponly' => is_null($httponly) ? $this->config('cookies.httponly') : $httponly
        );
        $this->response->cookies->set($name, $settings);
    }

    /**
     * Get value of HTTP cookie from the current HTTP request
     *
     * Return the value of a cookie from the current HTTP request,
     * or return NULL if cookie does not exist. Cookies created during
     * the current request will not be available until the next request.
     *
     * @param  string      $name
     * @param  bool        $deleteIfInvalid
     * @return string|null
     */
    public function getCookie($name, $deleteIfInvalid = true)
    {
        // Get cookie value
        $value = $this->request->cookies->get($name);

        // Decode if encrypted
        if ($this->config('cookies.encrypt')) {
            $value = \Adive\Http\Util::decodeSecureCookie(
                $value,
                $this->config('cookies.secret_key'),
                $this->config('cookies.cipher'),
                $this->config('cookies.cipher_mode')
            );
            if ($value === false && $deleteIfInvalid) {
                $this->deleteCookie($name);
            }
        }

        return $value;
    }

    /**
     * DEPRECATION WARNING! Use `setCookie` with the `cookies.encrypt` app setting set to `true`.
     *
     * Set encrypted HTTP cookie
     *
     * @param string    $name       The cookie name
     * @param mixed     $value      The cookie value
     * @param mixed     $expires    The duration of the cookie;
     *                                  If integer, should be UNIX timestamp;
     *                                  If string, converted to UNIX timestamp with `strtotime`;
     * @param string    $path       The path on the server in which the cookie will be available on
     * @param string    $domain     The domain that the cookie is available to
     * @param bool      $secure     Indicates that the cookie should only be transmitted over a secure
     *                              HTTPS connection from the client
     * @param  bool     $httponly   When TRUE the cookie will be made accessible only through the HTTP protocol
     */
    public function setEncryptedCookie($name, $value, $expires = null, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        $this->setCookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    /**
     * DEPRECATION WARNING! Use `getCookie` with the `cookies.encrypt` app setting set to `true`.
     *
     * Get value of encrypted HTTP cookie
     *
     * Return the value of an encrypted cookie from the current HTTP request,
     * or return NULL if cookie does not exist. Encrypted cookies created during
     * the current request will not be available until the next request.
     *
     * @param  string       $name
     * @param  bool         $deleteIfInvalid
     * @return string|bool
     */
    public function getEncryptedCookie($name, $deleteIfInvalid = true)
    {
        return $this->getCookie($name, $deleteIfInvalid);
    }

    /**
     * Delete HTTP cookie (encrypted or unencrypted)
     *
     * Remove a Cookie from the client. This method will overwrite an existing Cookie
     * with a new, empty, auto-expiring Cookie. This method's arguments must match
     * the original Cookie's respective arguments for the original Cookie to be
     * removed. If any of this method's arguments are omitted or set to NULL, the
     * default Cookie setting values (set during Adive::init) will be used instead.
     *
     * @param string    $name       The cookie name
     * @param string    $path       The path on the server in which the cookie will be available on
     * @param string    $domain     The domain that the cookie is available to
     * @param bool      $secure     Indicates that the cookie should only be transmitted over a secure
     *                              HTTPS connection from the client
     * @param  bool     $httponly   When TRUE the cookie will be made accessible only through the HTTP protocol
     */
    public function deleteCookie($name, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $settings = array(
            'domain' => is_null($domain) ? $this->config('cookies.domain') : $domain,
            'path' => is_null($path) ? $this->config('cookies.path') : $path,
            'secure' => is_null($secure) ? $this->config('cookies.secure') : $secure,
            'httponly' => is_null($httponly) ? $this->config('cookies.httponly') : $httponly
        );
        $this->response->cookies->remove($name, $settings);
    }

    /********************************************************************************
    * Security Methods
    *******************************************************************************/

    /**
     * Get the absolute path to this Adive application's root directory
     *
     * This method returns the absolute path to the Adive application's
     * directory. If the Adive application is installed in a public-accessible
     * sub-directory, the sub-directory path will be included. This method
     * will always return an absolute path WITH a trailing slash.
     *
     * @return string
     */
    public function root()
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . rtrim($this->request->getRootUri(), '/') . '/';
    }

    /**
     * Clean current output buffer
     */
    protected function cleanBuffer()
    {
        if (ob_get_level() !== 0) {
            ob_clean();
        }
    }

    /**
     * Stop
     *
     * The thrown exception will be caught in application's `call()` method
     * and the response will be sent as is to the HTTP client.
     *
     * @throws \Adive\Exception\Stop
     */
    public function stop()
    {
        throw new \Adive\Exception\Stop();
    }

    /**
     * Halt
     *
     * Stop the application and immediately send the response with a
     * specific status and body to the HTTP client. This may send any
     * type of response: info, success, redirect, client error, or server error.
     * If you need to render a template AND customize the response status,
     * use the application's `render()` method instead.
     *
     * @param  int      $status     The HTTP response status
     * @param  string   $message    The HTTP response body
     */
    public function halt($status, $message = '')
    {
        $this->cleanBuffer();
        $this->response->status($status);
        $this->response->body($message);
        $this->stop();
    }

    /**
     * Pass
     *
     * The thrown exception is caught in the application's `call()` method causing
     * the router's current iteration to stop and continue to the subsequent route if available.
     * If no subsequent matching routes are found, a 404 response will be sent to the client.
     *
     * @throws \Adive\Exception\Pass
     */
    public function pass()
    {
        $this->cleanBuffer();
        throw new \Adive\Exception\Pass();
    }

    /**
     * Set the HTTP response Content-Type
     * @param  string   $type   The Content-Type for the Response (ie. text/html)
     */
    public function contentType($type)
    {
        $this->response->headers->set('Content-Type', $type);
    }

    /**
     * Set the HTTP response status code
     * @param  int      $code     The HTTP response status code
     */
    public function status($code)
    {
        $this->response->setStatus($code);
    }

    /**
     * Get the URL for a named route
     * @param  string               $name       The route name
     * @param  array                $params     Associative array of URL parameters and replacement values
     * @throws \RuntimeException    If named route does not exist
     * @return string
     */
    public function urlFor($name, $params = array())
    {
        return $this->request->getRootUri() . $this->router->urlFor($name, $params);
    }

    /**
     * Redirect
     *
     * This method immediately redirects to a new URL. By default,
     * this issues a 302 Found response; this is considered the default
     * generic redirect response. You may also specify another valid
     * 3xx status code if you want. This method will automatically set the
     * HTTP Location header for you using the URL parameter.
     *
     * @param  string   $url        The destination URL
     * @param  int      $status     The HTTP redirect status code (optional)
     */
    public function redirect($url, $status = 302)
    {
        $this->response->redirect($url, $status);
        $this->halt($status);
    }

    /********************************************************************************
    * Flash Messages
    *******************************************************************************/

    /**
     * Set flash message for subsequent request
     * @param  string   $key
     * @param  mixed    $value
     */
    public function flash($key, $value)
    {
        if (isset($this->environment['adive.flash'])) {
            $this->environment['adive.flash']->set($key, $value);
        }
    }

    /**
     * Set flash message for current request
     * @param  string   $key
     * @param  mixed    $value
     */
    public function flashNow($key, $value)
    {
        if (isset($this->environment['adive.flash'])) {
            $this->environment['adive.flash']->now($key, $value);
        }
    }

    /**
     * Keep flash messages from previous request for subsequent request
     */
    public function flashKeep()
    {
        if (isset($this->environment['adive.flash'])) {
            $this->environment['adive.flash']->keep();
        }
    }

    /********************************************************************************
    * Hooks
    *******************************************************************************/

    /**
     * Assign hook
     * @param  string   $name       The hook name
     * @param  mixed    $callable   A callable object
     * @param  int      $priority   The hook priority; 0 = high, 10 = low
     */
    public function hook($name, $callable, $priority = 10)
    {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = array(array());
        }
        if (is_callable($callable)) {
            $this->hooks[$name][(int) $priority][] = $callable;
        }
    }

    /**
     * Invoke hook
     * @param  string   $name       The hook name
     * @param  mixed    $hookArg    (Optional) Argument for hooked functions
     */
    public function applyHook($name, $hookArg = null)
    {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = array(array());
        }
        if (!empty($this->hooks[$name])) {
            // Sort by priority, low to high, if there's more than one priority
            if (count($this->hooks[$name]) > 1) {
                ksort($this->hooks[$name]);
            }
            foreach ($this->hooks[$name] as $priority) {
                if (!empty($priority)) {
                    foreach ($priority as $callable) {
                        call_user_func($callable, $hookArg);
                    }
                }
            }
        }
    }

    /**
     * Get hook listeners
     *
     * Return an array of registered hooks. If `$name` is a valid
     * hook name, only the listeners attached to that hook are returned.
     * Else, all listeners are returned as an associative array whose
     * keys are hook names and whose values are arrays of listeners.
     *
     * @param  string     $name     A hook name (Optional)
     * @return array|null
     */
    public function getHooks($name = null)
    {
        if (!is_null($name)) {
            return isset($this->hooks[(string) $name]) ? $this->hooks[(string) $name] : null;
        } else {
            return $this->hooks;
        }
    }

    /**
     * Clear hook listeners
     *
     * Clear all listeners for all hooks. If `$name` is
     * a valid hook name, only the listeners attached
     * to that hook will be cleared.
     *
     * @param  string   $name   A hook name (Optional)
     */
    public function clearHooks($name = null)
    {
        if (!is_null($name) && isset($this->hooks[(string) $name])) {
            $this->hooks[(string) $name] = array(array());
        } else {
            foreach ($this->hooks as $key => $value) {
                $this->hooks[$key] = array(array());
            }
        }
    }

    /********************************************************************************
    * Kernel
    *******************************************************************************/

    /**
     * Add kernel
     *
     * This method prepends new kernel to the application kernel stack.
     * The argument must be an instance that subclasses Pulse_Kernel.
     *
     * @param \Adive\Kernel
     */
    public function add(\Adive\Kernel $newKernel)
    {
        if(in_array($newKernel, $this->kernel)) {
            $kernel_class = get_class($newKernel);
            throw new \RuntimeException("Circular Kernel setup detected. Tried to queue the same Kernel instance ({$kernel_class}) twice.");
        }
        $newKernel->setApplication($this);
        $newKernel->setNextKernel($this->kernel[0]);
        array_unshift($this->kernel, $newKernel);
    }

    /********************************************************************************
    * Runner
    *******************************************************************************/

    /**
     * Run
     *
     * This method invokes the kernel stack, including the core Adive application;
     * the result is an array of HTTP status, header, and body. These three items
     * are returned to the HTTP client.
     */
    public function processor()
    {
        set_error_handler(array('\Adive\Adive', 'handleErrors'));

        //Apply final outer kernel layers
        if ($this->config('debug')) {
            //Apply pretty exceptions only in debug to avoid accidental information leakage in production
            $this->add(new \Adive\Kernel\ErrorReporting());
        }

        //Invoke kernel and application stack
        $this->kernel[0]->call();

        //Fetch status, header, and body
        list($status, $headers, $body) = $this->response->finalize();

        // Serialize cookies (with optional encryption)
        \Adive\Http\Util::serializeCookies($headers, $this->response->cookies, $this->settings);

        //Send headers
        if (headers_sent() === false) {
            //Send status
            if (strpos(PHP_SAPI, 'cgi') === 0) {
                header(sprintf('Status: %s', \Adive\Http\Response::getMessageForCode($status)));
            } else {
                header(sprintf('HTTP/%s %s', $this->config('http.version'), \Adive\Http\Response::getMessageForCode($status)));
            }

            //Send headers
            foreach ($headers as $name => $value) {
                $hValues = explode("\n", $value);
                foreach ($hValues as $hVal) {
                    header("$name: $hVal", false);
                }
            }
        }

        //Send body, but only if it isn't a HEAD request
        if (!$this->request->isHead()) {
            echo $body;
        }

        $this->applyHook('adive.after');

        restore_error_handler();
    }
    
    /**
     * Require statement
     */
    public function route($folder, $path = 'home')
    {
        return require_once $folder.'/'.$path.'.php';
    }

    /**
     * Call
     *
     * This method finds and iterates all route objects that match the current request URI.
     */
    public function call()
    {
        try {
            if (isset($this->environment['adive.flash'])) {
                $this->view()->setData('flash', $this->environment['adive.flash']);
            }
            $this->applyHook('adive.before');
            ob_start();
            $this->applyHook('adive.before.router');
            $dispatched = false;
            $matchedRoutes = $this->router->getMatchedRoutes($this->request->getMethod(), $this->request->getResourceUri());
            foreach ($matchedRoutes as $route) {
                try {
                    $this->applyHook('adive.before.dispatch');
                    $dispatched = $route->dispatch();
                    $this->applyHook('adive.after.dispatch');
                    if ($dispatched) {
                        break;
                    }
                } catch (\Adive\Exception\Pass $e) {
                    continue;
                }
            }
            if (!$dispatched) {
                $this->notFound();
            }
            $this->applyHook('adive.after.router');
            $this->stop();
        } catch (\Adive\Exception\Stop $e) {
            $this->response()->write(ob_get_clean());
        } catch (\Exception $e) {
            if ($this->config('debug')) {
                throw $e;
            } else {
                try {
                    $this->error($e);
                } catch (\Adive\Exception\Stop $e) {
                    // Do nothing
                }
            }
        }
    }

    /********************************************************************************
    * Error Handling and Debugging
    *******************************************************************************/

    /**
     * Convert errors into ErrorException objects
     *
     * This method catches PHP errors and converts them into \ErrorException objects;
     * these \ErrorException objects are then thrown and caught by Adive's
     * built-in or custom error handlers.
     *
     * @param  int            $errno   The numeric type of the Error
     * @param  string         $errstr  The error message
     * @param  string         $errfile The absolute path to the affected file
     * @param  int            $errline The line number of the error in the affected file
     * @return bool
     * @throws \ErrorException
     */
    public static function handleErrors($errno, $errstr = '', $errfile = '', $errline = '')
    {
        if (!($errno & error_reporting())) {
            return;
        }

        throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    /**
     * Generate diagnostic template markup
     *
     * This method accepts a title and body content to generate an HTML document layout.
     *
     * @param  string   $title  The title of the HTML template
     * @param  string   $body   The body content of the HTML template
     * @return string
     */
    protected static function generateTemplateMarkup($title, $body)
    {
        return sprintf("<!doctype html><html lang=\"es\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><link rel=\"stylesheet\" href=\"".iasset('bootstrap.min.css')."\"><link rel=\"stylesheet\" href=\"".iasset('styles.css')."\"><title>%s</title><link rel=\"icon\" type=\"image/png\" href=\"".iasset('adiveLogo.png')."\" /><link href=\"".iasset('font-awesome.min.css')."\" rel=\"stylesheet\" type=\"text/css\"></head><body><nav class=\"navbar navbar-inverse navbar-fixed-top\"> <div class=\"container\"><div class=\"navbar-header\"><button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\"><span class=\"sr-only\">Toggle navigation</span><span class=\"icon-bar\"></span><span class=\"icon-bar\"></span><span class=\"icon-bar\"></span></button><a class=\"navbar-brand\" href=\"".basePath()."\">Adive.</a></div><div id=\"navbar\" class=\"collapse navbar-collapse\"><ul class=\"nav navbar-nav\"><li><a href=\"".basePath()."\">Home</a></li><li><a href=\"".path('adashboard')."\">Dashboard</a></li></ul></div></div></nav><div class=\"container\"><div class=\"starter-template\"><h1>%s</h1>%s</div></div></script></body></html>", $title, $title, $body);
    }

    /**
     * Default Not Found handler
     */
    protected function defaultNotFound()
    {
        echo static::generateTemplateMarkup('404 Page Not Found', '<p>The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly. If all else fails, you can visit our home page at the link below.</p><a href="' . $this->request->getRootUri() . '/">Visit the Home Page</a>');
    }

    /**
     * Default Error handler
     */
    protected function defaultError($e)
    {
        $this->getLog()->error($e);
        echo self::generateTemplateMarkup('Error', '<p>A website error has occurred. The website administrator has been notified of the issue. Sorry for the temporary inconvenience.</p>');
    }
}
