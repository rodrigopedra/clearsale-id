<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Address implements XmlEntityInterface
{
    /** @var  string */
    private $street;

    /** @var  string|null */
    private $complement = null;

    /** @var  string */
    private $number;

    /** @var  string */
    private $county;

    /** @var  string */
    private $city;

    /** @var  string */
    private $state;

    /** @var  string */
    private $zipCode;

    /** @var  string */
    private $country;

    public function __construct(
        string $street,
        string $number,
        string $county,
        string $city,
        string $state,
        string $zipCode,
        string $country
    ) {
        $this->setStreet($street);
        $this->setNumber($number);
        $this->setCounty($county);
        $this->setCity($city);
        $this->setState($state);
        $this->setZipCode($zipCode);
        $this->setCountry($country);
    }

    public static function create(
        string $street,
        string $number,
        string $county,
        string $country,
        string $city,
        string $state,
        string $zipCode,
        ?string $complement = null
    ): self {
        $instance = new self(
            $street,
            $number,
            $county,
            $city,
            $state,
            $zipCode,
            $country
        );

        if ($complement) {
            $instance->setComplement($complement);
        }

        return $instance;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $street = \trim($street);

        if (\strlen($street) === 0) {
            throw new RequiredFieldException('Street is required');
        }

        $this->street = $street;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $number = \trim($number);

        if (\strlen($number) === 0) {
            throw new RequiredFieldException('Number is required');
        }

        $this->number = $number;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(string $complement): self
    {
        $this->complement = \trim($complement) ?: null;

        return $this;
    }

    public function getCounty(): string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $county = \trim($county);

        if (\strlen($county) === 0) {
            throw new RequiredFieldException('County is required');
        }

        $this->county = $county;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $city = \trim($city);

        if (\strlen($city) === 0) {
            throw new RequiredFieldException('City is required');
        }

        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $state = \trim($state);

        if (\strlen($state) === 0) {
            throw new RequiredFieldException('State is required');
        }

        $this->state = $state;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $zipCode = \preg_replace('/\D/', '', $zipCode);

        if (\strlen($zipCode) === 0) {
            throw new RequiredFieldException('ZipCode is required');
        }

        $this->zipCode = $zipCode;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('Endereco');

        $XMLWriter->writeElement('Logradouro', $this->street);

        if ($this->complement) {
            $XMLWriter->writeElement('Complemento', $this->complement);
        }

        $XMLWriter->writeElement('Numero', $this->number);
        $XMLWriter->writeElement('Bairro', $this->county);
        $XMLWriter->writeElement('Cidade', $this->city);
        $XMLWriter->writeElement('UF', $this->state);
        $XMLWriter->writeElement('CEP', $this->zipCode);
        $XMLWriter->writeElement('Pais', $this->country);

        $XMLWriter->endElement(); // Endereco
    }
}
