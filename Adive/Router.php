<?php
namespace Adive;

class Router
{
    /**
     * @var Route The current route (most recently dispatched)
     */
    protected $currentRoute;

    /**
     * @var array Lookup hash of all route objects
     */
    protected $routes;

    /**
     * @var array Lookup hash of named route objects, keyed by route name (lazy-loaded)
     */
    protected $namedRoutes;

    /**
     * @var array Array of route objects that match the request URI (lazy-loaded)
     */
    protected $matchedRoutes;

    /**
     * @var array Array containing all route groups
     */
    protected $routeGroups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = array();
        $this->routeGroups = array();
    }

    /**
     * Get Current Route object or the first matched one if matching has been performed
     * @return \Adive\Route|null
     */
    public function getCurrentRoute()
    {
        if ($this->currentRoute !== null) {
            return $this->currentRoute;
        }

        if (is_array($this->matchedRoutes) && count($this->matchedRoutes) > 0) {
            return $this->matchedRoutes[0];
        }

        return null;
    }

    /**
     * Return route objects that match the given HTTP method and URI
     * @param  string               $httpMethod   The HTTP method to match against
     * @param  string               $resourceUri  The resource URI to match against
     * @param  bool                 $reload       Should matching routes be re-parsed?
     * @return array[\Adive\Route]
     */
    public function getMatchedRoutes($httpMethod, $resourceUri, $reload = false)
    {
        if ($reload || is_null($this->matchedRoutes)) {
            $this->matchedRoutes = array();
            foreach ($this->routes as $route) {
                if (!$route->supportsHttpMethod($httpMethod) && !$route->supportsHttpMethod("ANY")) {
                    continue;
                }

                if ($route->matches($resourceUri)) {
                    $this->matchedRoutes[] = $route;
                }
            }
        }

        return $this->matchedRoutes;
    }

    /**
     * Add a route object to the router
     * @param  \Adive\Route     $route      The Adive Route
     */
    public function map(\Adive\Route $route)
    {
        list($groupPattern, $groupKernel) = $this->processGroups();

        $route->setPattern($groupPattern . $route->getPattern());
        $this->routes[] = $route;


        foreach ($groupKernel as $kernel) {
            $route->setKernel($kernel);
        }
    }

    /**
     * A helper function for processing the group's pattern and kernel
     * @return array Returns an array with the elements: pattern, kernelArr
     */
    protected function processGroups()
    {
        $pattern = "";
        $kernel = array();
        foreach ($this->routeGroups as $group) {
            $k = key($group);
            $pattern .= $k;
            if (is_array($group[$k])) {
                $kernel = array_merge($kernel, $group[$k]);
            }
        }
        return array($pattern, $kernel);
    }

    /**
     * Add a route group to the array
     * @param  string     $group      The group pattern (ie. "/books/:id")
     * @param  array|null $kernel Optional parameter array of kernel
     * @return int        The index of the new group
     */
    public function pushGroup($group, $kernel = array())
    {
        return array_push($this->routeGroups, array($group => $kernel));
    }

    /**
     * Removes the last route group from the array
     * @return bool    True if successful, else False
     */
    public function popGroup()
    {
        return (array_pop($this->routeGroups) !== null);
    }

    /**
     * Get URL for named route
     * @param  string               $name   The name of the route
     * @param  array                $params Associative array of URL parameter names and replacement values
     * @throws \RuntimeException            If named route not found
     * @return string                       The URL for the given route populated with provided replacement values
     */
    public function urlFor($name, $params = array())
    {
        if (!$this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route not found for name: ' . $name);
        }
        $search = array();
        foreach ($params as $key => $value) {
            $search[] = '#{' . preg_quote($key, '#') . '\+?(?!\w)}#';
        }
        $pattern = preg_replace($search, $params, $this->getNamedRoute($name)->getPattern());

        //Remove remnants of unpopulated, trailing optional pattern segments, escaped special characters
        return preg_replace('#\(/?:.+\)|\(|\)|\\\\#', '', $pattern);
    }

    /**
     * Add named route
     * @param  string            $name   The route name
     * @param  \Adive\Route       $route  The route object
     * @throws \RuntimeException         If a named route already exists with the same name
     */
    public function addNamedRoute($name, \Adive\Route $route)
    {
        if ($this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route already exists with name: ' . $name);
        }
        $this->namedRoutes[(string) $name] = $route;
    }

    /**
     * Has named route
     * @param  string   $name   The route name
     * @return bool
     */
    public function hasNamedRoute($name)
    {
        $this->getNamedRoutes();

        return isset($this->namedRoutes[(string) $name]);
    }

    /**
     * Get named route
     * @param  string           $name
     * @return \Adive\Route|null
     */
    public function getNamedRoute($name)
    {
        $this->getNamedRoutes();
        if ($this->hasNamedRoute($name)) {
            return $this->namedRoutes[(string) $name];
        } else {
            return null;
        }
    }

    /**
     * Get named routes
     * @return \ArrayIterator
     */
    public function getNamedRoutes()
    {
        if (is_null($this->namedRoutes)) {
            $this->namedRoutes = array();
            foreach ($this->routes as $route) {
                if ($route->getName() !== null) {
                    $this->addNamedRoute($route->getName(), $route);
                }
            }
        }

        return new \ArrayIterator($this->namedRoutes);
    }
}
