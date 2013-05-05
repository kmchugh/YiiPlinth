<footer class="grid">
    <div class="stars blue"></div>
    <section class="reverse blue">
        <div class="column half logo">
            <a href="/" title="home"><img src="/images/icons/logo_white_64.png" alt="BigTop Logo"/><h1>BigTop</h1></a>
            <nav><?php  $this->renderPartial('//site/_headerMenu', array('tlLinkWindow' => false));?></nav>
        </div>
        <div class="column half">
            <div class="newsfeed">
                <h2>NewsFeed</h2>
                <ul>
                    <li><a href="#">News item 1</a></li>
                    <li><a href="#">News item 2</a></li>
                    <li><a href="#">News item 3</a></li>
                    <li><a href="#">News item 4</a></li>
                </ul>
            </div>
        </div>
    </section>
    <div class="copyright"><small>Copyright &copy; <?php echo @date('Y').' '.Yii::app()->name; ?>, All rights reserved.</small></div>
</footer>