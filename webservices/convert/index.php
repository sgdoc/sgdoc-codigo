<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Relatório</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/sb-admin.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="css/plugins/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style type="text/css">

            #log-errors{
                width: 100%;
                height: 400px;
            }

        </style>

    </head>

    <body>

        <div id="wrapper">


            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                Relatório
                            </h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-calendar"></i> Agendamento </h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-condensed text-center text-muted text-nowrap">
                                        <tr class="">
                                            <td class="">Madrugada</td>
                                            <td>Dom<br><input type="checkbox" id="day_0_HALF"></td>
                                            <td>Seg<br><input type="checkbox" id="day_1_HALF"></td>
                                            <td>Ter<br><input type="checkbox" id="day_2_HALF"></td>
                                            <td>Qua<br><input type="checkbox" id="day_3_HALF"></td>
                                            <td>Qui<br><input type="checkbox" id="day_4_HALF"></td>
                                            <td>Sex<br><input type="checkbox" id="day_5_HALF"></td>
                                            <td>Sáb<br><input type="checkbox" id="day_6_HALF"></td>
                                        </tr>
                                        <tr class="">
                                            <td class="">Dia Todo</td>
                                            <td>Dom<br><input type="checkbox" id="day_0_FULL"></td>
                                            <td>Seg<br><input type="checkbox" id="day_1_FULL"></td>
                                            <td>Ter<br><input type="checkbox" id="day_2_FULL"></td>
                                            <td>Qua<br><input type="checkbox" id="day_3_FULL"></td>
                                            <td>Qui<br><input type="checkbox" id="day_4_FULL"></td>
                                            <td>Sex<br><input type="checkbox" id="day_5_FULL"></td>
                                            <td>Sáb<br><input type="checkbox" id="day_6_FULL"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Conversão Imagens </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-pie-chart"></div>
                                    </div>
                                    <div id="flot-memo" style="text-align:center;height:30px;width:250px;height:20px;text-align:center;margin:0 auto"></div>


                                    <div class="text-right">
                                        <a id="btnStop" href="#">Parar <i class="fa fa-arrow-circle-right"></i></a>
                                        <a id="btnStart" href="#">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Log de Errors</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id=""><textarea id="log-errors"></textarea></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->

    <!-- Flot Charts JavaScript -->
    <!--[if lte IE 8]><script src="js/excanvas.min.js"></script><![endif]-->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/flot-data.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {

            $('#btnStart').hide();
            $('#btnStop').hide();

            $('#btnStart').click(function() {
                $(this).hide();
                $('#btnStop').show();
                $.post('start.php');
            });

            $('#btnStop').click(function() {
                $(this).hide();
                $('#btnStart').show();
                $.post('stop.php');
            });

            function updateChart() {

                $.ajax({
                    url: 'data.php',
                    dataType: 'text',
                    success: function(data) {
                        data = eval(data);

                        var hour = new Date().getHours();
                        var day = new Date().getDay();

                        var full = $('#day_' + day + '_FULL').is(':checked');
                        var half = $('#day_' + day + '_HALF').is(':checked');

                        var madrugada = (hour === 22 || hour === 23 || (hour >= 0 && hour <= 6));

                        var locked = data[2].locked;

                        $('#log-errors').html(data[3].errors);

                        if (data[2].locked === true) {
                            $('#btnStart').hide();
                            $('#btnStop').show();
                        } else {
                            $('#btnStart').show();
                            $('#btnStop').hide();
                        }

                        if (full) {
                            if (full && locked === false) {
                                $('#btnStart').click().hide();
                                $('#btnStop').show();
                            }
                        } else {

                            if ((!full && locked === true) && (!half & locked === true)) {
                                $('#btnStop').click().hide();
                                $('#btnStart').show();
                            }

                            if (half && locked === false && madrugada === true) {
                                $('#btnStart').click().hide();
                                $('#btnStop').show();

                            }

                            if (half && (madrugada === false) && locked === true) {
                                $('#btnStop').click().hide();
                                $('#btnStart').show();
                            }
                        }

                        $.plot($("#flot-pie-chart"), data, {
                            legend: {
                                show: false,
                                margin: 0,
                                backgroundOpacity: 1.0
                            },
                            series: {
                                pie: {
                                    show: true
                                }
                            },
                            grid: {
                                hoverable: true
                            },
                            tooltip: true,
                            tooltipOpts: {
                                content: "%p.2%, %s", // show percentages, rounding to 2 decimal places
                                shifts: {
                                    x: 20,
                                    y: 0
                                },
                                defaultTheme: true
                            }
                        });
                    }
                });
            }

            updateChart();

            interval = setInterval(function() {
                updateChart()
            }, 1000 * 30);

        });

        $.fn.showMemo = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (!item) { return; }

                var html = [];
                var percent = parseFloat(item.series.percent).toFixed(2);

                html.push("<div style=\"border:1px solid grey;background-color:",
                     item.series.color,
                     "\">",
                     "<span style=\"color:white\">",
                     item.series.label,
                     " : ",
                     item.series.data[0][1],
                     " (", percent, "%)",
                     "</span>",
                     "</div>");
                $("#flot-memo").html(html.join(''));
            });
        };

        $("#flot-pie-chart").showMemo();


    </script>

</body>

</html>
