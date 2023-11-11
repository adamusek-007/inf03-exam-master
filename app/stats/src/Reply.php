<?php
class Reply
{
    public $number;
    public $answer_id;
    public $answer_correcness;
    public $reply_date_time;

    function __construct($answer_id, $answer_correctnes, $reply_date_time, $number)
    {
        $this->answer_id = $answer_id;
        $this->answer_correcness = $answer_correctnes;
        $this->reply_date_time = $reply_date_time;
        $this->number = $number;
    }
}