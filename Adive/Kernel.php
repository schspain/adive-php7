<?php
namespace Adive;

abstract class Kernel
{
    /**
     * @var \Adive\Adive Reference to the primary application instance
     */
    protected $app;

    /**
     * @var mixed Reference to the next downstream kernel
     */
    protected $next;

    /**
     * Set application
     *
     * This method injects the primary Adive application instance into
     * this kernel.
     *
     * @param  \Adive\Adive $APIlication
     */
    final public function setApplication($APIlication)
    {
        $this->app = $APIlication;
    }

    /**
     * Get application
     *
     * This method retrieves the application previously injected
     * into this kernel.
     *
     * @return \Adive\Adive
     */
    final public function getApplication()
    {
        return $this->app;
    }

    /**
     * Set next kernel
     *
     * This method injects the next downstream kernel into
     * this kernel so that it may optionally be called
     * when appropriate.
     *
     * @param \Adive|\Adive\Kernel
     */
    final public function setNextKernel($nextKernel)
    {
        $this->next = $nextKernel;
    }

    /**
     * Get next kernel
     *
     * This method retrieves the next downstream kernel
     * previously injected into this kernel.
     *
     * @return \Adive\Adive|\Adive\Kernel
     */
    final public function getNextKernel()
    {
        return $this->next;
    }

    /**
     * Call
     *
     * Perform actions specific to this kernel and optionally
     * call the next downstream kernel.
     */
    abstract public function call();
}
