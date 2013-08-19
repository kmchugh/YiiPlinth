<footer class="grid reverse">
    <section>
        <div class="column whole logo">
            <a href="/" title="home"><h1><?php echo Yii::app()->name;?></h1></a>
            <nav><?php  $this->renderPartial('//site/_headerMenu', array('tlLinkWindow' => false));?></nav>
        </div>
    </section>
    <div class="copyright"><small>Copyright &copy; <?php echo @date('Y').' '.Yii::app()->name; ?>, All rights reserved.</small></div>
</footer>