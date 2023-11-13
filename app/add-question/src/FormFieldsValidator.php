<?php
class FormFieldsValidator
{
    private bool $data_completion;

    public static array $expected_data_fields =
        [
            "content",
            "c-answer",
            "w-answer-1",
            "w-answer-2",
            "w-answer-3"
        ];

    public function get_data_completion(): bool
    {
        return $this->data_completion;
    }
    private function check_data_completion(): void
    {
        foreach ($this::$expected_data_fields as $field_name) {
            if (empty($_POST[$field_name])) {
                $this->data_completion = FALSE;
                return;
            }
        }
        $this->data_completion = TRUE;
    }
    public function __construct()
    {
        $this->check_data_completion();
    }
}