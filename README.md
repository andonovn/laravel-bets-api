# Laravel Bets Api

## Description
A package which helps you use the API of BetsApi. BetsApi is a paid service which provides sports data.
You can find more about them at <a href="https://betsapi.com">https://betsapi.com</a>.

Laravel Bets Api is an unofficial package and not all of the endpoints are covered.
I am just a client of theirs which happens to use their services on few projects
and that motivated me to extract the code into an open-source package.  

## Installation
```shell
composer require andonovn/laravel-bets-api
```

```shell
php artisan vendor:publish --provider="Andonovn\LaravelBetsApi\ServiceProvider"
```

Add the `BETS_API_TOKEN` env variable to your `.env` file. As you may already guessed, that's the BetsApi token
that you can obtain by registering in their website, and then subscribing to a plan of your choice.

You can also optionally add the `BETS_API_ENDPOINT` env variable. By default it is set to `https://api.b365api.com/v1/`

The package utilize the Laravel's package auto-discovery feature. If you use an old version of Laravel that 
does not support that, then you can manually add `Andonovn\LaravelBetsApi\ServiceProvider` to the list of your
service providers which are located in the `/config/app.php`'s `providers` array  

## Usage
Resolve the `Andonovn\LaravelBetsApi\BetsApi` class from the container and use one of the public methods.
Here is an example:
```php
public function index(\Andonovn\LaravelBetsApi\BetsApi $betsApi)
{
    $leagues = $betsApi->soccerLeagues();
    
    // $leagues will now be an array of all the supported leagues in soccer, use it as per your needs
    
    dd($leagues); // take a look at the structure
}
```

## Error handling
An `Andonovn\LaravelBetsApi\Exceptions\CallFailedException` will be raised when an API call fails.
Also, there are few more exceptions which are less likely to occur but you can be safe by handling all
of them by just catching the abstract `Andonovn\LaravelBetsApi\Exceptions\BetsApiException`.
In case you want to learn more about the exceptions, they are all located in the `Andonovn\LaravelBetsApi\Exceptions` namespace.

## Events
An `Andonovn\LaravelBetsApi\Events\RequestFailed` will be dispatched when a request fails.
The package will try to retry the request until it hits the max allowed retries specified in the config.
Note that the event will be raised for every retry that was made. When it hits the attempts limit, it will
throw the `Andonovn\LaravelBetsApi\Exceptions\CallFailedException` exception as described above.

There is one more event that the package raise and that's the `Andonovn\LaravelBetsApi\Events\ResponseReceived`
which you may use to track how many requests you have left, and other similar info that's available in each response.
