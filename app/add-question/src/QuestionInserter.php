<?php
class QuestionInserter
{
    public static string $file_upload_dir = "../resources/images/";

    public function __construct(FormFieldsValidator $form_validator)
    {
        if ($form_validator->get_data_completion()) {
            $this->internal_proceed();
        } else {
            $this->create_response(FALSE, "Wymagane pola nie sa wypełnione.");
        }
    }
    private function internal_proceed(): void
    {
        $image = new Image();

        if ($image->get_attachness()) {
            if ($image->get_type_correctness()) {
                $image->upload();
                $this->insert_data(TRUE);
            } else {
                $this->create_response(FALSE, "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG,
JPG.");
            }
        } else {
            $this->insert_data(FALSE);
        }
    }
    private function insert_data(bool $has_image): void
    {
        try {
            $connection = get_database_connection();
            $connection->query($this->get_question_insert_query($has_image));
            $question_id = $this->get_latest_question_id();
            $connection->query($this->get_answers_insert_query($question_id));
            $this->create_response(TRUE, "Pytanie pomyślnie dodane do bazy danych");
        } catch (Exception $e) {
            $this->create_response(FALSE, "Wystąpił błąd: {$e}");
        }
    }
    private function create_response(bool $status, string $message): void
    {
        $response = array(
            "status" => $status,
            "message" => $message
        );
        $JSON_response = json_encode($response);
        echo $JSON_response;

    }
    private function get_question_insert_query(bool $has_image): string
    {
        $add_question_query = "CALL addQuestion(\"%s\", %d, \"%s\");";
        if ($has_image) {
            return sprintf($add_question_query, $_POST['content'], true, $_FILES["image"]["name"]);
        } else {
            return sprintf($add_question_query, $_POST['content'], false, 'NULL');
        }
    }
    private function get_answers_insert_query(int $question_id): string
    {
        $query = "";
        $q_add_answer = "CALL addAnswer(%d, \"%s\", %d);";
        $answers_fields = ["c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];
        foreach ($answers_fields as $answer) {
            if ($answer == "c-answer") {
                $query = $query . sprintf($q_add_answer, $question_id, $_POST[$answer], 1);
            } else {
                $query = $query . sprintf($q_add_answer, $question_id, $_POST[$answer], 0);
            }
        }
        return $query;

    }
    private function get_latest_question_id(): int
    {
        $connection = get_database_connection();
        $row = $connection->query("CALL getLatestAddedQuestionId();")->fetch(PDO::FETCH_ASSOC);
        return intval($row['id']);
    }
}