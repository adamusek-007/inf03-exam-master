<header>
    <section>
    <div>Poprawnych odpowiedzi: <?=$total_correct_replies?></div>
    <div>Nie poprawnych odpowiedzi: <?=$total_incorrect_replies?></div>
    <div>Wszystkich odpowiedzi: <?=$total_replies?></div>
    </section>
    <svg viewBox="0 0 64 64" class="big-pie" style="width: 100px; border-radius: 50%;">
        <circle r="25%" cx="50%" cy="50%" style="stroke-dasharray: <?=$total_incorrect_procentage?> <?=$total_correct_procentage?> ">
        </circle>
        <circle r="25%" cx="50%" cy="50%"
            style="stroke-dasharray: <?=$total_correct_procentage?> <?=$total_incorrect_procentage?> ; stroke: green; stroke-dashoffset: <?=$total_correct_procentage?>">
        </circle>
    </svg>
    <style>
    .big-pie circle {
            fill: none;
            stroke: red;
            stroke-width: 32;
    }
    </style>
</header>