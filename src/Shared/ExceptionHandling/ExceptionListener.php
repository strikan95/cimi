<?php

namespace App\Shared\ExceptionHandling;

use App\Shared\ExceptionHandling\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        // Get incoming request
        $request = $event->getRequest();

        // Check if it is a rest api request
        if ('application/json' === $request->headers->get('Content-Type')) {
            // Customize your response object to display the exception details
            $responseMsg = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
                'traces' => $exception->getTrace(),
            ];

            if ($exception instanceof ValidationException) {
                $responseMsg['error']['details'] = $exception->getDetails();
            }

            $response = new JsonResponse($responseMsg);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }
}
