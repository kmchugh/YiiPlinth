<?php
/**
 * Default Field layout for forms
 */

?>

<div class="field imageUpload<?php echo isset($tcClass) ? ' '.$tcClass : ''; ?>">
    <?php echo $tcFieldLabel; ?>
    <div class="preview">
        <?php
            // TODO: allow for ALT text
            echo CHtml::image($toValue);
        ?>
    </div>
    <div class="input">
        <?php
            if (isset($tcFieldHint) && count($tcFieldHint) > 0)
            {
                echo "<div class=\"hint\">$tcFieldHint</div>";
            }

            echo $tcFieldContent;
            echo $tcError;
        ?>
    </div>
</div>
