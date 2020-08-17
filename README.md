# PAYONE Link Example

This is a example application for the [PAYONE Link](https://docs.payone.com/display/public/PLATFORM/Channel+PAYONE+Link])
build with Slim, Guzzle and Twig.

## Install

* Clone the repository
* `composer install`
* Set your PAYONE API Credentials in `.env`
* Set up database with `php vendor/bin/doctrine orm:schema-tool:create`
* Serve, for example with `php -S localhost:8080 -t public/`
