<?php

/*ACEWESTT*/

$this->title = 'uzdesignfind';
?>

<div class="header">
<img class="header-img" src="../../../uploads/site/img/main.svg">
    <h1>Поиск дизайнеров в Узбекистане</h1>
    <h6>Актуальные вакансии. Каждый день.</h6>
</div>

<a href="/vacancy/add" class="btn btn-default addVac">
    <p>Разместить вакансию</p>
</a>

<h2>🔥 ТОП новых вакансий:</h2>
<div class="vacancy-container">
    <?php
        foreach ($vacancies as $vacancy){
            $date = explode('-', $vacancy['date']);
            $day = $date[2];
            $month = $date[1];
            $year = $date[0];
            $monthword;
            switch ($month){
                case 1:
                    $monthword = 'Января';
                    break;
                case 2:
                    $monthword = 'Февраля';
                    break;
                case 3:
                    $monthword = 'Марта';
                    break;
                case 4:
                    $monthword = 'Апреля';
                    break;
                case 5:
                    $monthword = 'Майа';
                    break;
                case 6:
                    $monthword = 'Июня';
                    break;
                case 7:
                    $monthword = 'Июля';
                    break;
                case 8:
                    $monthword = 'Августа';
                    break;
                case 9:
                    $monthword = 'Сентября';
                    break;
                case 10:
                    $monthword = 'Октября';
                    break;
                case 11:
                    $monthword = 'Ноября';
                    break;
                case 12:
                    $monthword = 'Декабря';
                    break;

            };
            $category = \app\models\Category::findOne($vacancy['category_id'])['title'];
    ?>
            <div class="vacancy-item">
                <a href="/vacancy/item?id=<?=$vacancy['id']?>">
                    <div class="header">
                        <div class="header-busy-type">
                            <span><?=$vacancy['region']?></span>
                            <img class="img" src="/uploads/site/icons/strelka.svg">
                            <span><?=$vacancy['time']?></span>
                        </div>
                        <div class="header-minwage">от <?=$vacancy['minwage']?> UZS</div>
                    </div>
                    <div class="img-wrp">
                        <img src="<?=$vacancy['imgUrl']?>">
                    </div>
                    <div class="date"><?=$day.' '.$monthword?></div>
                    <h3><?=$category?> в компанию <?=$vacancy['company']?></h3>
                </a>
            </div>
    <?php
        }
    ?>
</div>
<a href="/vacancy" class="btn showAll">Показать все вакансии</a>
<h2 class="about">О проекте</h2>
<p class="about">
    🤘Привет. Наша цель простая: облегчить поиск дизайнеров в стране.<br><br>
    На своем опыте знаем, что найти<br>
    ответственного профи в Узбекистане - задача не из легких.<br><br>
    Каждый день мы отбираем только самые<br>
    интересные заявки для того, чтобы каждый смог максимально быстро решить свои задачи или найти работу.
</p>
<div class="tg-wrp">
    <img src="/uploads/site/img/tgpic.svg">
    <a href="https://t.me/designum_bot" target="_blank" class="btn">Мы в телеграм канале</a>
</div>

