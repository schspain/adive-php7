<?php
namespace Adive\Kernel;

class ErrorReporting extends \Adive\Kernel
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = $settings;
    }

    /**
     * Call
     */
    public function call()
    {
        try {
            $this->next->call();
        } catch (\Exception $e) {
            $log = $this->app->getLog(); // Force Adive to append log to env if not already
            $env = $this->app->environment();
            $env['adive.log'] = $log;
            $env['adive.log']->error($e);
            $this->app->contentType('text/html');
            $this->app->response()->status(500);
            $this->app->response()->body($this->renderBody($env, $e));
        }
    }

    /**
     * Render response body
     * @param  array      $env
     * @param  \Exception $exception
     * @return string
     */
    protected function renderBody(&$env, $exception)
    {
        $title = 'Oops! Exception detected';
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = str_replace(array('#', '\n'), array('<div>#', '</div>'), $exception->getTraceAsString());
        $html = sprintf('<h1>%s</h1>', $title);
        $html .= '<p>Something is broken, the application could not run, Adive has detected the following error:</p>';
        $html .= '<h2>Details</h2>';
        $html .= sprintf('<div><strong>Type:</strong> %s</div>', get_class($exception));
        if ($code) {
            $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
        }
        if ($message) {
            $html .= sprintf('<div><strong>Message:</strong> %s</div>', $message);
        }
        if ($file) {
            $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
        }
        if ($line) {
            $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
        }
        if ($trace) {
            $html .= '<h2>Trace</h2>';
            $html .= sprintf('<pre>%s</pre>', $trace);
        }

        return sprintf("<div class=\"starter-template\">%s</div>", $html);
    }
}
