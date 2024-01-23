# Executives Place - A Laravel 10 API Exercise

## Scenario

A digital freelance site where users post some basic info about themselves
and an hourly rate in a currency of their choosing. Anyone can view a specific
user on the platform and view their hourly rate in any currency

## Task

Small application that allows you to create users with their hourly rate information and
display any specific user with their hourly rate information converted into any currency provided.

*This app will only deal with EUR, USD and GBP*

## requirements
* Database that stores basic user information with hourly rate and currency
* No authentication
* No frontend - just JSON api routes
* Api resource controller to show, create, update and delete users
* Show method should accept a currency and the user's hourly rate should be shown in this currency
* The hourly rate conversion should be done through a local driver OR a third party API driver
* Switching between drivers should be managed through an environment variable, and default to local driver if not defined
* Local driver should use the following exchange rates:
* * GBP – USD (1.3)
* * GBP – EUR (1.1)
* * EUR – GBP (0.9)
* * EUR – USD (1.2)
* * USD – GBP (0.7)
* * USD – EUR (0.8)
* Third party api  driver should make a call to a currency exchange in order to get the correct exchange rate
* Bonus - some PHPUnit tests to make sure the application is working properly

## Quickstart

* Clone repo to chosen local directory
* Run `composer install` from local diretcory
* Copy `.env.example` to `.env`
* Run `php artisan key:generate`
* Make sure db of choice (Tested with MySQL) is up and running
* Update .env has required DB_CONNECTION/DB_USERNAME/DB_PASSWORD details
* Run `php artisan migrate --seed`
* serve locally to test (`php artisan serve` from project directory)

## Solution details
### Switching between rate drivers
Add the following config keys to .env to enable third party API rate driver (exchangeratesapi.io service - sign up for key)

`RATES_DRIVER=exchangeratesapi`

`RATES_API_KEY=[API KEY HERE]`

*Note:* The free tier of exchangeratesapi doesn't allow switching of base rates, so a user with a native currency of EUR will need
to be used to test functionality

### Converting user currency
show a user's native rate/currency by navigating to the show resource e.g. `http://127.0.0.1:8000/api/users/3` - To view a converted rate
add currency ISO code to the end of the url (GBP, USD, EUR) e.g. `http://127.0.0.1:8000/api/users/3/USD`

### Rate driver
The local and api exchange rate drivers are driven by services in the `App\Services` directory and registered in AppServiceProvider.  `RatesLocal`
and `RatesExchangeratesapi` both implement the `Rates` interface to provide a service to retrieve the exhange rates of a base currency into an exhange rate.

## API Resources
There is a resource route in the `api.php` routes file and resource controller (index,store,show,update,destroy) to route standard CRUD endpoints to the controller with
minimal bloat to the routes file. The GET route is overriden, so that an extra optional parameter can be passed to show a user with converted rate. `UserResource` and `UserCollection` resource files have been created to define the structure of JSON responses for the User model.

### Schema
* The default `Users` table in laravel has been extended to include `rate_hour` and `currency_iso` to store user rate information.
* The `Currencies` table stores the currencies that the app can work with, ensuring only they can be applied to a user
* The `Rates` table stores the local currency exchange rates

### Tests
There are a few feature tests to check the application endpoints and services and working.  Run them with `php artisan test`