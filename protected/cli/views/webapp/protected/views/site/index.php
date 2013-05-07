<section class="grid">
    <header>
        <div class="column twoThirds">
            <h1>That was easy.</h1>
            <p>Getting started couldn't have been simpler, but there is still a bit to do to get going.</p>
            <p>Check out the links to get cracking.</p>
        </div>
        <div class="column third nav">
            <h1>What's next?</h1>
            <ul>
                <li><a href="#updateDB">Update the database</a></li>
                <li><a href="#emailSupport">Set up email support</a></li>
                <li><a href="#automationSupport">Start up automation</a></li>
                <li><a href="#register">Register an account</a></li>
                <li><a href="#stylePage">Check out the style</a></li>
                <li><a href="#oauthSupport">Turn on OAuth (optional)</a></li>
                <li><a href="#errorSupport">Customise errors (optional)</a></li>
            </ul>
        </div>
    </header>
</section>

<section class="grid main">
<div>
    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a href="http://www.yiiframework.com/doc/guide/1.1/en/topics.console">yiic</a></li>
            <li><a href="http://www.yiiframework.com/doc/guide/1.1/en/database.migration">dbsync (migrate)</a></li>
        </ul>
    </div>

    <div class="column twoThirds">
        <a id="updateDB"><h1>Update the database</h1></a>
        <p>
            Before you are ready to go you will need to make sure your database is created and has all the required
            tables.  In Yii this is simple, by running all of the migrations everything is done for you.
        </p>

        <p>
            To see what migrations you need to run, enter the following command from your project/protected directory:
                <pre><code>
                        ./yiic dbsync
                    </code></pre>
        </p>

        <p>
            This will give a listing of migrations that need to be executed.  There should be a fairly long list looking something like the following:
                <pre><code>
                        Creating migration history table "database_version"...done.
                        Total 28 new migrations to be applied:
                        m121011_011000_user
                        m121011_012000_user
                        m121011_031000_userInfo
                        m121011_033449_userInfo
                        m121029_140700_country
                        m121029_155118_region
                        m121030_022317_city
                        m121030_022318_city
                        m121030_065617_countryIP
                        m121030_065618_countryIP
                        m121030_065619_countryIP
                        m121030_065620_countryIP
                        m121030_065621_countryIP
                        m121030_065622_countryIP
                        m121103_151635_gender
                        m121103_151800_changePasswordToken
                        ... and many more
                    </code></pre>
        </p>

        <p>
            The size of the countryIP migrations are quite large, so it is best to run them individually.  The easiest way
            get up to date is to enter each of the following commands from your applications protected directory, and type 'yes'
            at each prompt:

                <pre><code>
                        ./yiic dbsync up 6
                        ./yiic dbsync up 2
                        ./yiic dbsync up 1
                        ./yiic dbsync up 1
                        ./yiic dbsync up 1
                        ./yiic dbsync up 1
                        ./yiic dbsync up 1
                        ./yiic dbsync up 1
                        ./yiic dbsync
                    </code></pre>
        </p>
    </div>
</div>

<div>
    <div class="column twoThirds">
        <a id="emailSupport"><h1>Set up email support</h1></a>
        <p>
            Time to give your application the ability to send emails.  The simplest way to do this is to integrate
            with gmail.
        </p>

        <p>
            Open your common.php configuration file.  It will be found at:<br/>
            <small><strong><?php echo Yii::getPathOfAlias('application.config.common.php'); ?></strong></small><br/><br/>

            Then find the components section and add the following (replacing <strong>app-email@mysite.com</strong> and <strong>mypassword</strong> with appropriate content):

                <pre><code>
                        'mail'=>array(
                        'transportOptions' => array(
                        'username'=>'app-email@mysite.com',
                        'password'=>'mypassword',
                        ),
                        ),
                    </code></pre>
        </p>

        <p>Yep, that's it.  Check out the resources for other options.</p>
    </div>

    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a href="http://www.yiiframework.com/extension/mail/">Yii Mail Extension</a></li>
            <li><a href="http://swiftmailer.org/">PHP SwiftMailer</a></li>
        </ul>
    </div>
</div>

