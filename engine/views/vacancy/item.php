<?php

$js = <<<JS
    $('.btn.showContact').click(function() {
        var contact = $('#contact').html();
        $('#contactModal .modal-dialog .contact').empty();
        $('#contactModal .modal-dialog .contact').append(contact);
        $('#contactModal').modal();
    });
JS;

$this->registerJS($js);

if(!empty($vacancy)){
    $tags = explode(',',$vacancy['tags']);
    $this->title = $vacancy['company'].' - '.\app\models\Category::findOne($vacancy['category_id'])['title'].' | '.'designfind';
?>
<div class="full-wrp">
    <div class="header-city-time">
        <div><?=$vacancy['region']?></div>
        <img src="/uploads/site/icons/strelka.svg">
        <div><?=$vacancy['time']?></div>
    </div>
    <h2><?=\app\models\Category::findOne($vacancy['category_id'])['title']?> в компанию <?=$vacancy['company']?></h2>
    <img src="<?=$vacancy['imgUrl']?>" class="img">
    <div class="tags">
        <?php foreach ($tags as $tag){?>
            <div class="item">#<?=trim($tag)?></div>
    <?php }?>
    </div>
    <p class="description"><?=$vacancy['description']?></p>
    <p class="duties"><span class="strong">Обязанности:  </span> <?=$vacancy['duties']?> </p>
    <p class="condition"><span class="strong">Условия работы:  </span> <?=$vacancy['conditions']?> </p>
    <p class="time"><span class="strong">Тип занятости:  </span> <?=$vacancy['time']?> </p>
    <p class="address"><span class="strong">Адрес:  </span> <?=$vacancy['location']?> </p>
    <p class="minwage"><span class="strong">Оклад:  </span> от <?=preg_replace('/\B(?=(\d{3})+(?!\d))/', " ", $vacancy['minwage'])?> сумов</p>
    <p class="hidden" id="contact"><?=$vacancy['contact']?></p>
    <div class="btn showContact">Откликнутся на вакансию</div>
    <a href="/vacancy" class="btn goALL">
        <img src="/uploads/site/icons/goBack.svg">
        <div>Назад к списку вакансий</div>
    </a>
    <h3>Сейчас просматривают</h3>
    <div class="vacancies-wrp">
        <div class="vacancy-results">
            <?php
            if(count($vacancies)>0){
                foreach ($vacancies as $item){
                    ?>
                    <a href="/vacancy/item?id=<?=$item['id']?>">
                        <div class="result-item">
                            <div class="hidden id"><?=$item['id']?></div>
                            <div class="header-busy-type">
                                <span><?=$item['region']?></span>
                                <img class="img" src="/uploads/site/icons/strelka.svg">
                                <span><?=$item['time']?></span>
                            </div>
                            <h2><?=\app\models\Category::findOne($item['category_id'])['title']?>  в компанию <?=$item['company']?></h2>
                            <div class="date"><?=prettyDate($item['date'])?></div>
                            <p><?=$item['description']?></p>
                            <div class="price"> от <?=preg_replace('/\B(?=(\d{3})+(?!\d))/', ' ', $item['minwage'])?></div>
                        </div>
                    </a>

                    <?php
                }
            }
            ?>
        </div>
        <a href="/vacancy" class="btn showMore">Показать больше вакансий</a>
        <div class="addnew-wrp">
            <img src="/uploads/site/img/addNewBG.svg">
            <a href="/vacancy/add" class="btn addMore">Добавить новую вакансию</a>
        </div>
    </div>
</div>
<?php
}
?>

<?php
function prettyDate($date){
    $date = explode('-', $date);
    $month = '';
    switch ($date[1]){
        case 1:
            $month = 'Января';
            break;
        case 2:
            $month = 'Февраля';
            break;
        case 3:
            $month = 'Марта';
            break;
        case 4:
            $month = 'Апреля';
            break;
        case 5:
            $month = 'Мая';
            break;
        case 6:
            $month = 'Июня';
            break;
        case 7:
            $month = 'Июля';
            break;
        case 8:
            $month = 'Августа';
            break;
        case 9:
            $month = 'Сентября';
            break;
        case 10:
            $month = 'Октября';
            break;
        case 11:
            $month = 'Ноября';
            break;
        default:
            $month = 'Декабря';
            break;
    }

    return $date[2].' '.$month.' '.$date[0];
}
?>



<div id="contactModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <button type="button"  data-dismiss="modal">&times;</button>
        <div class="lilLabel">Контактная информация:</div>
        <div class="contact"></div>
        <a class="btn closeModal" data-dismiss="modal">Закрыть</a>
    </div>
</div>
