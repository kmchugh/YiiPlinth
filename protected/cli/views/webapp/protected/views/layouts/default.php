<?php
Yii::app()->clientScript->registerScriptFile('/javascript/libs/dromos/dromos.bootstrap.js', CClientScript::POS_HEAD);
$this->beginContent('//layouts/webpage');
?>
<div class="page">
    <?php  $this->renderPartial('//site/_header');?>

    <section class="body">
        <?php echo $content; ?>
    </section>

    <?php  $this->renderPartial('//site/_footer');?>
</div>
<?php $this->endContent(); ?>

