# PAYONE Link Example

This is a example application for the [PAYONE Link](https://docs.payone.com/display/public/PLATFORM/Channel+PAYONE+Link])
build with Slim, Guzzle and Twig.

## Install

* Clone the repository
* `composer install`
* Set your PAYONE API Credentials in `.env`
* Serve, for example with `php -S localhost:8080 -t public/`

## Usage

In the web interface, you can list all the links associated with your PAYONE account, as well create new links.

If you want to test out notifications and are running on your local machine, I recommend and 💖 [ngrok.io](https://ngrok.io/)! After serving locally on Port `8080` simply use:

```
user@machine:~$ ngrok http 8080
```

and configure `APPLICATION_BASE_URL` in `.env` according to your ngrok host.