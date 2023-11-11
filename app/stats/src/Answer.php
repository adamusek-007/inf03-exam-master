<?php
class Answer
{
    public $id;
    public $content;
    public $is_correct;
    function __construct($id, $content, $is_correct)
    {
        $this->id = $id;
        $this->content = $content;
        $this->is_correct = $is_correct;
    }
}