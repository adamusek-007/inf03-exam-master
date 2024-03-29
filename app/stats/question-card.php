<div class="top-section">
    <p id="question-content"><?= $question_content ?></p>
    <?php if (!is_null($question_image)) {
        $img_tag = "<img src=\"../resources/images/{$question_image}\">";
        echo $img_tag;
    } ?>
    <section class="answers-section">
        <?php foreach ($question_answers as $answer): ?>
            <div class="answer-block">
                <div class="answer-id"><?= $answer->id ?></div>
                <div class="answer-content"><?= $answer->content ?></div>
                <div class="answer-correctness"><?= $answer->is_correct ?></div>
            </div>
        <?php endforeach ?>
    </section>
</div>
<div class="mid-section">
    <!-- TODO Line chart -->
</div>
<div class="bottom-section">
    <table>
        <thead>
        <tr>
            <th>Lp.</th>
            <th>Poprawność</th>
            <th>Numer odpowiedzi</th>
            <th>Data i czas odpowiedzi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($replies as $reply): ?>
            <td><?= $reply->number ?></td>
            <td><?= $reply->answer_correctness; ?></td>
            <td><?= $reply->answer_id; ?></td>
            <td><?= $reply->reply_date_time; ?></td>
        <?php endforeach ?>
        </tbody>
    </table>
    <style>
        #question-content {
            font-size: 2em;
        }
    </style>
</div>