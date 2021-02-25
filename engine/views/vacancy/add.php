<?php
/*ACEWESTT*/

$this->title = 'Разместить вакансию - uzdesignfind';

$this->registerJSFile(Yii::$app->request->baseUrl.'/js/vacancy/add.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>



<div class="header vacancy">
    <h1>Разместите вакансию для быстрого поиска кандидата</h1>
</div>

<div class="mainContainer vacancy">
    <form id="addForm" class="form vacancy">
        <div class="label">Название организации</div>
        <input class="input" name="companyName" id="companyName" value="" placeholder="Введите название организации">
        <div class="label">Категория дизайнера (специальность)</div>
        <div class="input select" id="category">
            <div class="selected"><span class="hidden">-1</span> Выберите категорию</div>
            <div class="icon" id="categorySelectButton"> <img src="/uploads/site/icons/selectIcon.svg"></div>
            <div class="cat-wrp">
                <?php foreach ($categories as $cat){
                    echo '<div class="option"><span class="hidden">'.$cat->id.'</span> <div class="title">'.$cat->title.'</div></div>';
                }?>
            </div>
        </div>
        <div class="label">Опыт работы</div>
        <div class="input select" id="exp">
            <div class="selected"><span class="hidden">-1</span>Укажите опыт работы кандидата </div>
            <div class="icon" id="expSelectButton"> <img src="/uploads/site/icons/selectIcon.svg"></div>
            <div class="cat-wrp">
                <div class="option"><span class="hidden">Без опыта работы</span><div class="title">Без опыта работы</div> </div>
                <div class="option"><span class="hidden">от 1 года</span><div class="title">от 1 года</div></div>
                <div class="option"><span class="hidden">от 2х лет</span><div class="title">от 2х лет</div></div>
                <div class="option"><span class="hidden">от 3х лет</span><div class="title">от 3х лет</div></div>
            </div>
        </div>
        <div class="label">Тип занятости</div>
        <div class="input select" id="busyType">
            <div class="selected"><span class="hidden">-1</span>Укажите тип занятости кандидата</div>
            <div class="icon" id="busySelectButton"> <img src="/uploads/site/icons/selectIcon.svg"></div>
            <div class="cat-wrp">
                <div class="option"><span class="hidden">В офис</span><div class="title">В офис/полный день</div> </div>
                <div class="option"><span class="hidden">Удаленная работа</span><div class="title">Удаленная работа</div> </div>
                <div class="option"><span class="hidden">Разовый проект</span><div class="title">Разовый проект</div> </div>
            </div>
        </div>
        <div class="label">Описание вакансии</div>
        <textarea class="input" name="description" id="description" ></textarea>
        <div class="label">Обязанности кандидата</div>
        <textarea class="input" name="duties" id="duties"></textarea>
        <div class="label">Условия работы</div>
        <textarea class="input" name="conditions" id="conditions"></textarea>
        <div class="label">Выберите область</div>
        <div class="input select" id="region">
            <div class="selected"><span class="hidden">-1</span>Укажите область</div>
            <div class="icon" id="regionSelectionButton"> <img src="/uploads/site/icons/selectIcon.svg"></div>
            <div class="cat-wrp">
                <div class="option"><span class="hidden">Ташкент</span><div class="title">Ташкент</div> </div>
                <div class="option"><span class="hidden">Каракалпакстан</span><div class="title">Каракалпакстан</div> </div>
                <div class="option"><span class="hidden">Андижан</span><div class="title">Андижан</div> </div>
                <div class="option"><span class="hidden">Бухара</span><div class="title">Бухара</div> </div>
                <div class="option"><span class="hidden">Джизакх</span><div class="title">Джизакх</div> </div>
                <div class="option"><span class="hidden">Кашкадарья</span><div class="title">Кашкадарья</div> </div>
                <div class="option"><span class="hidden">Навои</span><div class="title">Навои</div> </div>
                <div class="option"><span class="hidden">Наманган</span><div class="title">Наманган</div> </div>
                <div class="option"><span class="hidden">Самарканд</span><div class="title">Самарканд</div> </div>
                <div class="option"><span class="hidden">Сурхандарья</span><div class="title">Сурхандарья</div> </div>
                <div class="option"><span class="hidden">Сырдарья</span><div class="title">Сырдарья</div> </div>
                <div class="option"><span class="hidden">Фергана</span><div class="title">Фергана</div> </div>
                <div class="option"><span class="hidden">Хорезм</span><div class="title">Хорезм</div></div>
            </div>
        </div>
        <div class="label">Адрес организации</div>
        <input class="input" name="companyLocation" id="companyLocation" value="" placeholder="Введите фактический адрес">
        <div class="label">Заработная плата</div>
        <input class="input" type="number" name="minvage" id="minvage" value="" placeholder="от">
        <input class="input" type="number" name="maxvage" id="maxvage" value="" placeholder="до">
        <div class="label">Контактная информация</div>
        <input class="input" name="phone" id="phone" value="" placeholder="Например, +998911654667">
        <div class="lilLabel">(почта, номер телефона, мессенджеры, форма и т.д.)</div>
        <div class="label">Теги</div>
        <input class="input" name="tags" id="tags" value="" placeholder="Например, '3д моушн, графический дизайн'">
        <div class="lilLabel">введите теги через запитую, ",".<br>
            Допустимые символы: A-Za-z', 'А-Яа-я', ' ', '0-9' и '-'.</div>
        <div class="label">Загрузить картинку вакансии</div>
        <div class="file-wrp" id="image">
            <button class="btn uploadFile" >Выберите файл</button>
            <input type="file" name="image">
        </div>
        <div class="lilLabel">Формат: jpg,png; размер файла: не более 5mb</div>
        <img src="" class="img uploaded">
        <input type="submit" value="Опубликовать вакансию" class="btn">
        <div class="mySuccess">
            <h6>Спасибо!</h6>
            <p>Ваша вакансия принята! Вакансия будет опубликована после проверки нашими модераторами в ближайшее время!</p>
            <a class="btn addmore" href="/vacancy/add">Добавить новую вакансию</a>
            <a class="btn govacancy" href="/vacancy">Перейти к вакансиям</a>
        </div>
    </form>

</div>

<div id="contactModal" class="modal fade addModel" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <button type="button"  data-dismiss="modal">&times;</button>
        <img src="/uploads/site/icons/success.svg">
        <div class="contact">Готово!</div>
        <div class="lilLabel">Заявка на публикацию вакансии<br>отправлена на модерацию!</div>
        <a class="btn closeModal" href="/">На главную</a>
    </div>
</div>
