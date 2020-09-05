<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

class Order
{
    public const STATUS_APPROVED_AUTOMATICALLY = 'APA';
    public const STATUS_REJECTED_BY_POLITICS = 'RPP';
    public const STATUS_REJECTED_AUTOMATICALLY = 'RPA';

    /** @var  string */
    private $id;

    /** @var  float */
    private $score;

    /** @var  string */
    private $status;

    /** @var  string|null */
    private $quizURL;

    public function __construct(string $id, float $score, string $status, $quizURL = null)
    {
        $this->id = $id;
        $this->score = $score;
        $this->status = $status;
        $this->setQuizURL($quizURL);
    }

    private function setQuizURL($value): void
    {
        $value = (array) $value;

        if (\count($value) === 0) {
            $this->quizURL = null;
        } else {
            $this->quizURL = \trim($value[0]) ?: null;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getQuizURL(): ?string
    {
        return $this->quizURL;
    }

    public function isApproved(): bool
    {
        return $this->getStatus() === self::STATUS_APPROVED_AUTOMATICALLY;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isRejected(): bool
    {
        return \in_array($this->getStatus(), [
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ]);
    }

    public function hasValidStatus(): bool
    {
        return \in_array($this->getStatus(), [
            self::STATUS_APPROVED_AUTOMATICALLY,
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ]);
    }
}
