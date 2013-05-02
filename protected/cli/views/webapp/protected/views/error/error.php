<?php
    Utilities::printVar($toError);
?>

<h2>Error <?php echo $toError['code']; ?></h2>

<div class="error">
    <?php echo CHtml::encode($toError['message']); ?>
</div>

<?php
if (Utilities::isDevelopment())
{?>
    <div><label>Message: </label> <?php echo $toError['message'];?></div>
    <div><label>File: </label> <?php echo $toError['file'];?></div>
    <div><label>Line: </label> <?php echo $toError['line'];?></div>
    <br/><br/>
    <div><label>Trace:</label>
        <table>
            <thead>
            <tr>
                <td>Index</td>
                <td>File</td>
                <td>Line</td>
                <td>Function</td>
                <td>Class</td>
                <td>Type</td>
                <td>Arguments</td>
            </tr>
            </thead>
            <?php
            $lnCount = 0;
            foreach($toError['traces'] as $laTrace)
            {
                echo '<tr>';
                echo "<td>$lnCount</td>";
                echo "<td>{$laTrace['file']}</td>";
                echo "<td>{$laTrace['line']}</td>";
                echo "<td>{$laTrace['function']}</td>";
                echo "<td>{$laTrace['class']}</td>";
                echo "<td>{$laTrace['type']}</td>";
                echo '<td>';
                Utilities::printVar($laTrace['args']);
                echo '</td>';

                echo '</tr>';

                $lnCount++;
            }
            ?>

        </table>
    </div>
<?php
}
?>