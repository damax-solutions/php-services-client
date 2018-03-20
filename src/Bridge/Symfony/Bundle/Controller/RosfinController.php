<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Bundle\Controller;

use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Route("/rosfin")
 */
class RosfinController
{
    /**
     * @Route("/catalogue/check")
     *
     * @throws BadRequestHttpException
     */
    public function checkCatalogueAction(Request $request, Client $client): Response
    {
        try {
            $check = $client->checkRosfin(
                $request->query->get('fullName', ''),
                $request->query->get('birthDate')
            );
        } catch (InvalidRequestException $e) {
            throw new BadRequestHttpException('Bad request', $e);
        }

        return JsonResponse::create($check->toArray());
    }
}
