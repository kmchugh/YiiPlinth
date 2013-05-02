<?php if(CCaptcha::checkRequirements()): ?>
    <div class="field captcha">
        <div class="image"><?php $this->widget('CCaptcha',
                array('buttonOptions'=>array('title'=>Utilities::getString('Get a new code')))); ?></div>
        <div class="hint"><?php echo Utilities::getString('Enter the letters above') ?></div>
        <div class="field">
            <?php
            echo $tcFieldContent;
            echo $tcError;
            ?>
        </div>
        <div class="hint"><?php echo Utilities::getString('Letters are not case-sensitive') ?></div>
    </div>
<?php endif; ?>