# Creating Emails

The Netflex SDK has a custom driver that allows you to use Laravel's **[Mailable](https://laravel.com/docs/8.x/mail)**.

The default site template includes this driver. If you use a different template, or have modified the default depenendencies, please install the driver.

```bash
composer require netflex/notifications
```

## Creating a Mailable

```bash
php artisan make:mail HelloWorld
```

This will create a Mailable classin `app/Mail/HelloWorld.php`

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Netflex\Render\Mail\MJML;

class HelloWorld extends Mailable
{
    use MJML, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('example.view');
    }
}
```

The `build()` method returns the View that should be sent as the mail body.

The view can be any regular blade views, and is used exactly like in Controllers and Components.
Data that should be exposed to the view, is passed as a associative array.

The view builder is also used to add attachments, and set subjects etc.

```php
public function build()
{
    $data = [
        'message' => 'Hello World'
    ];

    return $this->view('example.view', $data)
        ->subject('Hello World')
        ->attach('path.to.file', ['as' => 'file.txt']);
}
```

## Sending the Mailable

To send the Mailable, you use the Mail facade in Laravel, pass in the receivers, and use the `send()` method to give it your Mailable instance.

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloWorld;

Mail::to('example@example.com')
    ->send(new HelloWorld);
```

The `to()` method accepts various different values.
You can pass an email string directly, or an array of email strings.
You can also pass one, or an array of `Netflex\Customers\Customer` instanses.
This will also correctly set the receiver name in  the mail.

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloWorld;

use App\Models\User;

Mail::to(['example1@example.com', 'example2@example.com'])
    ->send(new HelloWorld);

$user = User::resolve('example3@example.com');
Mail::to($user)->send(new HelloWorld);

$users = User::where('mail', 'like', '*@example.com');
Mail::to($users)->send(new HelloWorld);
```

## Using MJML templating

In later versions of the `netflex/notifications` package, the `netflex/renderer` package should already be included. If it is missing, please install it.

```bash
composer require netflex/renderer
```

As long as you use the `Netflex\Render\Mail\MJML` trait in your Mailable, you can use the [MJML](https://mjml.io/) framework directly as your mail template.

```php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Netflex\Render\Mail\MJML;

class HelloWorld extends Mailable
{
    use MJML, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->mjml('mail.example');
    }
}
```

To create a mjml template, you just put a file in `resources/views` directory like usual. But you will omit the `.blade.php` file extension, and instead simply name the file `.mjml`.

You can still use regular blade syntax in the template itself, including the component tag syntax; &lt;x-component&gt;.

A shorthand to create a MJML mailable and the associated view:

```bash
php artisan make:mail MyMail --mjml=mail.my-mail
```

The will generate `app/Mail/MyMail.php` and `resources/views/mail/my-mail.mjml`.

## Common issues

### Mail is not sent

Ensure that the driver in `app/config/mail.php` is set to `'netflex'`.

### Mail has wrong sender

The default `app/config/mail.php` config is set up to read the sender name and from adress from `.env`. If the key is missing in `.env`, it falls back to using example values.

We recommend explicitly setting the fallback values to `NULL` when using the `'netflex'` mail driver. The from name and adress will then be controlled from Netflexapp instead, allowing the customer to change this without having to modify the code at a later stage.

You can then still set a test adress and name in your `.env`, but in develop and production branches it will use the value from Netflexapp unless otherwise specified.

```php
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', null),
        'name' => env('MAIL_FROM_NAME', null),
    ],
```