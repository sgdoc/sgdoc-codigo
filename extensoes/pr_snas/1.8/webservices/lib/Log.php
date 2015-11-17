<?php
class Log {
    protected $log;
    protected $fileLog;
    protected $pathLog;
    protected $nameLog;
    protected $printScreen = false;

    public function __construct($path, $tipo) {
        umask(0000);
        $this->nameLog = '/' . $tipo . date('dmYHis') . '.log';
        $this->pathLog = $path;
        $this->log = '[' . date('d/m/Y H:i:s') . "] -> Início do processamento\n";
    }

    public function addLog($message) {
        $this->log .= '[' . date('d/m/Y H:i:s') . "] -> {$message}\n";
        $this->writeLog();
    }

    public function errorLog(Exception $exception) {
    	$this->log .= '[' . date('d/m/Y H:i:s') . "] -> ERRO:\n";
    	$this->log .= '[' . date('d/m/Y H:i:s') . "] -> Linha: {$exception->getLine()}\n";
    	$this->log .= '[' . date('d/m/Y H:i:s') . "] -> Mensagem: {$exception->getMessage()}\n";
        $this->log .= '[' . date('d/m/Y H:i:s') . "] -> Trace: {$exception->getTraceAsString()}\n";
        $this->log .= '[' . date('d/m/Y H:i:s') . "] -> Processamento Interrompido!!!!!\n";
        $this->writeLog();
    }

    public function generateLog() {
        $this->log .= '[' . date('d/m/Y H:i:s') . "] -> Final do processamento\n";
        $this->writeLog();
    }

    public function setPrintScreen($print) {
    	$this->printScreen = $print;
    }
    
    private function writeLog() {
    	if ($this->printScreen) {
    		echo $this->log;
    	}
        if (is_dir($this->pathLog)) {
            $file = fopen($this->pathLog . $this->nameLog, 'a');
            fputs($file, $this->log);
            fclose($file);
        }
        $this->log = '';
    }
}