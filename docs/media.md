# Media Presets

The `/config/media.php` file is used to configure the various presets for cdn media. This makes it easy to re-use media styles.

## Example configuration

```php
<?php

use Netflex\Pages\Components\Picture;

/**
 *
 */
return [
    'breakpoints' => [
        'xss' => 320,
        'xs' => 480,
        'sm' => 768,
        'md' => 992,
        'lg' => 1200,
        'xl' => 1440,
        'xxl' => 1920,
    ],

    'presets' => [

        'default' => [

            'mode' => Picture::MODE_ORIGINAL,
            'resolutions' => ['1x', '2x'],

        ],

        'banner' => [

          'mode' => Picture::MODE_LANDSCAPE,
          'resolutions' => ['1x', '2x'],
          'size' => [1920, 600],

          'breakpoints' => [

            // Override for specific breakpoints:
            'md' => [
              'mode' => Picture::MODE_FIT,
              'resolutions' => ['1x'].
            ],

            'lg' => 'md', // Aliasing 'lg' breakpoint to 'md'
          ]
        ],
    ],
];
```

