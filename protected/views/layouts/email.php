<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title><?php isset($subject) ? $subject : 'Email Title'; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body style="font: normal medium/1.4em 'Avant Garde','Century Gothic',Futura,'URW Gothic L','Apple Gothic',AppleGothic,sans-serif;">
      <table width="600px" cellpadding="0" cellspacing="0" style="background: white;">
          <tbody>
            <tr>
                  <td width="600px">
                    <?php
                      echo (preg_match('/.+?<\?php/i', $content) > 0) ?
                        eval(' ?>'.$content.'<?php ') :
                        $content;
                    ?>
                  </td>
              </tr>
          </tbody>
      </table>
  </body>
</html>
