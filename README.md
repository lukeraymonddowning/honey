# Honey
A spam prevention package for Laravel, providing honeypot techniques, ip blocking and beautifully simple 
[Recaptcha](https://developers.google.com/recaptcha) integration. Stop spam. Use Honey.

## Installation
You can install Honey via Composer.

```bash
composer require lukeraymonddowning/honey
```

You should publish Honey's config file using the following [Artisan](https://laravel.com/docs/master/artisan) Command:

```bash
php artisan vendor:publish --tag=honey
```

Honey is now successfully installed!

## Usage
Using Honey couldn't be easier. Go to a `<form>` in your blade files and add `<x-honey/>` as a child element.

```blade
<form action="{{ route('some.route') }}" method="POST">
    @csrf
    <input type="email" placeholder="Your email" required />
    <x-honey/>
</form>
```

Now, in the routes file, add the `honey` middleware to the route that your form points to.

```php
// routes/web.php

Route::post('/test', fn() => event(new RegisterInterest))
    ->middleware(['honey'])
    ->name('some.route');
```

That's it! Your route is now protected from spam. If you want to take it to the next level, read on...

### Recaptcha

Honey makes it a breeze to integrate Google's [Recaptcha](https://developers.google.com/recaptcha) on your Laravel
site. We integrate with Recaptcha v3 for completely seamless and invisible bot prevention. Here's how to get started.

Honey uses [Alpine JS](https://github.com/alpinejs/alpine) to integrate Recaptcha. If you don't want to use Alpine,
you can still use Honey, but you won't be able to use it's Recaptcha integration. Follow Alpine's 30 second install
instructions, then come back here.

Next, you need to go grab a key pair from Google. You can get yours here: 
[https://g.co/recaptcha/v3](https://g.co/recaptcha/v3). Head into your `.env` file, and add your key pair.

```dotenv
# .env

RECAPTCHA_SITE_KEY=YOUR_RECAPTCHA_SITE_KEY
RECAPTCHA_SECRET_KEY=YOUR_RECAPTCHA_SECRET_KEY
```

We're almost there. Head to your blade file and, inside of a `<form>` element, add the `<x-honey-recaptcha/>` component.
We'll use the example from earlier:

```blade
<form action="{{ route('some.route') }}" method="POST">
    @csrf
    <input type="email" placeholder="Your email" required />
    <x-honey/>
    <x-honey-recaptcha/> 
</form>
```

As a note, you can use `<x-honey-recaptcha/>` alongside `<x-honey/>`, or separately. You do you.

You now have 2 options. You can allow Honey to make the Recaptcha request for you and fail automatically if it
detects a bot, or you can do it manually (although its basically magic, so don't worry).

#### Via Middleware

To use Honey's built in Middleware, add `honey-recaptcha` to your route's middleware stack:

```php
// routes/web.php

Route::post('/test', fn() => event(new RegisterInterest))
    ->middleware(['honey', 'honey-middleware'])
    ->name('some.route');
```

Again, you can use the independently of the `honey` middleware if you're only interested in Recaptcha. The middleware
will abort the request by default if things look fishy.

#### Manually

Honey provides you all the power you could ever wish for via the `Honey` Facade. To check the token manually, you may
do the following:

```php
$token = request()->honey_recapture_token;
$response = Honey::recaptcha()->check($token);
```

The response will contain a standard json response as defined in the Recaptcha docs: [https://developers.google.com/recaptcha/docs/v3](https://developers.google.com/recaptcha/docs/v3).
If you want to quickly ascertain if the request is spam based on your configured minimum score, you can use the 
`isSpam` method after calling the `check` method.

```php
$token = request()->honey_recapture_token;
Honey::recaptcha()->check($token);
$probablyABot = Honey::recaptcha()->isSpam();
``` 

