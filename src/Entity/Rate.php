<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=RateRepository::class)
 * @ORM\Table(name="rate",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="rate_unique",
 *            columns={"source", "currency_from", "currency_to"})
 *    }
 * )
 */
class Rate
{
    const DEFAULT_RATE_VALUE = 0.0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency_from;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency_to;

    /**
     * @ORM\Column(type="enumsource")
     */
    private $source;

    /**
     * @ORM\Column(type="float")
     */
    private $rate;

    public function __construct()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currency_from;
    }

    public function setCurrencyFrom(string $currency_from): self
    {
        $this->currency_from = $currency_from;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currency_to;
    }

    public function setCurrencyTo(string $currency_to): self
    {
        $this->currency_to = $currency_to;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function equals(Rate $rate): bool
    {
        return $rate->getCurrencyFrom() === $this->getCurrencyFrom()
            && $rate->getCurrencyTo() === $this->getCurrencyTo()
            && $rate->getSource() === $this->getSource()
            && $rate->getRate() === $this->getRate();
    }
}
