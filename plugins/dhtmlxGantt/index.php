<html>
    <head>
        <title>Monitoramento</title>
        <link type="text/css" rel="stylesheet" href="codebase/dhtmlxgantt.css">
        <script type="text/javascript" language="JavaScript" src="codebase/dhtmlxcommon.js"></script>
        <script type="text/javascript" language="JavaScript" src="codebase/dhtmlxgantt.js"></script>
        <style type="text/css">
            .targetGanttChartControl{
                width: 100%;
                height: 670px;
                position: relative;
            }
        </style>
    </head>
    <body>
        <?php
        $project1 = Project::factory(1, 'Violencia', '2013-01-14');

        $project1->addTask(
                        Task::factory('11', '1.1', '2013-01-15', '24', 10, '')
                        ->addTask(
                                Task::factory('111', '1.1.1', '2013-01-15', '12', 100, ''))
                        ->addTask(
                                Task::factory('112', '1.1.2', '2013-01-15', '11', 65, '')
                                ->addTask(
                                        Task::factory('1121', '1.1.2.1', '2013-01-16', '1', 45, '')
                                )
                        )
                )
                ->addTask(
                        Task::factory('12', '1.2', '2013-01-19', '48', 55, '')
                )
                ->addTask(
                        Task::factory('13', '1.3', '2013-01-25', '72', 65, '112')
                )
        ;

        $project2 = Project::factory(2, 'Sociedade', '2013-05-12');

        $project2->addTask(
                Task::factory('21', '2.1', '2013-05-12', '48', 22, '')
        )
        ;

        $project3 = Project::factory(3, 'League of Legends', '2013-03-01');

        $project3->addTask(
                        Task::factory('31', '3.1', '2013-03-02', '12', 75, '')
                )
                ->addTask(
                        Task::factory('32', 'Mobilizacao Social', '2013-03-04', '24', 0, '31')
                )
        ;

        Gantt::factory('targetGanttChartControl')
                ->addProject($project1)
                ->addProject($project2)
                ->addProject($project3)
                ->render()
        ;
        ?>
    </body>
</html>

<?php

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Gantt
{

    /**
     * @var integer
     */
    private $_id = 0;

    /**
     * @var Project[]
     */
    private $_projects = array();

    /**
     * @var array
     */
    private $_tmpl = array(
        'script' => "<script type='text/javascript'>%s</script>\r\n",
        'project' => "var project%d = new GanttProjectInfo(%d, '%s', new Date(%s));\r\n",
        'task' => "var task%d = new GanttTaskInfo(%d, '%s', new Date(%s), %d, %d, '%s');\r\n",
        'addTaskInProject' => "project%d.addTask(task%d);\r\n",
        'addTaskInTask' => "task%d.addChildTask(task%d);\r\n",
        'addProjectInGantt' => "ganttChartControl%s.addProject(project%d);\r\n",
        'createGantt' => "ganttChartControl%s.create('%s');\r\n",
        'html' => "<div class='targetGanttChartControl' id='%s'></div>\r\n",
    );

    /**
     * @return void
     */
    private function __construct ($target)
    {
        $this->_id = $target;
    }

    /**
     * @return Gantt
     */
    public static function factory ($target = 'targetGanttChartControl')
    {
        return new self($target);
    }

    /**
     * @return Gantt 
     */
    public function addProject (Project $project)
    {
        $this->_projects[$project->id] = $project;
        return $this;
    }

    /**
     * @return string
     */
    private function _subTasks ($parent)
    {
        if (!isset($script)) {
            $script = '';
        }

        foreach ($parent->tasks as $task) {
            $script .= sprintf($this->_tmpl['task'], $task->id, $task->id, $task->name, str_replace('-', ',', $task->date), $task->duration, $task->percent, $task->parent);
            if (!empty($task->tasks)) {
                $script .= $this->_subTasks($task);
            }
            $script .= sprintf($this->_tmpl['addTaskInTask'], $parent->id, $task->id);
        }

        return $script;
    }

    /**
     * @return void
     */
    public function render ()
    {
        printf($this->_tmpl['html'], $this->_id);

        $script = sprintf("var ganttChartControl%s = new GanttChart();\r\n"
                . "ganttChartControl%s.setImagePath('codebase/imgs/');\r\n"
                . "ganttChartControl%s.setEditable(false);\r\n"
                . "ganttChartControl%s.shortMonthNames = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];\r\n"
                . "ganttChartControl%s.monthNames = ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];\r\n"
                . "ganttChartControl%s.useShortMonthNames(false);\r\n", $this->_id, $this->_id, $this->_id, $this->_id, $this->_id, $this->_id)
        ;

        foreach ($this->_projects as $project) {

            $script .= sprintf($this->_tmpl['project'], $project->id, $project->id, $project->name, str_replace('-', ',', $project->date));

            foreach ($project->tasks as $task) {

                $script .= sprintf($this->_tmpl['task'], $task->id, $task->id, $task->name, str_replace('-', ',', $task->date), $task->duration, $task->percent, $task->parent);
                $script .= $this->_subTasks($task);
                $script .= sprintf($this->_tmpl['addTaskInProject'], $project->id, $task->id);
            }

            $script .= sprintf($this->_tmpl['addProjectInGantt'], $this->_id, $project->id);
        }

        $script .= sprintf($this->_tmpl['createGantt'], $this->_id, $this->_id);

        printf($this->_tmpl['script'], $script);
    }

}

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Project
{

    /**
     * @var integer
     */
    public $id = 0;

    /**
     * @var string
     */
    public $name = 0;

    /**
     * @var date
     */
    public $date = '0000-00-00';

    /**
     * @var array
     */
    public $tasks = array();

    /**
     * @return void
     */
    private function __construct ($id, $name, $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
    }

    /**
     * @return Project
     */
    public static function factory ($id, $name, $date)
    {
        return new self($id, $name, $date);
    }

    /**
     * @return Project
     */
    public function addTask (Task $task)
    {
        $this->tasks[$task->id] = $task;
        return $this;
    }

}

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Task
{

    /**
     * @var integer
     */
    public $id = 0;

    /**
     * @var string
     */
    public $name = 0;

    /**
     * @var date
     */
    public $date = '0000-00-00';

    /**
     * @var integer
     */
    public $duration = 0;

    /**
     * @var integer
     */
    public $percent = 0;

    /**
     * @var integer
     */
    public $parent = '';

    /**
     * @var array
     */
    public $tasks = array();

    /**
     * @return void
     */
    private function __construct ($id, $name, $date, $duration, $percent, $parent = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->duration = $duration;
        $this->percent = $percent;
        $this->parent = $parent;
    }

    /**
     * @return Task
     */
    public static function factory ($id, $name, $date, $duration, $percent, $parent = '')
    {
        return new self($id, $name, $date, $duration, $percent, $parent);
    }

    /**
     * @return Task
     */
    public function addTask (Task $task)
    {
        $this->tasks[$task->id] = $task;
        return $this;
    }

}