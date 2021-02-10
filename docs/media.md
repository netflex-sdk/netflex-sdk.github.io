# Media Presets

The `/config/media.php` file is used to configure the various presets for cdn media. This makes it easy to re-use media styles.

All presets inherits from the 'default' preset, so if any parameters are left out, they will inherit its value from the default preset.

## API reference

The full technical API reference for `Netflex\Pages\MediaPreset` can be [found here](https://netflex-sdk.github.io/docs/api/Netflex/Pages/MediaPreset.html).

## Example configuration

```php
<?php

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

            'mode' => MODE_ORIGINAL,
            'resolutions' => ['1x', '2x'],

        ],

        'banner' => [

          'mode' => MODE_LANDSCAPE,
          'resolutions' => ['1x', '2x'],
          'size' => [1920, 600],

          'breakpoints' => [

            // Override for specific breakpoints:
            'md' => [
              'mode' => MODE_FIT,
              'resolutions' => ['1x'].
            ],

            'lg' => 'md', // Aliasing 'lg' breakpoint to 'md'
          ]
        ],
    ],
];
```

