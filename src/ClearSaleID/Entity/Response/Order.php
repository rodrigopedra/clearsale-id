<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

class Order
{
    const STATUS_APPROVED_AUTOMATICALLY = 'APA';
    const STATUS_REJECTED_BY_POLITICS   = 'RPP';
    const STATUS_REJECTED_AUTOMATICALLY = 'RPA';

    /** @var  string */
    private $id;

    /** @var  int */
    private $score;

    /** @var  string */
    private $status;

    /** @var  string */
    private $quizURL;

    public function __construct( $id, $score, $status, $quizURL = '' )
    {
        $this->id     = $id;
        $this->score  = $score;
        $this->status = $status;
        $this->setQuizURL( $quizURL );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getScore()
    {
        return floatval( $this->score );
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getQuizURL()
    {
        return $this->quizURL;
    }

    public function isApproved()
    {
        return in_array( $this->getStatus(), [ self::STATUS_APPROVED_AUTOMATICALLY ] );
    }

    public function isRejected()
    {
        return in_array( $this->getStatus(), [
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ] );
    }

    public function hasValidStatus()
    {
        return in_array( $this->getStatus(), [
            self::STATUS_APPROVED_AUTOMATICALLY,
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ] );
    }

    private function setQuizURL( $value )
    {
        $value = (array)$value;

        if (count( $value ) === 0) {
            $this->quizURL = '';

            return;
        }

        $this->quizURL = trim( $value[ 0 ] );
    }
}
