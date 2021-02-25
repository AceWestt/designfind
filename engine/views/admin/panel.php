<?php
$this->title = 'Панель управления | Admin | designfind';
$this->registerJSFile(Yii::$app->request->baseUrl.'/js/vacancy/admin/panel.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<h3>Список Вакансий</h3>
<hr>
<table class="table table-bordered vac">
    <thead>
    <tr>
        <th>
            №
        </th>
        <th>Дата</th>
        <th>Компания</th>
        <th>Категория</th>
        <th>Опыт</th>
        <th>Занятость</th>
        <th>Описание</th>
        <th>Обязяанности</th>
        <th>Условия</th>
        <th>Адрес</th>
        <th>Зп/ставка</th>
        <th>Контакты</th>
        <th>Картинка</th>
        <th>Тэги</th>
        <th>Управление</th>
    </tr>
    </thead>
    <tbody>
    <?php
        if(count($vacancies)>0){
            $n = 0;
            $date = '';
            $month = '';
            $cat = '';
            foreach ($vacancies as $vacancy){
                $n++;
                $date = explode('-', $vacancy['date']);
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

                $date = $date[2].' '.$month.' '.$date[0];
                $cat = \app\models\Category::findOne($vacancy['category_id'])['title'];
    ?>
                <tr>
                    <td class="id hidden"><?=$vacancy['id']?></td>
                    <td class="number"><?=$n?></td>
                    <td class="date"><?=$date?></td>
                    <td class="company"><?=$vacancy['company']?></td>
                    <td class="category"><?=$cat?></td>
                    <td class="exp"><?=$vacancy['exp']?></td>
                    <td class="time"><?=$vacancy['time']?></td>
                    <td class="description"><?=$vacancy['description']?></td>
                    <td class="duties"><?=$vacancy['duties']?></td>
                    <td class="conditions"><?=$vacancy['conditions']?></td>
                    <td class="location"><?=$vacancy['location']?></td>
                    <td class="price"><?=$vacancy['minwage'].' - '.$vacancy['wage'].' UZS'?></td>
                    <td class="contact"><?=$vacancy['contact']?></td>
                    <td class="img">
                        <img width="150" src="<?=$vacancy['imgUrl']?>">
                    </td>
                    <td class="tags"><?=$vacancy['tags']?></td>
                    <td class="management">
                        <?php
                            if($vacancy['status']===1){
                        ?>
                                <div class="btn confirmed disabled">Утверждено</div>
                        <?php
                            }
                            else{
                        ?>
                                <div class="btn confirm">Утвердить</div>
                        <?php
                            }
                        ?>
                        <div class="btn delete">Удалить</div>
                    </td>
                </tr>

    <?php
            }
        }
    ?>
    </tbody>
</table>


<br>
<br>
<br>

<h3>Список категорий</h3>

<hr>

<table class="table table-bordered cat">
    <thead>
    <tr>
        <th>№</th>
        <th>Название</th>
        <th>К-во вакансий</th>
        <th>Управление</th>
    </tr>
    <tbody>
    <tr class="new">
        <td class="number">00</td>
        <td class="title"><div class="form-group"><input type="text" id="newCatTitle" class="form-control"></div></td>
        <td class="count"></td>
        <td class="control"><div class="btn btn-info" id="addCat">Добавить</div> </td>
    </tr>
        <?php
            $nn = 0;
            $count = 0;
            foreach ($categories as $item){
                $nn++;
                $count = count(\app\models\Vacancy::find()->where(['category_id'=>$item['id']])->asArray()->all());
        ?>
                <tr>
                    <td class="number"><?=$nn?></td>
                    <td class="id hidden"><?=$item['id']?></td>
                    <td class="title"><?=$item['title']?></td>
                    <td class="count"><?=$count?></td>
                    <td class="control">
                        <div class="btn btn-warning editCat" >Редактировать</div>
                        <div class="btn btn-danger deleteCat">Удалить</div>
                    </td>
                </tr>
        <?php
            }
        ?>
    </tbody>
    </thead>
</table>


<!--DELETE MODAL-->
<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Подтвердите действие</h4>
            </div>
            <div class="modal-body">
                <p>Удалить</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmDelete" class="btn btn-danger">Подтвердить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>

    </div>
</div>


<div id="photoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

    </div>
</div>