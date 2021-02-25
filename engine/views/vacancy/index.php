<?php
/*ACEWESTT*/

$this->title = 'Все вакансии - uzdesignfind';
$this->registerJSFile(Yii::$app->request->baseUrl.'/js/vacancy/vacancies.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>


<div class="vacancies-wrp">
    <h2>Найдите работу<br> своей мечты прямо сейчас:</h2>
    <input class="input" name="search" id="search" value="" placeholder="Например, 3d дизайнер">
    <div class="input select" id="category">
        <div class="selected"><span class="hidden">-1</span> Выберите категорию</div>
        <div class="icon" id="categorySelectButton"> <img src="/uploads/site/icons/selectIcon.svg"></div>
        <div class="cat-wrp">
            <div class="option"><span class="hidden">-1</span> <div class="title">Все категории</div></div>
            <?php foreach ($categories as $cat){
                echo '<div class="option"><span class="hidden">'.$cat->id.'</span> <div class="title">'.$cat->title.'</div></div>';
            }?>
        </div>
    </div>
    <div class="extended-wrp">
        <div class="checkboxBlock busyTypeBlock">
            <div class="item">
                <img src="/uploads/site/icons/checkbox.svg" class="checkbox-custom">
                <div class="label">Офис, полный рабочий день</div>
                <div class="value hidden">В офис</div>
            </div>
            <div class="item">
                <img src="/uploads/site/icons/checkbox.svg" class="checkbox-custom">
                <div class="label">Удаленная работа</div>
                <div class="value hidden">Удаленная работа</div>
            </div>
            <div class="item">
                <img src="/uploads/site/icons/checkbox.svg" class="checkbox-custom">
                <div class="label">Разовый проект</div>
                <div class="value hidden">Разовый проект</div>
            </div>
        </div>
        <hr>
        <div class="checkboxBlock tagBlock">
            <?php
            foreach ($tagArray as $tag){
            ?>
                <div class="item">
                    <img src="/uploads/site/icons/checkbox.svg" class="checkbox-custom">
                    <div class="label"><?=$tag?></div>
                    <div class="value hidden"><?=$tag?></div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="btn findVacancy">
        <img src="/uploads/site/icons/findVac.svg">
        <span>Найти вакансию</span>
    </div>
    <div class="extWrpOpener">Расширенный поиск</div>
    <h3>Свежие вакансии (<span></span>)</h3>
    <div class="vacancy-results">

    </div>
    <div class="btn showMore">Показать больше вакансий</div>
    <div class="addnew-wrp">
        <img src="/uploads/site/img/addNewBG.svg">
        <a href="/vacancy/add" class="btn addMore">Добавить новую вакансию</a>
    </div>
</div>







