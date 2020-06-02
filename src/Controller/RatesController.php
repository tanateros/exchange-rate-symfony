<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Service\RateService;
use App\ValueObject\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RatesController extends AbstractController
{
    protected const ROUND_RESPONSE_PRECISION = 6;

    /**
     * @Route("/rate/get", name="rates")
     * @param Request $request
     * @return JsonResponse
     */
    public function getRate(Request $request): JsonResponse
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $amount = $request->query->get('amount');

        if (null === $from || null === $to  || !is_string($from) || !is_string($to)) {
            return $this->json([
                'rate' => Rate::DEFAULT_RATE_VALUE,
            ]);
        }

        $rateRepository = $this->getDoctrine()
            ->getRepository(Rate::class);
        $source = $this->getParameter('source_default');
        $rateService = new RateService($rateRepository, $source);

        return $this->json([
            'rate' => round($rateService->getRate(
                new Currency($from), new Currency($to), $amount
            ), self::ROUND_RESPONSE_PRECISION),
        ]);
    }
}
