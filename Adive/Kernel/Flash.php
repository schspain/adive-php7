<?php
namespace Adive\Kernel;

class Flash extends \Adive\Kernel implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $messages;

    /**
     * Constructor
     * @param  array  $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = array_merge(array('key' => 'adive.flash'), $settings);
        $this->messages = array(
            'prev' => array(), //flash messages from prev request (loaded when kernel called)
            'next' => array(), //flash messages for next request
            'now' => array() //flash messages for current request
        );
    }

    /**
     * Call
     */
    public function call()
    {
        //Read flash messaging from previous request if available
        $this->loadMessages();

        //Prepare flash messaging for current request
        $env = $this->app->environment();
        $env['adive.flash'] = $this;
        $this->next->call();
        $this->save();
    }

    /**
     * Now
     *
     * Specify a flash message for a given key to be shown for the current request
     *
     * @param  string $key
     * @param  string $value
     */
    public function now($key, $value)
    {
        $this->messages['now'][(string) $key] = $value;
    }

    /**
     * Set
     *
     * Specify a flash message for a given key to be shown for the next request
     *
     * @param  string $key
     * @param  string $value
     */
    public function set($key, $value)
    {
        $this->messages['next'][(string) $key] = $value;
    }

    /**
     * Keep
     *
     * Retain flash messages from the previous request for the next request
     */
    public function keep()
    {
        foreach ($this->messages['prev'] as $key => $val) {
            $this->messages['next'][$key] = $val;
        }
    }

    /**
     * Save
     */
    public function save()
    {
        $_SESSION[$this->settings['key']] = $this->messages['next'];
    }

    /**
     * Load messages from previous request if available
     */
    public function loadMessages()
    {
        if (isset($_SESSION[$this->settings['key']])) {
            $this->messages['prev'] = $_SESSION[$this->settings['key']];
        }
    }

    /**
     * Return array of flash messages to be shown for the current request
     *
     * @return array
     */
    public function getMessages()
    {
        return array_merge($this->messages['prev'], $this->messages['now']);
    }

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset)
    {
        $messages = $this->getMessages();

        return isset($messages[$offset]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset)
    {
        $messages = $this->getMessages();

        return isset($messages[$offset]) ? $messages[$offset] : null;
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value)
    {
        $this->now($offset, $value);
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset)
    {
        unset($this->messages['prev'][$offset], $this->messages['now'][$offset]);
    }

    /**
     * Iterator Aggregate: Get Iterator
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $messages = $this->getMessages();

        return new \ArrayIterator($messages);
    }

    /**
     * Countable: Count
     */
    public function count()
    {
        return count($this->getMessages());
    }
}
