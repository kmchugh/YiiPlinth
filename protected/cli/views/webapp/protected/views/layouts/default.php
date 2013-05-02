<?php
$this->beginContent('//layouts/webpage');
?>
<div class="page">
    <header><h1><?php echo $this->pageTitle;?></h1></header>
    <section class="body">
        <?php echo $content; ?>
    </section>
    <footer><small>Copyright &copy; <?php echo @date('Y').' '.Yii::app()->name; ?>, All rights reserved.</small></footer>
</div>
<?php $this->endContent(); ?>

