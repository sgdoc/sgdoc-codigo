<html>
    <head>
        <title>Monitoramento</title>
        <link type="text/css" rel="stylesheet" href="plugins/dhtmlxGantt/codebase/dhtmlxgantt.css">
        <script type="text/javascript" language="JavaScript" src="plugins/dhtmlxGantt/codebase/dhtmlxcommon.js"></script>
        <script type="text/javascript" language="JavaScript" src="plugins/dhtmlxGantt/codebase/dhtmlxgantt.js"></script>
        <style type="text/css">
            .targetGanttChartControl{
                width: 100%;
                height: 100%;
                position: relative;
            }
        </style>
    </head>
    <body>
        <?php
        $relatorio = new Relatorio();
        $array = $relatorio::listGantt();

        function calculeHours ($inicio, $final)
        {
            return date("d", strtotime($inicio) - strtotime($final)) * 24;
        }

        $max = array();
        $tasks = $projects = array();
        $gantt = Gantt::factory('targetGanttChartControl');

        foreach ($array as $key => $value) {

            if (!isset($max[$value['ID_ASSUNTO']])) {
                $max[$value['ID_ASSUNTO']] = '2099-01-01';
            }

            if ($max[$value['ID_ASSUNTO']] > $value['DT_DOCUMENTO']) {
                $max[$value['ID_ASSUNTO']] = $value['DT_DOCUMENTO'];
            }

            $hours = calculeHours($value['DT_PRAZO'], $value['DT_DOCUMENTO']);
            $tasks[$value['ID_ASSUNTO']][] = Task::factory($value['SQ_PRAZO'], $value['DIGITAL'] . ' - ' . $value['INTERESSADO'], $value['DT_DOCUMENTO'], $hours, ($value['DT_RESPOSTA']) ? 100 : 0, '');
            $projects[$value['ID_ASSUNTO']] = Project::factory($value['ID_ASSUNTO'], $value['ID_ASSUNTO'] . ' - ' . $value['NM_ASSUNTO'], $max[$value['ID_ASSUNTO']]);
        }

        foreach ($projects as $key => $project) {
            foreach ($tasks[$key] as $index => $task) {
                $project->addTask($task);
            }
            $gantt->addProject($project);
        }
        $gantt->render();
        ;


//exit;
//
//        $project1 = Project::factory(1, 'Violencia', '2013-01-14');
//
//        $project1->addTask(
//                        Task::factory('11', '1.1', '2013-01-15', '24', 10, '')
//                        ->addTask(
//                                Task::factory('111', '1.1.1', '2013-01-15', '12', 100, ''))
//                        ->addTask(
//                                Task::factory('112', '1.1.2', '2013-01-15', '11', 65, '')
//                                ->addTask(
//                                        Task::factory('1121', '1.1.2.1', '2013-01-16', '1', 45, '')
//                                )
//                        )
//                )
//                ->addTask(
//                        Task::factory('12', '1.2', '2013-01-19', '48', 55, '')
//                )
//                ->addTask(
//                        Task::factory('13', '1.3', '2013-01-25', '72', 65, '112')
//                )
//        ;
//
//        $project2 = Project::factory(2, 'Sociedade', '2013-05-12');
//
//        $project2->addTask(
//                Task::factory('21', '2.1', '2013-05-12', '48', 22, '')
//        )
//        ;
//
//        $project3 = Project::factory(3, 'League of Legends', '2013-03-01');
//
//        $project3->addTask(
//                        Task::factory('31', '3.1', '2013-03-02', '12', 75, '')
//                )
//                ->addTask(
//                        Task::factory('32', 'Mobilizacao Social', '2013-03-04', '24', 0, '31')
//                )
//        ;
//
//        Gantt::factory('targetGanttChartControl')
//                ->addProject($project1)
//                ->addProject($project2)
//                ->addProject($project3)
//                ->render()
//        ;
        exit;
        ?>
    </body>
</html>