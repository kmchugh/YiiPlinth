<div class="form<?php echo count($this->containerClass) > 0 ? ' '.$this->containerClass : ''; ?>">
    <?php
        // Form title if needed
        if (strlen($this->title) > 0)
        {
            ?>
            <h1><?php echo $this->title;?></h1>
            <?php
        }

        // Form note if needed
        if (strlen($this->note) > 0)
        {
            ?>
            <p class="note"><?php echo $this->note;?></p>
        <?php
        }
    ?>
    <?php
        // <form> start tag
        if($this->stateful)
        {
            echo CHtml::statefulForm($this->action, $this->method, $this->htmlOptions);
        }
        else
        {
            echo CHtml::beginForm($this->action, $this->method, $this->htmlOptions);
        }
    ?>

    <?php if (count($this->fields) >0)
    {
        ?>
        <fieldset>
            <p class="note"><?php echo Utilities::getString('Fields with'); ?> <span class="required">*</span> <?php echo Utilities::getString('are required'); ?></p>
            <div class="fields">
                <?php
                    foreach($this->fields as $loField)
                    {
                        echo $this->field($loField);
                    }
                ?>
            </div>

            <?php if (count($this->buttons) >0)
            { ?>
                <div class="buttons">
                    <?php
                    foreach($this->buttons as $loButton)
                    {
                        echo $this->button($loButton);
                    }
                    ?>
                </div>
            <?php } ?>

            <?php if(Yii::app()->user->hasFlash('formMessage')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
                </div>
            <?php endif; ?>
        </fieldset>
       <?php
    }
    ?>

    <?php if ($this->oauth)
    {
        // TODO: Use the oauth module to render an 'oauth marker' instead of hard coding the marker
        ?>
        <div class="oauth">
            <label><?php echo Utilities::getString("oauth_or"); ?></label>
            <span class="oauth"/>
        </div>
    <?php
    }
    ?>

    <?php if (count($this->links) >0)
    {
        ?>
        <div class="links">
            <?php
            foreach($this->links as $loLink)
            {
                echo $this->link($loLink);
            }
            ?>
        </div>
    <?php
    }
    ?>

    <?php
        // <form> end tag
        echo CHtml::endForm();
    ?>
</div>