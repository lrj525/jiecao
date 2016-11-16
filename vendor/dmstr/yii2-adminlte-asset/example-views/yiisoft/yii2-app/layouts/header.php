<?php
use yii\helpers\Html;
use backend\models\Admin;
?>
<style>
    .nav a {
        float: left
    };
</style>
<header class="main-header">
    <?= Html::a('<span class="logo-lg" style="text-align:left;">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#">
                        <?php echo Yii::$app->user->getIdentity()->mobile;?>
                    </a>
                    <a href="/user/modify-password" >
                        修改密码
                    </a>
                    <a href="/site/logout">
                        退出
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>