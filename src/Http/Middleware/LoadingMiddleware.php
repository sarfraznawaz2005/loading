<?php

namespace Sarfraznawaz2005\Loading\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoadingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (config('loading.enabled')) {
            $this->addContent($request, $response);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param $response
     */
    protected function addContent($request, $response)
    {
        $color = config('loading.color');
        $size = config('loading.size');

        $sizeMap = [
            'normal' => '12px',
            'medium' => '16px',
            'large' => '20px',
        ];

        $size = $sizeMap[$size];

        // do nothing in case of console
        if (app()->runningInConsole()) {
            return;
        }

        // do nothing in case of ajax request
        if ($request->ajax() || $request->expectsJson()) {
            return;
        }

        // do nothing in case of method which is not GET
        if (!$request->isMethod('get')) {
            return;
        }

        // do nothing in case of binary content
        if ($response instanceof BinaryFileResponse) {
            return;
        }

        // do nothing in case of ajax binary file content
        if ($response instanceof BinaryFileResponse) {
            return;
        }

        $html = <<< LOADING
            <style>
            
            .php_loading-indicator-with-overlay {
                position: fixed;
                z-index: 9999999999999999999999999;
                height: 2em;
                width: 2em;
                overflow: show;
                margin: auto;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0
            }
            
            .php_loading-indicator-with-overlay:before {
                content: '';
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, .3)
            }
            
            .php_loading-indicator-with-overlay:not(:required) {
                font: 0/0 a;
                color: transparent;
                text-shadow: none;
                background-color: transparent;
                border: 0
            }
            
            .php_loading-indicator-with-overlay:not(:required):after {
                content: '';
                display: block;
                font-size: $size;
                width: 1em;
                height: 1em;
                margin-top: -.5em;
                -webkit-animation: spinner 1.5s infinite linear;
                -moz-animation: spinner 1.5s infinite linear;
                -ms-animation: spinner 1.5s infinite linear;
                -o-animation: spinner 1.5s infinite linear;
                animation: spinner 1.5s infinite linear;
                border-radius: .5em;
                -webkit-box-shadow: $color 1.5em 0 0 0, $color 1.1em 1.1em 0 0, $color 0 1.5em 0 0, $color -1.1em 1.1em 0 0, $color -1.5em 0 0 0, $color -1.1em -1.1em 0 0, $color 0 -1.5em 0 0, $color 1.1em -1.1em 0 0;
                box-shadow: $color 1.5em 0 0 0, $color 1.1em 1.1em 0 0, $color 0 1.5em 0 0, $color -1.1em 1.1em 0 0, $color -1.5em 0 0 0, $color -1.1em -1.1em 0 0, $color 0 -1.5em 0 0, $color 1.1em -1.1em 0 0;
            }
            
            @-webkit-keyframes spinner {
                0% {
                    -webkit-transform: rotate(0);
                    -moz-transform: rotate(0);
                    -ms-transform: rotate(0);
                    -o-transform: rotate(0);
                    transform: rotate(0)
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -moz-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }
            
            @-moz-keyframes spinner {
                0% {
                    -webkit-transform: rotate(0);
                    -moz-transform: rotate(0);
                    -ms-transform: rotate(0);
                    -o-transform: rotate(0);
                    transform: rotate(0)
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -moz-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }
            
            @-o-keyframes spinner {
                0% {
                    -webkit-transform: rotate(0);
                    -moz-transform: rotate(0);
                    -ms-transform: rotate(0);
                    -o-transform: rotate(0);
                    transform: rotate(0)
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -moz-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }
            
            @keyframes spinner {
                0% {
                    -webkit-transform: rotate(0);
                    -moz-transform: rotate(0);
                    -ms-transform: rotate(0);
                    -o-transform: rotate(0);
                    transform: rotate(0)
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -moz-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }
            </style>

            <div class="php_loading-indicator-with-overlay">Loading&#8230;</div>

            <script>
                window.onload = function() {
                    var elements = document.getElementsByClassName('php_loading-indicator-with-overlay');
                    
                    while(elements.length > 0){
                        elements[0].parentNode.removeChild(elements[0]);
                    }                    
                };
            </script>
LOADING;

        $content = $response->getContent();

        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $content = substr($content, 0, $bodyPosition) . $html . substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }
}
