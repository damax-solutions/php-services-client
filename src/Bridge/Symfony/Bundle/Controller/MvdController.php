<?php

declare(strict_types=1);

namespace Damax\Client\Bridge\Symfony\Bundle\Controller;

use Damax\Client\Client;
use Damax\Client\InvalidRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
