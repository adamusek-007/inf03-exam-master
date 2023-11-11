<?php
class FormFieldsValidator
{
    private bool $data_completition;

    public static array $expected_data_fields =
        [
            "content",
            "c-answer",
            "w-answer-1",
            "w-answer-2",
            "w-answer-3"
        ];

    public function get_data_completition(): bool
    {
        return $this->data_completition;
    }
    private function check_data_completion(): void
    {
        foreach ($this::$expected_data_fields as $field_name) {
            if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
                $this->data_completition = FALSE;
                return;
            }
        }
        $this->data_completition = TRUE;
    }
    public function __construct()
    {
        $this->check_data_completion();
    }
}