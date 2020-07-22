<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ExchangeRateRepository::class)
 * @JMS\ExclusionPolicy("all")
 */
class ExchangeRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @JMS\Expose()
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=10)
     * @JMS\Expose()
     * @JMS\SerializedName("exchange_rate")
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=3)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $currencyCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }
}
