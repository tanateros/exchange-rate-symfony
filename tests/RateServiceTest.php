<?php

namespace App\Tests;

use App\DBAL\EnumRateSourceType;
use App\Entity\Rate;
use App\Repository\RateRepository;
use App\Service\RateService;
use App\ValueObject\Currency;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RateServiceTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $source;

    protected function setUp()
    {
        $container = self::bootKernel()->getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->source = $container->getParameter('source_default');
    }

    public function testGetRate(): void
    {
        /** @var RateRepository $rateRepository */
        $rateRepository = $this->entityManager
            ->getRepository(Rate::class);
        $from = new Currency('USD');
        $to = new Currency(
            $this->source === EnumRateSourceType::CBR ? 'RUB' : 'EUR'
        );

        /** @var Rate|null $rate */
        $rate = $rateRepository->findOneBy([
            'source' => $this->source,
            'currency_from' => $from,
            'currency_to' => $to,
        ]);

        if ($rate !== null) {
            $serviceResult = new RateService($rateRepository, $this->source);
            $this->assertEquals(
                $serviceResult->getRate($from, $to, 1),
                $rate->getRate()
            );
        }
    }
}
