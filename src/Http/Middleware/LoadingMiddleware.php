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
        $icon = config('loading.icon', 'cp-skeleton');
        $showOverlay = config('loading.show_overlay', true) ? 'block' : 'none';
        $overlayOpacity = config('loading.overlay_opacity', 0.3);
        $overlayBackground = config('loading.overlay_background_color', '#000');
        $clodeLoadingCode = config('loading.hide_event') === 'window' ? 'window.onload = hidePHPLoadingIndicator;' : 'document.addEventListener("DOMContentLoaded", hidePHPLoadingIndicator);';
        $customCSS = config('loading.custom_css', '');

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

        $html = <<< LOADING
        <style type="text/css">
        .php-loading-indicator-overlay {                
            position: fixed;
            z-index: 9999999999999999999999999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        
        .php-loading-indicator-overlay:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: $overlayBackground;
            opacity: $overlayOpacity;
            display:$showOverlay;
        }
                                
        .cp-spinner{width:48px;height:48px;display:inline-block;box-sizing:border-box;position:relative}.cp-round:before{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px #bababa;border-right:solid 6px #bababa;border-bottom:solid 6px #bababa;border-left:solid 6px #bababa;position:absolute;top:0;left:0}.cp-round:after{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px #000;border-right:solid 6px transparent;border-bottom:solid 6px transparent;border-left:solid 6px transparent;position:absolute;top:0;left:0;animation:cp-round-animate 1s ease-in-out infinite}@keyframes cp-round-animate{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}.cp-pinwheel{border-radius:50%;width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 24px #0fd6ff;border-right:solid 24px #58bd55;border-bottom:solid 24px #eb68a1;border-left:solid 24px #f3d53f;animation:cp-pinwheel-animate 1s linear infinite}@keyframes cp-pinwheel-animate{0%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(0)}25%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}50%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f}75%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}100%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(360deg)}}.cp-balls{animation:cp-balls-animate 1s linear infinite}.cp-balls:before{border-radius:50%;content:" ";width:24px;height:24px;display:inline-block;box-sizing:border-box;background-color:#0fd6ff;position:absolute;top:0;left:0;animation:cp-balls-animate-before 1s ease-in-out infinite}.cp-balls:after{border-radius:50%;content:" ";width:24px;height:24px;display:inline-block;box-sizing:border-box;background-color:#eb68a1;position:absolute;bottom:0;right:0;animation:cp-balls-animate-after 1s ease-in-out infinite}@keyframes cp-balls-animate{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}@keyframes cp-balls-animate-before{0%{transform:translate(-5px,-5px)}50%{transform:translate(0,0)}100%{transform:translate(-5px,-5px)}}@keyframes cp-balls-animate-after{0%{transform:translate(5px,5px)}50%{transform:translate(0,0)}100%{transform:translate(5px,5px)}}.cp-bubble{border-radius:50%;width:24px;height:24px;display:inline-block;box-sizing:border-box;background:#58bd55;animation:cp-bubble-animate 1s linear infinite}.cp-bubble:before{border-radius:50%;content:" ";width:24px;height:24px;display:inline-block;box-sizing:border-box;background-color:#58bd55;position:absolute;left:-30px;animation:cp-bubble-animate-before 1s ease-in-out infinite}.cp-bubble:after{border-radius:50%;content:" ";width:24px;height:24px;display:inline-block;box-sizing:border-box;background-color:#58bd55;position:absolute;right:-30px;animation:cp-bubble-animate-after 1s ease-in-out infinite}@keyframes cp-bubble-animate{0%{opacity:.5;transform:scale(1) translateX(0)}25%{opacity:1;transform:scale(1.1) translateX(-15px)}50%{opacity:1;transform:scale(1.2) translateX(15px)}100%{opacity:.5;transform:scale(1) translateX(0)}}@keyframes cp-bubble-animate-before{0%{opacity:.5;transform:scale(1)}25%{transform:scale(1.1)}100%,50%{opacity:1;transform:scale(1)}}@keyframes cp-bubble-animate-after{0%,50%{opacity:.5;transform:scale(1)}50%{transform:scale(1.1)}100%,75%{opacity:1;transform:scale(1)}}.cp-flip{transform-style:preserve-3d;perspective:10em}.cp-flip:before{width:48px;height:48px;display:inline-block;box-sizing:border-box;background:#000;content:" ";position:absolute;top:0;left:0;animation:cp-flip-animate-before 2s linear infinite}@keyframes cp-flip-animate-before{0%{transform:rotateY(0) rotateX(0)}25%{transform:rotateY(360deg) rotateX(0)}50%{transform:rotateY(360deg) rotateX(360deg)}75%{transform:rotateY(0) rotateX(360deg)}100%{transform:rotateY(0) rotateX(0)}}.cp-hue{width:24px;height:24px;display:inline-block;box-sizing:border-box;background:#000;border-radius:50%;animation:cp-hue-animate 1s ease-in-out infinite}.cp-hue:before{border-radius:0 12px 12px 0;content:" ";width:12px;height:24px;display:inline-block;box-sizing:border-box;background:#fff;position:absolute;top:0;right:0;animation:cp-hue-animate-before 1s ease-in-out infinite}@keyframes cp-hue-animate{0%{background:#000}25%{background:#58bd55}50%{background:#eb68a1}75%{background:#f3d53f}100%{background:#000}}@keyframes cp-hue-animate-before{0%{transform:rotateY(0);transform-origin:left center;opacity:.5}30%,70%{transform:rotateY(180deg);transform-origin:left center;opacity:.2}100%{transform:rotateY(0);opacity:.5}}.cp-skeleton{border-radius:50%;border-top:solid 6px #000;border-right:solid 6px transparent;border-bottom:solid 6px transparent;border-left:solid 6px transparent;animation:cp-skeleton-animate 1s linear infinite}.cp-skeleton:before{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px transparent;border-right:solid 6px transparent;border-bottom:solid 6px transparent;border-left:solid 6px #000;position:absolute;top:-6px;left:-6px;transform:rotateZ(-30deg)}.cp-skeleton:after{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px transparent;border-right:solid 6px #000;border-bottom:solid 6px transparent;border-left:solid 6px transparent;position:absolute;top:-6px;right:-6px;transform:rotateZ(30deg)}@keyframes cp-skeleton-animate{0%{transform:rotate(0);opacity:1}50%{opacity:.7}100%{transform:rotate(360deg);opacity:1}}.cp-eclipse{width:12px;height:12px;display:inline-block;box-sizing:border-box;border-radius:50%;background:#f3d53f;margin:12px;animation:cp-eclipse-animate 1s ease-out infinite}.cp-eclipse:before{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px transparent;border-right:solid 6px #f3d53f;border-bottom:solid 6px transparent;border-left:solid 6px transparent;position:absolute;top:-18px;left:-18px}.cp-eclipse:after{border-radius:50%;content:" ";width:48px;height:48px;display:inline-block;box-sizing:border-box;border-top:solid 6px transparent;border-right:solid 6px transparent;border-bottom:solid 6px transparent;border-left:solid 6px #f3d53f;position:absolute;top:-18px;right:-18px}@keyframes cp-eclipse-animate{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}.cp-boxes:before{width:24px;height:24px;display:inline-block;box-sizing:border-box;content:" ";background:#58bd55;position:absolute;top:12px;left:0;animation:cp-boxes-animate-before 1s ease-in-out infinite}.cp-boxes:after{width:24px;height:24px;display:inline-block;box-sizing:border-box;content:" ";background:#58bd55;position:absolute;top:12px;right:0;animation:cp-boxes-animate-after 1s ease-in-out infinite}@keyframes cp-boxes-animate-before{0%{transform:translateX(-24px) rotate(45deg)}50%{transform:translateX(-8px) rotate(225deg)}100%{transform:translateX(-24px) rotate(45deg)}}@keyframes cp-boxes-animate-after{0%{transform:translateX(24px) rotate(45deg)}50%{transform:translateX(8px) rotate(-225deg)}100%{transform:translateX(24px) rotate(45deg)}}.cp-morph{width:48px;height:48px;display:inline-block;box-sizing:border-box;background:#0fd6ff;animation:cp-morph-animate 1s linear infinite}@keyframes cp-morph-animate{0%{transform:rotate(0) scale(1);border-radius:0;background:#f3d53f}25%,75%{transform:rotate(180deg) scale(.4);border-radius:50%;background:#0fd6ff}100%{transform:rotate(360deg) scale(1);border-radius:0;background:#f3d53f}}.cp-heart{animation:cp-heart-animate 2s ease-in-out infinite}.cp-heart:before{border-radius:12px 12px 0 0;content:" ";width:24px;height:35px;display:inline-block;box-sizing:border-box;background-color:#eb68a1;transform:rotate(-45deg);position:absolute;top:0;left:8px}.cp-heart:after{border-radius:12px 12px 0 0;content:" ";width:24px;height:35px;display:inline-block;box-sizing:border-box;background-color:#eb68a1;transform:rotate(45deg);position:absolute;top:0;right:8px}@keyframes cp-heart-animate{0%{transform:scale(.9);transform-origin:center}15%{transform:scale(1.4);transform-origin:center}30%{transform:scale(.9);transform-origin:center}45%{transform:scale(1.4);transform-origin:center}100%,60%{transform:scale(.9);transform-origin:center}}.cp-meter{border-radius:50%;border-top:solid 6px #0fd6ff;border-right:solid 6px #0fd6ff;border-bottom:solid 6px #0fd6ff;border-left:solid 6px #0fd6ff;width:48px;height:48px;display:inline-block;box-sizing:border-box}.cp-meter:before{border-radius:3px;content:" ";width:6px;height:12px;display:inline-block;box-sizing:border-box;background-color:#0fd6ff;position:absolute;top:5px;left:16px;transform-origin:center bottom;animation:cp-meter-animate-before 1s linear infinite}@keyframes cp-meter-animate-before{0%{transform:rotate(-45deg)}100%{transform:rotate(315deg)}}
        
        $customCSS
        </style>

        <div class="php-loading-indicator-overlay">
            <div class="cp-spinner $icon"></div>
        </div>

        <script>
        $clodeLoadingCode
        
        function hidePHPLoadingIndicator() {
            var element = document.getElementsByClassName('php-loading-indicator-overlay')[0];
            element.parentNode.removeChild(element);                      
        }
        </script>
LOADING;

        $content = $response->getContent();

        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $html = "\n<!-- Loading Start -->\n" . $this->compress($html) . "\n<!-- Loading End -->\n\n";
            $content = substr($content, 0, $bodyPosition) . $html . substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }

    private function compress($html)
    {
        ini_set('pcre.recursion_limit', '16777');
        @ini_set('zlib.output_compression', 'On');

        $regEx = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        $compressed = preg_replace($regEx, ' ', $html);

        if ($compressed !== null) {
            $html = $compressed;
        }

        return trim($html);
    }
}
