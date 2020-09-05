<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Connection implements XmlEntityInterface
{
    /** @var  string */
    private $company;

    /** @var  string */
    private $flightNumber;

    /** @var  \DateTimeInterface */
    private $flightDate;

    /** @var  string */
    private $class;

    /** @var  string */
    private $from;

    /** @var  string */
    private $to;

    /** @var  \DateTimeInterface */
    private $departureDate;

    /** @var  \DateTimeInterface */
    private $arrivalDate;

    public function __construct(
        string $company,
        string $flightNumber,
        \DateTimeInterface $flightDate,
        string $class,
        string $from,
        string $to,
        \DateTimeInterface $departureDate,
        \DateTimeInterface $arrivalDate
    ) {
        $this->setCompany($company);
        $this->setFlightNumber($flightNumber);
        $this->setFlightDate($flightDate);
        $this->setClass($class);
        $this->setFrom($from);
        $this->setTo($to);
        $this->setDepartureDate($departureDate);
        $this->setArrivalDate($arrivalDate);
    }

    public static function create(
        string $company,
        string $flightNumber,
        \DateTimeInterface $flightDate,
        string $class,
        string $from,
        string $to,
        \DateTimeInterface $departureDate,
        \DateTimeInterface $arrivalDate
    ): self {
        return new self($company, $flightNumber, $flightDate, $class, $from, $to, $departureDate, $arrivalDate);
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $company = \trim($company);

        if (\strlen($company) === 0) {
            throw new RequiredFieldException('Company is required');
        }

        $this->company = $company;

        return $this;
    }

    public function getFlightNumber(): string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): self
    {
        $flightNumber = \trim($flightNumber);

        if (\strlen($flightNumber) === 0) {
            throw new RequiredFieldException('Flight Number is required');
        }

        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getFlightDate(): \DateTimeInterface
    {
        return $this->flightDate;
    }

    public function setFlightDate(\DateTimeInterface $flightDate): self
    {
        $this->flightDate = $flightDate;

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $class = \trim($class);

        if (\strlen($class) === 0) {
            throw new RequiredFieldException('Class is required');
        }

        $this->class = $class;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): self
    {
        $from = \trim($from);

        if (\strlen($from) === 0) {
            throw new RequiredFieldException('From is required');
        }

        $this->from = $from;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): self
    {
        $to = \trim($to);

        if (\strlen($to) === 0) {
            throw new RequiredFieldException('To is required');
        }

        $this->to = $to;

        return $this;
    }

    public function getDepartureDate(): \DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeInterface $departureDate): self
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getArrivalDate(): \DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $arrivalDate): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('Conexao');

        $XMLWriter->writeElement('Companhia', $this->company);
        $XMLWriter->writeElement('NumeroVoo', $this->flightNumber);
        $XMLWriter->writeElement('DataVoo', $this->flightDate->format(self::DATE_TIME_FORMAT));
        $XMLWriter->writeElement('Classe', $this->class);
        $XMLWriter->writeElement('Origem', $this->from);
        $XMLWriter->writeElement('Destino', $this->to);
        $XMLWriter->writeElement('DataPartida', $this->departureDate->format(self::DATE_TIME_FORMAT));
        $XMLWriter->writeElement('DataChegada', $this->arrivalDate->format(self::DATE_TIME_FORMAT));

        $XMLWriter->endElement(); // Conexao
    }
}
