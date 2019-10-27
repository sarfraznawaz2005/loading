<?php

return [

    // enable/disable loading indicator
    'enabled' => env('ENABLE_LOADING', true),

    // any of "normal", "medium", "large"
    'size' => 'normal',

    // color of loading indicator
    'color' => '#000',

    // hide loading indicator on DOM ready or complete window load. Use either of "window", "dom"
    'hide_event' => 'window'

];
