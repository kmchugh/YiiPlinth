<?php
/**
 * Default Field layout for forms
 */
?>

<div class="field<?php echo isset($tcClass) ? ' '.$tcClass : ''; ?>">
        <?php
        echo $tcFieldContent;
        echo $tcError;
        ?>
</div>
