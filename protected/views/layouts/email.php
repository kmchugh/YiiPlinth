<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Facebook sharing information tags -->
<meta property="og:title" content="<?php isset($subject) ? $subject : 'Email Title'; ?>" />

<title><?php isset($subject) ? $subject : 'Email Title'; ?></title>
<style type="text/css">
    /* Client-specific Styles */
#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

    /* Reset Styles */
body{margin:0; padding:0;}
img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
table td{border-collapse:collapse;}
#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* Template Styles */

    /**
    * @tab Page
    * @section background color
    * @tip Set the background color for your email. You may want to choose one that matches your company's branding.
    * @theme page
    */
body, #backgroundTable
{
    background-color:#FAFAFA;
}

    /**
    * @tab Page
    * @section email border
    * @tip Set the border for your email.
    */
#templateContainer
{
    border: 1px solid #DDDDDD;
}

    /**
    * @tab Page
    * @section heading 1
    * @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
    * @style heading 1
    */
h1, .h1
{
    color:#202020;
    display:block;
    font-family:Arial;
    font-size:34px;
    font-weight:bold;
    line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}

    /**
    * @tab Page
    * @section heading 2
    * @tip Set the styling for all second-level headings in your emails.
    * @style heading 2
    */
h2, .h2
{
    color:#202020;
    display:block;
    font-family:Arial;
    font-size:30px;
    font-weight:bold;
    line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}

    /**
    * @tab Page
    * @section heading 3
    * @tip Set the styling for all third-level headings in your emails.
    * @style heading 3
    */
h3, .h3
{
    color:#202020;
    display:block;
    font-family:Arial;
    font-size:26px;
    font-weight:bold;
    line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}

    /**
    * @tab Page
    * @section heading 4
    * @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
    * @style heading 4
    */
h4, .h4
{
    color:#202020;
    display:block;
    font-family:Arial;
    font-size:22px;
    font-weight:bold;
    line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}

    /**
    * @tab Header
    * @section header style
    * @tip Set the background color and border for your email's header area.
    * @theme header
    */
#templateHeader
{
    background-color:#FFFFFF;
    border-bottom:0;
}

    /**
    * @tab Header
    * @section header text
    * @tip Set the styling for your email's header text. Choose a size and color that is easy to read.
    */
.headerContent
{
    color:#202020;
    font-family:Arial;
    font-size:34px;
    font-weight:bold;
    line-height:100%;
    padding:0;
    text-align:center;
    vertical-align:middle;
}

    /**
    * @tab Header
    * @section header link
    * @tip Set the styling for your email's header links. Choose a color that helps them stand out from your text.
    */
.headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */
{
    color:#336699;
    font-weight:normal;
    text-decoration:underline;
}

#headerImage
{
    height:auto;
    max-width:600px !important;
}

    /**
    * @tab Body
    * @section body style
    * @tip Set the background color for your email's body area.
    */
#templateContainer, .bodyContent
{
    background-color:#FFFFFF;
}

    /**
    * @tab Body
    * @section body text
    * @tip Set the styling for your email's main content text. Choose a size and color that is easy to read.
    * @theme main
    */
.bodyContent div
{
    color:#505050;
    font-family:Arial;
    font-size:14px;
    line-height:150%;
    text-align:left;
}

    /**
    * @tab Body
    * @section body link
    * @tip Set the styling for your email's main content links. Choose a color that helps them stand out from your text.
    */
.bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */
{
    color:#336699;
    font-weight:normal;
    text-decoration:underline;
}

.bodyContent img
{
    display:inline;
    height:auto;
}

    /**
    * @tab Footer
    * @section footer style
    * @tip Set the background color and top border for your email's footer area.
    * @theme footer
    */
#templateFooter{
    background-color:#FFFFFF;
    border-top:0;
}

    /**
    * @tab Footer
    * @section footer text
    * @tip Set the styling for your email's footer text. Choose a size and color that is easy to read.
    * @theme footer
    */
.footerContent div
{
    color:#707070;
    font-family:Arial;
    font-size:12px;
    line-height:125%;
    text-align:left;
}

    /**
    * @tab Footer
    * @section footer link
    * @tip Set the styling for your email's footer links. Choose a color that helps them stand out from your text.
    */
.footerContent div a:link, .footerContent div a:visited, /* Yahoo! Mail Override */ .footerContent div a .yshortcuts /* Yahoo! Mail Override */
{
    color:#336699;
    font-weight:normal;
    text-decoration:underline;
}

.footerContent img{display:inline;}
</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                    <?php
                    echo (preg_match('/.+?<\?php/i', $content) > 0) ?
                        eval(' ?>'.$content.'<?php ') :
                        $content;
                    ?>
                </table>
                <br />
            </td>
        </tr>
    </table>
</center>
</body>
</html>