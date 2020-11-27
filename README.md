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
    <button type="submit">Subscribe!</button>
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
    <button type="submit">Subscribe!</button>
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

The response will return a `RecaptchaResponse` object with properties as defined in the Recaptcha docs: [https://developers.google.com/recaptcha/docs/v3](https://developers.google.com/recaptcha/docs/v3).
This class implements `ArrayAccess`, so you can use array syntax as if you were working with a JSON response.

```php
$token = request()->honey_recapture_token;
$score = Honey::recaptcha()->check($token)['score'];
```

If you want to quickly ascertain if the request is spam based on your configured minimum score, you can use the 
`isSpam` method after calling the `check` method.

```php
$token = request()->honey_recapture_token;
$probablyABot = Honey::recaptcha()->check($token)->isSpam();
``` 
### Configuring Honey
Honey is built with a great set of defaults, but we understand that one size rarely fits all. That's why we provide
plenty of config options for you. You can access them from the `honey.php` config file. Let's look at the
different options available to you.

#### Features
You can disable or enable global features provided by Honey simply by adding or removing them from the `features` array. 
Here are the features on offer:

##### Spammer IP Tracking
When enabled, Honey will add a `spammers` migration to your database. Any time somebody fails the spam check, their
IP address is added to the `spammers` table. If you don't want Honey to track this, simply disable the feature.

##### Block Spammers Globally
If spammer IP tracking is enabled, Honey can go one step further. By default, it registers global middleware that
will block any IP address in the `spammers` table that has hit the `maximum_attempts` defined further down in the
config file. If you would like more granular control or wish to remove this functionality entirely, simply disable the feature.

#### Checks
Each time the `honey` middleware is run or `Honey::check()` is called, Honey runs through an array of checks to determine
if the request is spam. You can tailor which checks are to be run by adding or removing items in the `checks` array.

##### User is blocked spammer check
This requires the `spammerIpTracking` feature to be enabled to take effect. If an IP address is recorded as hitting the
`maximum_attempts` of spamming defined further down in the config file, it will fail.

##### Present but empty check
When you include the `<x-honey/>` blade directive, Honey adds a hidden input to your form. If a bot fills this input
out, or removes the input from the request, this check will fail.

##### Minimum time passed check 
When you include the `<x-honey/>` blade directive, Honey adds a hidden input to your form with the current time in it as an encrypted value. 
If the form is submitted faster than defined in the `minimum_time_passed` config entry, or removes the input from the request, 
this check will fail.

##### Alpine input filled check
This check is disabled by default, because not everybody uses Alpine JS, but if you do, you should enable it. 
When you include the `<x-honey/>` blade directive, Honey adds a hidden input to your form. It starts empty, but after
the time specified in the `minimum_time_passed` config entry, Alpine JS fills the input with an encrypted value.
If the input has been filled out with a different value, or has no value, the check will fail.

#### Minimum time passed
If you have the minimum time passed check or the alpine input filled check enabled, both checks will use this value
to determine, in seconds, the minimum amount of time that should pass from the page loading until the form can be 
submitted. Forms that are submitted more quickly than this will fail the spam check.

#### Spammer blocking
If you have the `spammerIpTracking` feature enabled, you can configure the options for it here. The `table_name`
entry allows you to change the name of the database table if it conflicts with something else in your application.
The `maximum_attempts` entry defines the maximum number of times an IP address can be recorded as spam before they
are blocked from the site. We recommend setting a value higher than 1 to account for occasional mistakes.

#### Input name selectors
By default, Honey uses the `static` driver to decide on input names. If you would like to change the name of each
input, you may do so here. We recommend altering these from the default values to prevent learned bot behaviour across
sites.

#### Recaptcha
Here you can define your key pair if you don't want to do it in the env file. You may also alter the minimum score
(between 0 and 1), that a user must get back from Recaptcha to avoid being classed as spam.

## Testing

Honey has a full test suite. Go ahead and run it for yourself!

```bash
php vendor/bin/phpunit tests
```

## Accreditations

My main impetus for creating this package came after watching Jefferey Way's brilliant course on Spam prevention
on Laracasts. If you don't have a Laracasts subscription, you should get one.
