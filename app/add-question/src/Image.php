<?php

class Image
{
    public static array $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    private bool|null $is_attached = null;
    private bool|null $is_type_correct = null;
    private string $name = "";
    public function get_attachness(): bool
    {
        return $this->is_attached;
    }
    public function get_type_correctness(): bool
    {
        return $this->is_type_correct;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    private function check_is_image_attached(): void
    {
        $this->is_attached = (
            array_key_exists("image", $_FILES) &&
            array_key_exists("type", $_FILES["image"]) &&
            $_FILES["image"]["type"] !== ""
        );
    }
    private function check_is_type_correct(): void
    {
        $uploaded_file_type = $_FILES["image"]["type"];
        $this->is_type_correct = in_array($uploaded_file_type, Image::$supported_file_types);
    }
    public function upload(): void
    {
        $target_file_dir = QuestionInserter::$file_upload_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_dir);
    }
    public function __construct()
    {
        $this->check_is_image_attached();
        if ($this->get_attachness()) {
            $this->check_is_type_correct();
            if ($this->get_type_correctness()) {
                $this->name = $_FILES["image"]["name"];
            }
        }
    }
}