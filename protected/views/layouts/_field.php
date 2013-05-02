<?php
/**
 * Default Field layout for forms
 */
?>

<div class="field<?php echo isset($tcClass) ? ' '.$tcClass : ''; ?>">
    <?php
    if (isset($tcFieldHint) && count($tcFieldHint) > 0)
    {
        echo "<div class=\"hint\">$tcFieldHint</div>";
    }
    echo $tcFieldLabel;
    echo $tcFieldContent;
    echo $tcError;
    ?>
</div>
