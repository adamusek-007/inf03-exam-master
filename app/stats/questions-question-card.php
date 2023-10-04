<div class="questions-question-card">
    <section>
        <div class="questions-question-content"><a href="?question-id=<?= $question_id ?>">
                <?= $question_content ?>
            </a></div>
        <div class="questions-question-id">ID pytania:
            <?= $question_id ?>
        </div>
        <div class="questions-question-last-viewed">Ostatnio wyświetlono:
            <?= $last_seen ?>
        </div>
        <section class="questions-question-replies">
            <div class="questions-question-total-replies">Udzielono odpowiedzi w sumie:
                <?= $reply_count ?>
            </div>
            <div class="questions-question-correct-replies">Udzielono poprawnych odpowiedzi:
                <?= $correct_replies ?>
            </div>
            <div class="questions-question-incorrect-replies">Udzielono niepoprawnych odpowiedzi:
                <?= $incorrect_replies ?>
            </div>
        </section>
    </section>
    <svg viewBox="0 0 64 64" class="small-pie" style="width: 100px; border-radius: 50%;">
        <circle r="25%" cx="50%" cy="50%"
            style="stroke-dasharray: <?= $incorrect_procentage ?> <?= $correct_procentage ?> ">
        </circle>
        <circle r="25%" cx="50%" cy="50%"
            style="stroke-dasharray: <?= $correct_procentage ?> <?= $incorrect_procentage ?> ; stroke: green; stroke-dashoffset: <?= $correct_procentage ?>">
        </circle>
    </svg>
    <style>
        .small-pie circle {
            fill: none;
            stroke: red;
            stroke-width: 32;
        }
    </style>

    <!-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> -->
</div>