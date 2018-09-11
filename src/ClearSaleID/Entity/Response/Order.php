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

    /**
     * Order constructor.
     *
     * @param  int    $id
     * @param  string $score
     * @param  string $status
     * @param  string $quizURL
     */
    public function __construct( $id, $score, $status, $quizURL = '' )
    {
        $this->id     = $id;
        $this->score  = $score;
        $this->status = $status;
        $this->setQuizURL( $quizURL );
    }

    /**
     * @param  string $value
     */
    private function setQuizURL( $value )
    {
        $value = (array)$value;

        if (count( $value ) === 0) {
            $this->quizURL = '';

            return;
        }

        $this->quizURL = trim( $value[ 0 ] );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return floatval( $this->score );
    }

    /**
     * @return string
     */
    public function getQuizURL()
    {
        return $this->quizURL;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return in_array( $this->getStatus(), [ self::STATUS_APPROVED_AUTOMATICALLY ] );
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isRejected()
    {
        return in_array( $this->getStatus(), [
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ] );
    }

    /**
     * @return bool
     */
    public function hasValidStatus()
    {
        return in_array( $this->getStatus(), [
            self::STATUS_APPROVED_AUTOMATICALLY,
            self::STATUS_REJECTED_BY_POLITICS,
            self::STATUS_REJECTED_AUTOMATICALLY,
        ] );
    }
}
