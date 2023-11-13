<?php
class Reply
{
    public $number;
    public $answer_id;
    public $answer_correctness;
    public $reply_date_time;

    function __construct($answer_id, $answer_correctness, $reply_date_time, $number)
    {
        $this->answer_id = $answer_id;
        $this->answer_correctness = $answer_correctness;
        $this->reply_date_time = $reply_date_time;
        $this->number = $number;
    }
}