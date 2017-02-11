<?php if($ok!='ok'): ?>
        <h1>Configure your Database</h1>
        <p class="lead">
            Fill the fields to complete the configuration. If database does not exist Adive tries to create it.
        </p>
        <section>
            <?php if($error!='no'): ?>
            <span class="alert alert-danger"><?php echo $error ?></span>
            <?php endif; ?>
            
            <h2>Database connection</h2>
                <form id="formulario" role="form" method="post">
                        <div class="form-group">
                        <label for="id">Host</label>
                        <input type="text" class="form-control" id="host" name="host" placeholder="localhost" value="localhost">
                        <label for="serial">Database name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Database name">
                        <label for="tipo">Database user</label>
                        <input type="text" class="form-control" id="user" name="user" placeholder="Database user">
                        <label for="activa">Database password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Database password">
                        </div>
                        <button type="submit" class="btn btn-primary" id="alta">Save configuration</button> 

                        <div id="mensajes"></div>
                </form>
        </section>
<?php else: ?>
        <h1>Database Configured!</h1>
        <p class="lead">
            Please, <a href="<?=basePath()?>">visit home page of Adive framework</a>.
        </p>
<?php endif; ?>
        <section style="padding-top: 20px">
            <p><i>Adive Framework Version.<?=Adive\Adive::VERSION?></i></p>
        </section>
