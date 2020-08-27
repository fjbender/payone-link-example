<?php

namespace Fbender\Payonelink\Controller;

use Fbender\Payonelink\Model\LinkExecutionData;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class NotificationController
{
    // Handle notification POST from PAYONE Link API
    public function notifyPost(Request $request, Response $response): Response
    {
        $notify = json_decode($request->getBody(), true);
        if ($notify !== null && $this->verifyNotificationSignature($request)) {
            $linkExecutionData = LinkExecutionData::fromLinkExecutionData($notify['linkExecutionData']);
            // Do something with the data here, e.g. save to Order
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }

    // Verify the signature of a given Notification request
    // N.B. a previous version of the documentation said something about base64 encoding
    // parts of the string to sign, which was not true
    private function verifyNotificationSignature(Request $request): bool
    {
        // see https://docs.payone.com/display/public/PLATFORM/How+to+Verify+Notifications
        $stringToSign = $request->getHeaderLine('X-Request-ID') . ':'
            . hash('sha512', trim($request->getBody()), false);
        $key = hash('sha512', $_ENV['PAYONE_KEY'], false);
        $signature = hash_hmac('sha512', $stringToSign, $key);

        return hash_equals($signature, $request->getHeaderLine('X-Auth-Code'));
    }
}