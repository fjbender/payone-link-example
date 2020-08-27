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

## Peculiar things

There are some things that you might find peculiar:

* Templates: As this is the first time I used Twig, the templates don't yet use inheritance. I'll try to learn that an update the project accordingly
* Authentication/Signatures: The API uses different mechanisms for request signature and authentication, depending on the context. The documentation is somewhat short of examples, so the code in this project might be the most sought after part of this:
   * `\Fbender\Payonelink\Service\PayoneLinkService::getBodySignatureForLinkCreation` for create link request signing
   * `\Fbender\Payonelink\Service\PayoneLinkService::getSignatureForLinkList` for get link list request signing
   * `\Fbender\Payonelink\Service\PayoneLinkService::getSignatureForGetLink` for get single link request signing
   * `\Fbender\Payonelink\Controller\NotificationController::verifyNotificationSignature` for validating the notification signature
* Pagination in lists: Due to a bug in the PAYONE Link API, pagination is not yet supported