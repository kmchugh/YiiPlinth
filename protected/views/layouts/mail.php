<?php $this->beginContent('//layouts/email'); ?>
    <!-- Header -->
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                <tr>
                    <td class="headerContent">
                        <h1 class="h1"><?php echo isset($title) ? $title : ''; ?></h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Body -->
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
                <tr>
                    <td valign="top" class="bodyContent">
                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                            <tr>
                                <td valign="top">
                                    <?php echo $content; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Footer -->
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter">
                <tr>
                    <td valign="top" class="footerContent">
                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                            <tr>
                                <td  colspan="2" valign="top">
                                    <div><small>Copyright &copy; <?php echo @date('Y').' '.Yii::app()->name; ?>, All rights reserved.</small></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

<?php $this->endContent(); ?>