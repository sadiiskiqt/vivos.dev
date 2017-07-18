<?php

namespace Atlantis\Helpers\Route;

/**
 * @see \Illuminate\Routing\Router
 */
class AtlantisRoute extends \Illuminate\Support\Facades\Route {

    public static function controller($path, $controllerClassName, $names = []) {

        $class = new \ReflectionClass($controllerClassName);
        $publicMethods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($publicMethods as $method) {
            $methodName = $method->name;

            if ($methodName == 'getMiddleware') {
                continue;
            }

            if (self::stringStartsWith($methodName, 'any')) {
                $slug = self::slug($method);
                //var_dump($slug);
                $slug = preg_replace("/{request}(\/)*/", "", $slug);
                if ($slug[strlen($slug) - 1] === "/") {
                    $slug = substr($slug, 0, strlen($slug) - 1);
                }
                $slugArray = explode("/", $slug);
                $lastSlugParameter = array_pop($slugArray);
                if ($lastSlugParameter === "index") {
                    $indexSlug = implode("/", $slugArray);
                    // var_dump(    "\Route::post($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName)");
                    \Route::any($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName);
                }

                \Route::any($path . '/' . $slug, $controllerClassName . '@' . $methodName);
            }
            if (self::stringStartsWith($methodName, 'get')) {
                $slug = self::slug($method);
                //var_dump($slug);

                $slug = preg_replace("/{request}(\/)*/", "", $slug);
                if ($slug[strlen($slug) - 1] === "/") {
                    $slug = substr($slug, 0, strlen($slug) - 1);
                }
                $slugArray = explode("/", $slug);
                $lastSlugParameter = array_pop($slugArray);
                if ($lastSlugParameter === "index") {
                    $indexSlug = implode("/", $slugArray);
                    // var_dump(    "\Route::post($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName)");
                    \Route::get($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName);
                }

                \Route::get($path . '/' . $slug, $controllerClassName . '@' . $methodName);
            }
            if (self::stringStartsWith($methodName, 'post')) {
                $slug = self::slug($method);
                $slug = preg_replace("/{request}(\/)*/", "", $slug);
                if ($slug[strlen($slug) - 1] === "/") {
                    $slug = substr($slug, 0, strlen($slug) - 1);
                }

                $slugArray = explode("/", $slug);
                $lastSlugParameter = array_pop($slugArray);
                if ($lastSlugParameter === "index") {
                    $indexSlug = implode("/", $slugArray);
                    // var_dump(    "\Route::post($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName)");
                    \Route::post($path . '/' . $indexSlug, $controllerClassName . '@' . $methodName);
                }
                //  var_dump(    "\Route::post($path . '/' . $slug, $controllerClassName . '@' . $methodName)");
                \Route::post($path . '/' . $slug, $controllerClassName . '@' . $methodName);
            }
        }

        \Route::get($path, $controllerClassName . '@getIndex');
    }

    protected static function stringStartsWith($string, $match) {
        return (substr($string, 0, strlen($match)) == $match) ? true : false;
    }

    protected static function slug($method) {
        $methodName = $method->name;
        $cleaned = str_replace(['any', 'get', 'post', 'delete'], '', $methodName);

        $snaked = \Illuminate\Support\Str::snake($cleaned, ' ');
        $slug = str_slug($snaked, '-');
        foreach ($method->getParameters() as $parameter) {


            $slug .= sprintf('/{%s%s}', $parameter->getName(), $parameter->isDefaultValueAvailable() ? '?' : '');
        }
        return $slug;
    }

}
