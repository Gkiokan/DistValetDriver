<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\BasicValetDriver;

class DistValetDriver extends BasicValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {

        return file_exists($sitePath . '/dist/index.html');
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        if (file_exists($staticFilePath = $sitePath . '/dist' . $uri)) {
            if( is_file($staticFilePath) ) 
                return $staticFilePath;

            if( file_exists($staticFilePath = $staticFilePath . '/index.html') )
                return $staticFilePath;        
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        // Enable this if you just want to have straight dist/index.html to be served by default
        // Issue with this is that 'not existing paths' will still show up the default page.
        // return $sitePath . '/dist/index.html';        
         
        // Path the $uri and make sure we have a build file at that path that should be resolved
        if( $uri == '/' )
            $uri = '';

        if( file_exists($fullPath = $sitePath . '/dist' . $uri . '/index.html') )
            return $fullPath;

        // In case the file doesn't exists, it just returns 404 by valet
        // This could be optimized to allow $sitePath/dist/404.html or something
        return false;
    }
}