<div class="top-section">
    <p><?=$question_content?></p>
    <?php if(!is_null($question_image)) {
        echo "<img src=\"../resources/images/{$question_image}\">";
    } ?>
    <section>
        <?php foreach($question_answers as $question_answer):?>
            <p class="answer">$question_answer</p>
        <?php endforeach?>
    </section>
</div>
<div class="mid-section">
    <svg>
    <polyline
     fill="none"
     stroke="#0074d9"
     stroke-width="3"
     points="
       0,120
       20,60
       40,80
       60,20"/>
    </svg>
</div>
<div class="bottom-section">
    <table>
        <thead>
            <th>Lp.</th>
            <th>Numer odpowiedzi</th>
            <th>Data i czas odpowiedzi</th>
        </thead>
        <tbody>
            <?php foreach($replies as $reply):?>
                $reply->reply_date_time;
                $reply->answer_correcness;
                $reply->answer_id;
            <?php endforeach ?>
        </tbody>
    </table>
</div>