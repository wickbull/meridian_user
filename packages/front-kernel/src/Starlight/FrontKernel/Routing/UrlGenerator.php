<?php

namespace Starlight\FrontKernel\Routing;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator {

    /**
     * Replace all of the wildcard parameters for a route path.
     *
     * @param  string  $path
     * @param  array  $parameters
     * @return string
     */
    protected function replaceRouteParameters($path, array &$parameters)
    {
        if ($_path = app('events')->fire('url.make.replace-params', [$path, &$parameters], true)) {
            $path = $_path;
        }

        return parent::replaceRouteParameters($path, $parameters);
    }

    /**
     * Get the current URL with one or more replaced parameters
     *
     * @param  array  $routeParameters
     * @param  array  $getParameters
     * @param  bool   $absolute
     * @param  bool   $optimize
     * @return string
     */
    public function alter(array $routeParameters = array(), array $getParameters = array(), $absolute = true, $optimize = true)
    {
        $existingParameters = $this->request->route()->parameters();
        $replacedRouteParameters = array_intersect_key($routeParameters, $existingParameters) + $existingParameters;
        $replacedGetParameters = $getParameters + $this->request->query->all();

        if ($optimize) {
            $replacedGetParameters = array_filter($replacedGetParameters);
        }

        return $this->toRoute($this->request->route(), $replacedRouteParameters, $absolute)
            . $this->getRouteQueryString($replacedGetParameters);
    }

}