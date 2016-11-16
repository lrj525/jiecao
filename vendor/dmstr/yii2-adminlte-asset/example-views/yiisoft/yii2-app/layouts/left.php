<aside class="main-sidebar newSide">
    <section class="sidebar">
	<?php
	$arr['options'] = ['class' => 'sidebar-menu'];
	$arr['items'] = Yii::$app->params['menu'];
	echo dmstr\widgets\Menu::widget($arr); 
	?>
    </section>
</aside>