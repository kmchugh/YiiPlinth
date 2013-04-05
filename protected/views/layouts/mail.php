<?php $this->beginContent('//layouts/email'); ?>
    <table width="600px" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td width="50px"></td>
            <td width="500px"></td>
            <td width="50px"></td>
        </tr>

        <!-- Header -->
        <tr>
            <td width="50px"></td>
            <td width="550px">
                <table width="550px" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td width="550px">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td width="50px"></td>
        </tr>

        <!-- spacer -->
        <tr>
            <td colspan="3">
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td width="50px"></td>
            <td width="500px">
                <?php echo $content; ?>
            </td>
            <td width="50px"></td>
        </tr>

        <!-- spacer -->
        <tr>
            <td colspan="3">
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td width="50px"></td>
            <td width="550px">
            </td>
            <td width="50px"></td>
        </tr>

        <!-- spacer -->
        <tr>
            <td colspan="3">
            </td>
        </tr>

        </tbody>
    </table>

<?php $this->endContent(); ?>