<?php
// We need to include this here because we are linking empty.less by default
Yii::app()->clientScript->registerCssFile('/formPage.less');
$this->beginContent('//layouts/default');
?>
<section class="grid">
    <div>
        <div class="column twoThirds"><?php echo $content; ?></div>
    </div>
</section>

<?php $this->endContent(); ?>

