
        <h1>Welcome to Adive!</h1>
        <p class="lead">
            Congratulations! Your application is running. If this is
            your first time using Adive, start with this <a href="<?=basePath()?>/hello/world">"Hello World" Example</a>.
        </p>
        <section>
            <h2>Get Started</h2>
            <ol>
                <li>The application router/controller is in <code>Controller/Default.php</code></li>
                <li>The HTML5 views in <code>Views/Default/home.php</code></li>
                <li>Read the <a href="http://adive.es/" target="_blank">online documentation</a></li>
            </ol>
        </section>
        <section style="padding-bottom: 20px;">
            <h2>Auth Security incorporated</h2>
            <p>
                Check the integrated security with Authorization Header, 
                start with this <a href="<?=basePath()?>/secured">"Protected path" Example</a> and get PublicKey in <code>Controller/Default.php</code>. Change <code>errorAuth();</code> with <code>printAuth();</code> and start with your secured Api's.
            </p>
        </section>
        <section>
            <h2>Adive Framework Community</h2>
            <p>
                Visit the Adive's official site for support and knowledge base 
                to read notifications, ask questions, help others, or expose your Adive Framework apps.
            </p>
        </section>
        <section style="padding-bottom: 40px">
            <h2>Adive Dashboard</h2>
            <p>
                Start to design your app or website with the Adive Admin Dashboard, 
                speacially implemente to design without coding.
            </p>
            <p><a href="<?=path('adashboard')?>" class="btn btn-primary">Go to Dashboard</a></p>
        </section>
        <section style="padding-bottom: 10px">
            <p><i>Adive Framework Version.<?=Adive\Adive::VERSION?></i></p>
        </section>
