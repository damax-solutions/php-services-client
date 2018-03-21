<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Bundle\Controller;

use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Route("/mvd")
 */
class MvdController
{
    /**
     * @Route("/passports/check")
     *
     * @throws BadRequestHttpException
     */
    public function checkPassportAction(Request $request, Client $client): Response
    {
        try {
            $check = $client->checkPassport($request->query->get('input', ''));
        } catch (InvalidRequestException $e) {
            throw new BadRequestHttpException('Bad request', $e);
        }

        return JsonResponse::create($check->toArray());
    }

    /**
     * @Route("/passports/check/download")
     *
     * @throws BadRequestHttpException
     */
    public function downloadPassportCheckAction(Request $request, Client $client): Response
    {
        try {
            $response = $client->downloadPassportCheck($request->query->get('input', ''));
        } catch (InvalidRequestException $e) {
            throw new BadRequestHttpException('Bad request', $e);
        }

        $fn = function () use ($response) {
            echo $response->getBody()->getContents();
        };

        return (new StreamedResponse($fn, 200, $response->getHeaders()))
            ->setProtocolVersion($response->getProtocolVersion())
            ->setStatusCode($response->getStatusCode())
        ;
    }
}
