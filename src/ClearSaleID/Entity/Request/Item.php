<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Item implements XmlEntityInterface
{
    /** @var  string */
    private $id;

    /** @var  string */
    private $name;

    /** @var  float */
    private $value;

    /** @var  int */
    private $quantity;

    /** @var  string|null */
    private $notes = null;

    /** @var  int|null */
    private $categoryId = null;

    /** @var  string|null */
    private $categoryName = null;

    public function __construct(string $id, string $name, float $value, int $quantity)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setValue($value);
        $this->setQuantity($quantity);
    }

    public static function create(string $id, string $name, float $value, int $quantity): self
    {
        return new self($id, $name, $value, $quantity);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $id = \trim($id);

        if (\strlen($id) === 0) {
            throw new RequiredFieldException('Id is required');
        }

        $this->id = $id;

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

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = \trim($notes) ?: null;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = \trim($categoryName) ?: null;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('Item');

        $XMLWriter->writeElement('CodigoItem', $this->id);
        $XMLWriter->writeElement('NomeItem', $this->name);
        $XMLWriter->writeElement('ValorItem', $this->value);
        $XMLWriter->writeElement('Quantidade', $this->quantity);

        if ($this->notes) {
            $XMLWriter->writeElement('Generico', $this->notes);
        }

        if ($this->categoryId) {
            $XMLWriter->writeElement('CodigoCategoria', $this->categoryId);
        }

        if ($this->categoryName) {
            $XMLWriter->writeElement('NomeCategoria', $this->categoryName);
        }

        $XMLWriter->endElement(); // Item
    }
}