<div>
    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a href="http://unixhelp.ed.ac.uk/CGI/man-cgi?crontab+5">crontab man pages</a></li>
        </ul>
    </div>

    <div class="column twoThirds">
        <a id="automationSupport"><h1>Start up automation</h1></a>
        <p>Automation of tasks is achieved in YiiPlinth using crontab and setting it up is just as easy as getting the email
            system up and running.  Just run this from your application directory:

                <pre><code>
                        ./yiic cron start
                    </code></pre>

        </p>

        <p>
            This will create the entries in crontab to get your automation tasks running in the background.

            And if you want to stop them, just use this command from the application directory:
                <pre><code>
                        ./yiic cron stop
                    </code></pre>
        </p>
    </div>
</div>

<div>
    <div class="column twoThirds">
        <a id="stylePage"><h1>Check out the style</h1></a>
        <p>
            Now it's time to have a look at your work, The <a target="_blank" href="<?php echo Yii::app()->createUrl('/site/page/', array('view'=>'visualTest')); ?>">style page</a> allows you to modify and review the styling applied to the site in one view.
            You can use the style page as a guide and visual test when you are modifying the .less files.
        </p>
        <h2>Conventions</h2>
        <p>
            Generally the .less files are located in their theme folder under the css directory.
                <pre><code>
                        {Project Folder}/themes/{Theme Name}/css/
                    </code></pre>
        </p>
        <p>

            .less files with names starting with an underscore are meant to be included in main .less files.   The main
            .less files start with a lower case character, and naming convention is lowercase camel-case.
                <pre><code>
                        // A main file
                        {Project Folder}/themes/{Theme Name}/css/default.less

                        // An include
                        {Project Folder}/themes/{Theme Name}/css/_buttons.less
                    </code></pre>
        </p>
        <p>

            Main files are included by web pages to style the page, include files contain common snippets to reduce the
            redundant styling.  You still can include a main file in another file, for example if you are "subclassing"
            a style.
        </p>
        <p>
            The .less engine currently does not detect dependencies, this means that when you modify an include file you
            must also modify the main file, or you can remove the cached version of the files by browsing to <a href="<?php echo Yii::app()->createUrl('/LessCSS/default/clearCache'); ?>">Clear CSS Cache</a> (Keep this link handy)
        </p>
    </div>

    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a target="_blank" href="http://leafo.net/lessphp/">.less</a></li>
            <li><a target="_blank" href="<?php echo Yii::app()->createUrl('/site/page/', array('view'=>'visualTest')); ?>">style page</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('/LessCss/default/clearCache/'); ?>">Clear CSS Cache</a></li>
        </ul>
    </div>
</div>

<div>
    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a target="_blank" href="https://developers.facebook.com/">Facebook Developers</a></li>
            <li><a target="_blank" href="https://dev.twitter.com/">Twitter Developers</a></li>
        </ul>
    </div>

    <div class="column twoThirds">
        <a id="oauthSupport"><h1>Turn on OAuth</h1></a>
        <p>OAuth is optional and you only need to set it up if you want your users to be able to
            log in through oauth services such as Twitter or Facebook.  Setting it up is fairly simple though,
            and it means your users have much less to do so why not.
            <br/><br/>

            To set it up add the following to the params section in your common.php configuration file:

                <pre><code>
                        // Twitter Keys
                        'twitter'=>array(
                        'consumerKey'=>'my twitter key',
                        'consumerSecret'=>'my twitter secret',
                        ),
                        // Facebook Keys
                        'facebook'=>array(
                        'consumerKey'=>'my facebook key',
                        'consumerSecret'=>'my facebook secret',
                        ),
                    </code></pre>
        </p>

        <p>
            Currently Twitter and Facebook are supported, and to obtain keys you must create an application in the
            relevent service.
        </p>
    </div>
</div>

<div>
    <div class="column twoThirds">
        <a id="register"><h1>Register an account</h1></a>
        <p>Finally get yourself registered for your own app.  Be your first user.
            Click <a href="<?php echo Yii::app()->createUrl('/register/'); ?>">this</a> link or use the menu items in the header or footer of the page.
        </p>
    </div>

    <div class="column third reverse">
        <h1>Resources</h1>
        <ul>
            <li><a href="<?php echo Yii::app()->createUrl('/register/'); ?>">Register</a></li>
        </ul>
    </div>
</div>

</section>