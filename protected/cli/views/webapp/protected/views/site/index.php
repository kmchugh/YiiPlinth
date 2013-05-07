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

        <div class="column third reverse">
            <h1>Resources</h1>
            <ul>
                <li><a href="http://www.yiiframework.com/doc/guide/1.1/en/topics.console">yiic</a></li>
                <li><a href="http://www.yiiframework.com/doc/guide/1.1/en/database.migration">dbsync (migrate)</a></li>
            </ul>
        </div>
    </div>

    <div>
        <div class="column third reverse">
            <h1>Resources</h1>
            <ul>
                <li><a target="_blank" href="http://leafo.net/lessphp/">.less</a></li>
                <li><a target="_blank" href="<?php echo Yii::app()->createUrl('/site/page/', array('view'=>'visualTest')); ?>">style page</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('/LessCss/default/clearCache'); ?>">Clear CSS Cache</a></li>
            </ul>
        </div>

        <div class="column twoThirds">
            <a id="stylePage"><h1>Check out the style</h1></a>
            <p>
                The <a target="_blank" href="<?php echo Yii::app()->createUrl('/site/page/', array('view'=>'visualTest')); ?>">style page</a> allows you to modify and review the styling applied to the site in one view.
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
                must also modify the main file, or you can remove the cached version of the files by browsing to <a href="<?php echo Yii::app()->createUrl('/LessCss/default/clearCache'); ?>">Clear CSS Cache</a> (Keep this link handy)
            </p>
        </div>
    </div>



    <div class="column whole">
        <a id="emailSupport"><h1>Set up email support</h1></a>
        <p>content</p>
    </div>

    <div class="column whole">
        <a id="automationSupport"><h1>Start up automation</h1></a>
        <p>content</p>
    </div>

    <div class="column whole">
        <a id="oauthSupport"><h1>Turn on OAuth</h1></a>
        <p>content</p>
    </div>

    <div class="column whole">
        <a id="register"><h1>Register an account</h1></a>
        <p>content</p>
    </div>
    </div>

</section>