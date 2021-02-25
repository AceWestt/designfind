<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <div class="main">
        <div class="navd">
            <a href="/">
                <img src="../../../uploads/site/icons/logo.svg">
                <h5>uzdesignfind</h5>

            </a>
            <div class="menu-button">
                <svg id="menuButtonSVG"  data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.86 24.05" >
                    <animateTransform attributeName="transform"
                                      attributeType="XML"
                                      type="rotate"
                                      to="-45 0 0"
                                      dur="0.3s"
                                      begin="indefinite"
                                      fill="freeze"
                                      id="menuButtonRotate"
                    />


                    <animate attributeName="opacity"
                                      attributeType="XML"
                                      to="0"
                                      dur="0.3s"
                                      begin="indefinite"
                                      fill="freeze"
                                      id="menuButtonRotate3"
                    />

                    <animate attributeName="opacity"
                             attributeType="XML"
                             to="1"
                             dur="0.3s"
                             fill="freeze"
                             begin="indefinite"
                             id="menubuttonInverse"/>
                    <animateTransform attributeName="transform"
                                      attributeType="XML"
                                      type="rotate"
                                      to="0 0 0"
                                      dur="0.3s"
                                      begin="indefinite"
                                      id="menubuttonInverse3"
                                      fill="freeze"

                    />

                    <defs>
                        <style>.cls-1{fill:#fff;}
                        </style>
                    </defs>
                    <title>menuButton</title>
                    <path class="cls-1" d="M40.22,9.8H.92a.81.81,0,0,1-.55-.23.77.77,0,0,1,0-1.1.81.81,0,0,1,.55-.23h39.3a.79.79,0,0,1,
    .55.23.77.77,0,0,1,0,1.1A.79.79,0,0,1,40.22,9.8Z" transform="translate(-0.14 -8.24)">
                        <animateTransform attributeName="transform"
                                          attributeType="XML"
                                          type="translate"
                                          to="0 0"
                                          dur="0.3s"
                                          begin="indefinite"
                                          fill="freeze"
                                          id="menuButtonRotate1" />
                        <animateTransform attributeName="transform"
                                          attributeType="XML"
                                          type="translate"
                                          to="-0.14 -8.24"
                                          dur="0.3s"
                                          begin="indefinite"
                                          id="menubuttonInverse1"
                                          fill="freeze" />
                    </path>
                    <path class="cls-1" d="M40.22,21.05H.92a.81.81,0,0,1-.55-.23.77.77,0,0,1,0-1.1.81.81,0,0,1,
    .55-.23h39.3a.79.79,0,0,1,.55.23.77.77,0,0,1,0,1.1A.79.79,0,0,1,40.22,21.05Z" transform="translate(-0.14 -8.24)"/>
                    <path class="cls-1" d="M40.22,32.29H.92a.8.8,0,0,1-.55-.22.83.83,0,0,1-.23-.55A.81.81,0,0,1,.37,31a.76.76,0,0,1,.55-.23h39.3a.75.75,0,0,1,.55.23.76.76,0,0,1,.23.55.77.77,0,0,1-.78.77Z" transform="translate(-0.14 -8.24)">
                        <animateTransform attributeName="transform"
                                          attributeType="XML"
                                          type="translate"
                                          to="-1.14 -25.24"
                                          dur="0.3s"
                                          begin="indefinite"
                                          id="menuButtonRotate2"
                                          fill="freeze" />
                        <animateTransform attributeName="transform"
                                          attributeType="XML"
                                          type="translate"
                                          to="-0.14 -8.24"
                                          dur="0.3s"
                                          begin="indefinite"
                                          id="menubuttonInverse2"
                                          fill="freeze" />
                    </path>
                </svg>
            </div>

        </div>



        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    <footer>
        <a href="/" class="logo">
            <img src="/uploads/site/icons/footerLogo.svg">
        </a>
        <a class="social" href="https://t.me/designum_bot" target="_blank"><img src="/uploads/site/icons/tg.svg"> </a>
        <a class="link first" href="/vacancy">Вакансии</a>
        <div class="link inactive">Pro-вакансии (скоро)</div>
        <div class="link inactive first">Обработка персональных данных</div>
        <p class="copy">© 2018-<?=date('Y')?> designfind.uz</p>
    </footer>

</div>



<div class="navigation">
    <div class="closeButton">&times;</div>
    <div class="link-block">
        <div class="link-item"><a href="/vacancy/add">Разместить вакансию</a> </div>
        <div class="link-item"><a href="/vacancy">Список вакансий</a> </div>
        <div class="link-item"><div class="disabled">Pro-вакансии (скоро)</div> </div>
    </div>
    <div class="link-block social">
        <div class="link-item"><a href="https://t.me/designum_bot" target="_blank"><img src="/uploads/site/icons/tg.svg"> </a> </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<!--developer Asilbek Khamzaev-->
<!--developer asilbekkhamzaev@gmail.com +998945677776-->