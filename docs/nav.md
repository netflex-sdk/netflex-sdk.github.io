# Navigation

This article describes how you can use the built-in helper methods to generate navigation menus from Netflex Pages.

## Building the navigation tree

In previous versions of the Netflex SDK, you had a global variable named `$navdata` that contained data for generating navigation menus.
In V2 this is deprecated, and a few new methods have been added to achieve this.

In Netflex SDK v2 we have added a helper function to generate navigation data, simply called `navigation_data`.
It takes a `$parent` id (the id of the page considered parent), and an optional `$type` and `$root` parameter.

It generates a [Collection](https://laravel.com/docs/7.x/collections) object with a simple nested data structure like this:

```php
(object) [
  'id' => 10000,        // Page ID
  'url' => '/',         // Page URL
  'target' => '_blank', // Link target type
  'type' => 12345,      // Template ID or type code
  'children' => ...     // Collection of children of this page with the same data structure
];
```

## Built in components

The Pages library adds a built in [Nav component](https://github.com/netflex-sdk/pages/blob/master/src/Components/Nav.php) that generates a simple bootstrap compatible nav menu.
It can be used like this:

```php
<x-nav parent="10000" /> {{-- Generates a navigation menu for children in page 10000 --}}
```

Notice that te `parent` attribute is optional, and that if not provided, the navigation menu will generate a menu for all routes.

This component simply uses the data provided by naviation_data to generate the navigation menu recursively.

## Custom navigation

The simplest way to create a custom navigation, is to extend the Netflex provided component, and providing your own custom view.

```php
<?php

namespace App\View\Components;

use Netflex\Pages\Components\Nav;

class CustomNav extends Nav
{
    public function render()
    {
      return view('custom-nav'); // <-- Override the render method to provide your own view
    }
}
```

And create the view (The example view is almost the same as used by the built-in nav, but you hopefully get the idea)

```php
<ul role="menu" {{ $attributes }}>
  @foreach ($children as $child)
    <li>
      <a
        target="{{ $child->target }}"
        href="{{ $child->url }}"
        role="menuitem"
      >
        {{ $child->title }}
      </a>
      @if($child->children->count())
        {{-- Note that we use the custom navigation recursively to enable sub menus --}}
        <x-custom-nav
          :class="trim($attributes->get('class') . ' dropdown-container')"
          :parent="$child->id"
          :levels="$levels - 1"
          :type="$type"
          :root="$root"
        />
      @endif
    </li>
  @endforeach
</ul>
```
