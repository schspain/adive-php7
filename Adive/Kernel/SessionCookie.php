<?php
namespace Adive\Kernel;

class SessionCookie extends \Adive\Kernel
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     *
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'expires' => '20 minutes',
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => false,
            'name' => 'slim_session',
        );
        $this->settings = array_merge($defaults, $settings);
        if (is_string($this->settings['expires'])) {
            $this->settings['expires'] = strtotime($this->settings['expires']);
        }

        /**
         * Session
         *
         * We must start a native PHP session to initialize the $_SESSION superglobal.
         * However, we won't be using the native session store for persistence, so we
         * disable the session cookie and cache limiter. We also set the session
         * handler to this class instance to avoid PHP's native session file locking.
         */
        ini_set('session.use_cookies', 0);
        session_cache_limiter(false);
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
    }

    /**
     * Call
     */
    public function call()
    {
        $this->loadSession();
        $this->next->call();
        $this->saveSession();
    }

    /**
     * Load session
     */
    protected function loadSession()
    {
        if (session_id() === '') {
            session_start();
        }

        $value = $this->app->getCookie($this->settings['name']);

        if ($value) {
            try {
                $_SESSION = unserialize($value);
            } catch (\Exception $e) {
                $this->app->getLog()->error('Error unserializing session cookie value! ' . $e->getMessage());
            }
        } else {
            $_SESSION = array();
        }
    }

    /**
     * Save session
     */
    protected function saveSession()
    {
        $value = serialize($_SESSION);

        if (strlen($value) > 4096) {
            $this->app->getLog()->error('WARNING! Adive\Kernel\SessionCookie data size is larger than 4KB. Content save failed.');
        } else {
            $this->app->setCookie(
                $this->settings['name'],
                $value,
                $this->settings['expires'],
                $this->settings['path'],
                $this->settings['domain'],
                $this->settings['secure'],
                $this->settings['httponly']
            );
        }
        // session_destroy();
    }

    /********************************************************************************
    * Session Handler
    *******************************************************************************/

    /**
     * @codeCoverageIgnore
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function close()
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function read($id)
    {
        return '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function write($id, $data)
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function destroy($id)
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}
