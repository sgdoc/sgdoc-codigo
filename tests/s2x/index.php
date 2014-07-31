<?php
require './classes/Read.php';
require_once './classes/Bootstrap.php';
?>

<!DOCTYPE html>
<html lang="pt-Br">
    <head>
        <meta charset="utf-8">
        <title>S2X by cerberosnash</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 20px;
                padding-bottom: 40px;
                font-family: monospace;
                font-size: 12px;
            }
        </style>
        <link href="vendor/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="vendor/bootstrap/js/html5shiv.js"></script>
        <![endif]-->

    </head>

    <body>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>S2X - [ SELENIUM TO XEBIUM ]</h1>
                <!--<p></p>-->
            </div>
            <p><a href="#" id="run" class="">[ create-all-tests ]</a></p>
            <hr>
            <div class="row">
                <div class="span12">
                    <p id="textDisplay">0/0</p>
                    <div class="progress progress-warning">
                        <div id="barDisplay" class="bar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <hr>
            <hr>

            <?php $i = 0; ?>
            <?php foreach (Read::factory()->directoryToArray(Bootstrap::factory()->config('config.input.selenium'), true) as $key => $path): ?>
                <?php if (strpos($path, '.selenium') !== false): ?>
                    <div class="row test" id='row<?php print ++$i; ?>'>
                        <div class="span12">
                            <p><?php print "[{$i}] [<a class='run-unit' test='{$i}' href='#'>run</a>] {$path}"; ?></p>
                            <input class="path" type="hidden" value="<?php print $path; ?>">
                            <div id="bar<?php print $i; ?>" class="progress progress-striped active">
                                <div class="bar" style="width: 0%;"></div>
                            </div>
                            <div class="alert">
                                aguardando...
                            </div>
                        </div>
                        <hr>
                        <hr>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>


            <footer>
                <p>&copy; 2014 by cerberosnash</p>
            </footer>

        </div> <!-- /container -->

    </body>
</html>

<script type="text/javascript" src="vendor/selenium-f39246684647/ide/main/src/content/tools.js"></script>
<script type="text/javascript" src="vendor/selenium-f39246684647/ide/main/src/content/testCase.js"></script>
<script type="text/javascript" src="vendor/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="formatter/xebium.js"></script>
<script type="text/javascript">

    var n = 1;
    var c = 0;
    var p = 0;

    $.ajaxSetup({async: false});

    $(document).ready(function() {
        $('#run').click(function() {
            $('.test').each(function() {
                read(n++);
            });
        });

        $('.run-unit').click(function(e) {
            e.preventDefault();
            read($(this).attr('test'));
        });

        $('#textDisplay').html(c + '/' + $('.test').length);

    });

    function updateDisplay() {

        p = (++c * 100) / $('.test').length;

        $('#barDisplay').css('width', Math.round(p) + '%');
        $('#textDisplay').html(c + '/' + $('.test').length, Math.round(p));
    }

    function read(id) {

        $('#row' + id + ' .bar').css('width', '50%');
        $('#row' + id + ' .alert').html('lendo teste selenium...');
        $('#row' + id + ' .alert').removeClass('alert-error').addClass('alert-info');

        $.post('reader.php', {path: $('#row' + id + ' .path').val()}, function(response) {

            $('#row' + id + ' .bar').css('width', '75%');
            $('#row' + id + ' .alert').html('convertendo teste selenium para xebium...');
            $('#row' + id + ' .alert').removeClass('alert-info').addClass('alert-warning');

            write(id, response);
        });


    }

    function write(id, response) {

        try {
            eval(response);
        } catch (e) {
            $('#row' + id + ' .alert').removeClass('alert-info').addClass('alert-error').html(e);
            $('#bar' + id).removeClass('progress-striped').removeClass('active').addClass('progress-danger');
        }

        $.post('write.php', {path: transport.path, content: transport.xebium}, function(response) {

            $('#row' + id + ' .bar').css('width', '100%');
            $('#row' + id + ' .alert').html(response.message);

            if (response.success === 'true') {
                $('#row' + id + ' .alert').removeClass('alert-info').addClass('alert-success');
                $('#bar' + id).removeClass('progress-striped').removeClass('active').addClass('progress-success');
                $('#row' + id).hide();
            } else {
                $('#row' + id + ' .alert').removeClass('alert-info').addClass('alert-error');
                $('#bar' + id).removeClass('progress-striped').removeClass('active').addClass('progress-danger');
            }

            updateDisplay();

        }, 'json');

    }

</script>