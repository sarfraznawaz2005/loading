<?php

return [

    // enable/disable loading indicator
    'enabled' => env('ENABLE_LOADING', true),

    // specify loading icon:
    // cp-round, cp-pinwheel, cp-balls, cp-bubble, cp-flip, cp-hue, cp-skeleton, cp-eclipse, cp-boxes, cp-morph, cp-heart, cp-meter
    'icon' => 'cp-pinwheel',

    // hide loading indicator on DOM ready or complete window load. Use either of "window", "dom"
    'hide_event' => 'window',

    // loading indicator background overlay options
    'show_overlay' => true,
    'overlay_background_color' => '#fff',
    'overlay_opacity' => 0.7,

    // any custom css you want to override loading indicator styles with
    'custom_css' => '',

];
