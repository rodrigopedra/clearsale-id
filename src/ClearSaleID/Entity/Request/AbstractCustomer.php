<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

abstract class AbstractCustomer implements XmlEntityInterface
{
    public const TYPE_PESSOA_FISICA = 1;
    public const TYPE_PESSOA_JURIDICA = 2;
    public const SEX_MASCULINE = 'M';
    public const SEX_FEMININE = 'F';

    protected static $customerTypes = [
        self::TYPE_PESSOA_FISICA,
        self::TYPE_PESSOA_JURIDICA,
    ];

    protected static $sexTypes = [
        self::SEX_MASCULINE,
        self::SEX_FEMININE,
    ];

    /** @var  string */
    protected $id;

    /** @var  int */
    protected $type;

    /** @var  string */
    protected $legalDocument1;

    /** @var  string|null */
    protected $legalDocument2 = null;

    /** @var  string */
    protected $name;

    /** @var  string|null */
    protected $email = null;

    /** @var  string|null */
    protected $sex = null;

    /** @var  \DateTimeInterface|null */
    protected $birthDate = null;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Address */
    protected $address;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Phone[] */
    protected $phones = [];

    public function __construct(
        string $id,
        string $type,
        string $legalDocument,
        string $name,
        Address $address,
        Phone $phone
    ) {
        $this->setId($id);
        $this->setType($type);
        $this->setLegalDocument1($legalDocument);
        $this->setName($name);
        $this->setAddress($address);
        $this->addPhone($phone);
    }

    public static function create(
        string $id,
        string $type,
        string $legalDocument,
        string $name,
        Address $address,
        Phone $phone,
        ?\DateTimeInterface $birthDate = null
    ): self {
        $instance = new static($id, $type, $legalDocument, $name, $address, $phone);

        if ($birthDate) {
            $instance->setBirthDate($birthDate);
        }

        return $instance;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $id = \trim($id);

        if (\strlen($id) === 0) {
            throw new RequiredFieldException('Customer ID is required');
        }

        $this->id = $id;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (! \in_array($type, self::$customerTypes)) {
            throw new \InvalidArgumentException(\sprintf('Invalid type (%s)', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getLegalDocument1(): string
    {
        return $this->legalDocument1;
    }

    public function setLegalDocument1(string $legalDocument1): self
    {
        $legalDocument1 = \preg_replace('/\D/', '', $legalDocument1);

        if (\strlen($legalDocument1) === 0) {
            throw new RequiredFieldException('Legal Document is required');
        }

        $this->legalDocument1 = $legalDocument1;

        return $this;
    }

    public function getLegalDocument2(): ?string
    {
        return $this->legalDocument2;
    }

    public function setLegalDocument2(string $legalDocument2): self
    {
        $this->legalDocument2 = \preg_replace('/\D/', '', $legalDocument2) ?: null;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $name = \trim($name);

        if (\strlen($name) === 0) {
            throw new RequiredFieldException('Name is required');
        }

        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = \trim($email) ?: null;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        if (! \in_array($sex, self::$sexTypes)) {
            throw new \InvalidArgumentException(\sprintf('Invalid sex (%s)', $sex));
        }

        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Phone[]
     */
    public function getPhones(): array
    {
        return $this->phones;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Phone|\RodrigoPedra\ClearSaleID\Entity\Request\Phone[]  $phones
     * @return self
     */
    public function setPhones($phones): self
    {
        $phones = \is_iterable($phones) ? $phones : [$phones];

        foreach ($phones as $phone) {
            $this->addPhone($phone);
        }

        if (\count($this->phones) === 0) {
            throw new RequiredFieldException('Customer object requires at least one Phone');
        }

        return $this;
    }

    public function addPhone(Phone $phone): self
    {
        $this->phones[] = $phone;

        return $this;
    }

    abstract protected function getXMLWrapperElement(): string;

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement($this->getXMLWrapperElement());

        $XMLWriter->writeElement('UsuarioID', $this->id);
        $XMLWriter->writeElement('TipoUsuario', $this->type);
        $XMLWriter->writeElement('DocumentoLegal1', $this->legalDocument1);

        if ($this->legalDocument2) {
            $XMLWriter->writeElement('DocumentoLegal2', $this->legalDocument2);
        }

        $XMLWriter->writeElement('Nome', $this->name);

        if ($this->email) {
            $XMLWriter->writeElement('Email', $this->email);
        }

        if ($this->sex) {
            $XMLWriter->writeElement('Sexo', $this->sex);
        }

        if ($this->birthDate) {
            $XMLWriter->writeElement('Nascimento', $this->birthDate->format(self::DATE_TIME_FORMAT));
        }

        $this->address->toXML($XMLWriter);

        $XMLWriter->startElement('Telefones');

        foreach ($this->phones as $phone) {
            $phone->toXML($XMLWriter);
        }

        $XMLWriter->endElement(); // Telefones

        $XMLWriter->endElement(); // $this->getXMLWrapperElement()
    }
}
