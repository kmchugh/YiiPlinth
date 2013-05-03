<section class="error">
    <h1><?php echo $toError['code']; ?></h1>
    <div><?php echo CHtml::encode($toError['message']); ?></div>
    <div>Go back <a href="/" title="home">home</a></div>
</section>

<?php
if (Utilities::isDevelopment())
{?>
    <div class="trace">
        <table>
            <thead>
            <tr>
                <td>Index</td>
                <td>File</td>
                <td>Class</td>
                <td>Function</td>
                <td>Line</td>
            </tr>
            </thead>
            <?php
            $lnCount = 0;
            foreach($toError['traces'] as $laTrace)
            {
                echo '<tr>';
                echo "<td>$lnCount</td>";
                echo "<td>{$laTrace['file']}</td>";
                echo "<td>{$laTrace['class']}</td>";
                echo "<td>{$laTrace['function']}</td>";
                echo "<td>{$laTrace['line']}</td>";
                echo '</tr>';

                $lnCount++;
            }
            ?>

        </table>
    </div>
<?php
}
?>