# Netflex Components

The Netflex SDK provides a lot of built-in components that integrates with the Netflex API for CDN images and inline editable content etc.
The components are implemented as [Blade components](https://laravel.com/docs/7.x/blade#components), and can also be used with the new Blade Component Tag syntax.

## BackgroundImage

The BackgroundImage comoponent can be used to create a CSS style tag that generates a responsive background image.

The preferred method of configuring the background image component, is through the use of media presets. See [Media Presets](/docs/media.md)

> [!NOTE]
> Background images can retrieve the file from a content area, but you will have to configure a editor button for it, it cannot be edited inline.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|class|Determines which class to generate the styles for|
|area|Load image from file defined in content area|If src is not defined|
|alt|HTML alt attribute when unable to load the image|*Optional*|
|title|HTML title attribute|*Optional*|
|src|Loads image from this filename|If area is not defined|
|mode|Resizing mode||`Picture::MODE_FIT`|
|width|Overrides the width|If preset is not defined|0|
|height|Overrides the height|If preset is not defined|0|
|size|Shorthand for setting both with and height|If preset is not defined or width and height is not defined|{width}x{height}|
|fill|Only used If mode is `PICTURE::MODE_FILL`|*Optional*||
|image-class|The class attribute to set for the image element|*Optional*|
|picture-class|The class attribute to set for the picture element|*Optional*|
|preset|The Media Preset to use||`default`|
|direction|Defines the direction for the image when using mode `Picture::MODE_FIT_DIRECTION`||`Picture::DIRECTION_CENTER`|
|is|Determines what tag to wrap the inner slot in|*Optional*|`div`

**Example:**

```html
<x-background-image
  class="main-banner"
  src="1590000000/main-banner.jpg"
  preset="banner"
  title="My Awesome Banner!"
  alt="Picture of the awesome banner"
/>

<div class="main-banner"></div>

<!-- You can also implement it like this to only scope the styling to the specific child element -->

<x-background-image
  src="1590000000/main-banner.jpg"
  preset="banner"
  title="My Awesome Banner!"
  alt="Picture of the awesome banner"
>
Inner content that will be overlayed on top of the background image
</x-background-image>

```

## Blocks

The Blocks component is used to display content from a BlockBuilder field.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area|Specifies the content area to load the blocks from|Yes||

**Example:**

```html
<x-blocks area="sections" />
```

## Component

This is a wrapper component that lets you use other components dynamically without knowing the component name ahead of time. Can be usefull when controlling what component should be rendered based on user input.

> [!NOTE]
> All other attributes will be 'proxied' to the target component

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|is|The component to wrap|Yes||
|attributes|Additional variales to proxy to the component|*Optional*|`[]`|

**Example:**

```html
<x-component is="x-seo" title="My awesome title!">
```

## EditorButton

The EditorButton component creates a menu that lets the user customize a content area inside the Netflexapp editor.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area||Yes||
|type|Specifies the type of the content area|Yes|*See table below*|
|items|Specifies the max number of items for array like data|*Optional*|99999|
|name||*Optional*||
|label||*Optional*||
|description||*Optional*||
|style||*Optional*|`'position: initial;'`|
|icon||*Optional*||
|position||*Optional*||
|field||*Optional*||
|model||*Optional*||
|options||*Optional*||

**Types:**

|Name|Description|
|----|-----------|
|checkbox||
|checkbox-group||
|color||
|contentlist||
|contentlist_advanced||
|datetime||
|editor_large||
|editor_small||
|entries||
|file||
|gallery||
|image||
|integer||
|link||
|multiselect||
|nav||
|select||
|tags||
|text||
|textarea||

**Example:**

```html
<x-editor-button
  area="newsfeed"
  type="entries"
  :model="App\Article::class"
/>
```

## EditorTools

The EditorTools component injects the necessary javascript and data required for rendering the inline editors and editor menus.
This component should always be included in templates that supports editing.

**Example:**

```html
<x-editor-tools />
```

## Image

The Image component generates a html img element that loads an image from the Netflex CDN.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area|Load image from file defined in content area|If src is not defined|
|size|Shorthand for setting both with and height|If not width and height is defined|{width}x{height}|
|width|Overrides the width|If not size is defined|0|
|height|Overrides the height|If not size is defined|0|
|mode|Resizing mode|*Optional*|`Picture::MODE_FIT`|`Picture::MODE_EXACT`
|alt|HTML alt attribute when unable to load the image||
|title|HTML title attribute||
|color|Only used If mode is `Picture::MODE_FILL`|||
|direction|Defines the direction for the image when using mode `Picture::MODE_FIT_DIRECTION`||`Picture::DIRECTION_CENTER`|
|src|Loads image from this filename|If area is not defined|
|class|The class attribute to set for the image element||
|style|Inline styles ||

**Example:**

```html
<!-- Example of hardcoded file path -->

<x-image
  src="1590000000/logo.png"
  :mode="Netflex\Pages\Components\Picture::MODE_FIT"
  width="600"
  height="600"
  title="My Awesome logo!"
  alt="Picture of the awesome logo"
/>

<!-- Example of inline editable image -->

<x-image
  area="logo-image"
  :mode="Netflex\Pages\Components\Picture::MODE_FIT"
  width="600"
  height="600"
  title="My Awesome logo!"
  alt="Picture of the awesome logo"
/>

```

## Inline

The Inline component creates an inline editable content field with rich text editing capabilities.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area|The content field to store the content in|Yes|
|class|Class applied to the rendered content if the `is` parameter is defined|
|style|Inline styles applied to the rendered content if the `is` parameter is defined
|is|Wraps the content in this html element if defined. Otherwise just outputs as a regular text node.

**Example:**

```html
<x-inline area="article-content" />

<!-- Example of wrapping the output in a tag -->

<x-inline area="article-content" is="div" class="article" />
```

## Nav

The Nav component generates a bootstrap compatible navigation menu.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|parent|Specifies the parent Page that the navigation should be generated for. If not specified, all pages from the root level will be included|*Optional*|
|levels|Max depth of the navigation|*Optional*|
|type|||`'nav'`
|root|A prefix that should be prepended to the generated links|*Optional*|

**Example:**

```html
<!-- Generate navigation for all pages -->
<x-nav />

<!-- Generate navigation using a specific page as root -->
<x-nav parent="10000" />
```

## Picture

The Picture comoponent generates a responsive picture element.

The preferred method of configuring the picture component, is through the use of media presets. See [Media Presets](/docs/media.md)

> [!NOTE]
> If the area attribute is used, the picture will be inline editable when editing through the Netflexapp editor. **Inline fields only works when in the context of a Netflex Page**.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area|Load image from file defined in content area|If src is not defined|
|alt|HTML alt attribute when unable to load the image||
|title|HTML title attribute||
|src|Loads image from this filename|If area is not defined|
|mode|Resizing mode||`Picture::MODE_FIT`|
|width|Overrides the width|If preset is not defined|0|
|height|Overrides the height|If preset is not defined|0|
|size|Shorthand for setting both with and height|If preset is not defined or width and height is not defined|{width}x{height}|
|fill|Only used If mode is `Picture::MODE_FILL`|||
|image-class|The class attribute to set for the image element||
|picture-class|The class attribute to set ||
|preset|The Media Preset to use||`default`|
|direction|Defines the direction for the image when using mode `Picture::MODE_FIT_DIRECTION`||`Picture::DIRECTION_CENTER`|

**Example:**

```html
<!-- Example of hardcoded file path using a preset -->

<x-picture
  src="1590000000/main-banner.jpg"
  preset="banner"
  title="My Awesome Banner!"
  alt="Picture of the awesome banner"
/>

<!-- Example of inline editable image using a preset -->

<x-picture
  area="banner"
  preset="banner"
  title="My Awesome Banner!"
  alt="Picture of the awesome banner"
/>

<!-- Example without using preset -->

<x-picture
  src="1590000000/main-banner.jpg"
  :mode="Netflex\Pages\Components\Picture::MODE_FIT"
  width="1920"
  heigt="600"
  title="My Awesome Banner!"
  alt="Picture of the awesome banner"
/>
```

## SEO

The Seo component generates meta tags for page metadata.

It automatically generates the meta tags from the current page context if available. Otherwise it will fallback to the global site meta tags.

**Attributes:**

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|title|Overrides the page meta title|*Optional*|
|description|Overrides the page description|*Optional*|
|images|Generates image meta tags for OpenGraph etc.|*Optional*|`[]`
|suffix|Appends the default meta title|*Optional*|`true`

**Example:**

```html
<!-- Generating the default meta tags -->
<x-seo />

<!-- Overriding the default meta tags -->
<x-seo
  title="My awesome title"
  description="My awesome description"
/>
```

## StaticContent

The StaticContent component is a wrapper for fetching static content defined in the Netflexapp editor.

> [!NOTE]
> If the `block` field is not specified, all blocks from the static content area will be retrieved.

|Name|Description|Required|Default|
|----|-----------|--------|-------|
|area|The name of the static content area|Yes|
|block|Optionally specifies a specific block in the static content area to fetch|*Optional*|
|column|Specifies which internal data type to get the column data from. Usually not required|*Optional*|

**Example:**

```html
<x-static-content area="analytics" />

<!-- Example of specific blocks -->
<x-static-content area="analytics" block="google" />
<x-static-content area="analytics" block="facebook-pixel" />
```
