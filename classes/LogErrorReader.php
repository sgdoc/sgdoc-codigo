<?php

/**
 * 
 * Copyright 2011 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 *
 */
class LogErrorReader
{

    /**
     * @var string
     */
    private $_path = 'cache/logs/erros.log';

    /**
     * @return void
     */
    public function __construct ()
    {
        if (!is_dir('cache/logs')) {
            mkdir('cache/logs', 0777);
        }
    }

    /**
     * @return LogErrorReader
     */
    public static function factory ()
    {
        return new self();
    }

    /**
     * @return void
     */
    public function isExists ()
    {
        return is_file($this->_path);
    }

    /**
     * @return string
     */
    public function render ()
    {
        return ($this->isExists()) ? file_get_contents($this->_path) : '';
    }

    /**
     * @return string
     */
    public function time ()
    {
        return ($this->isExists()) ? sprintf('Último log gerado em [%s]', date("d/m/Y H:i:s", filemtime($this->_path))) : '';
    }
    
    /**
     * @return boolean 
     */
    public function clearCacheRecursos ()
    {
        try {
            if (!Controlador::getInstance()->cache->clean('matchingAnyTag', array('paginas'))) {
                throw new \Exception('Erro na limpeza de cache');
            }
        } catch (Exception $e) {
            throw new \Exception('Erro na limpeza de cache: '. $e->getMessage());
        }
        
        return $this;
    }
    
    /**
     * @return boolean 
     */
    public function clearAllCache ()
    {
        try {
            Controlador::getInstance()->cache->clean();
        } catch (Exception $e) {
            throw new \Exception('Erro na limpeza de cache: '. $e->getMessage());
        }
        
        return $this;
    }
    

    /**
     * @return boolean
     */
    public function delete ()
    {
        if (is_file($this->_path)) {
            unlink($this->_path);
            return $this;
        }

        throw new \Exception('Nenhum log foi registrado!');
    }

    /**
     * @return void
     * @param string $message
     */
    public function html ($message = '')
    {
        printf('<html>
                    <body>
                        <div class="menu">
                            <form>
                                <button name="action" value="back" type="submit">VOLTAR</button>
                                <button name="action" value="delete" type="submit">LIMPAR</button>
                                <button name="action" value="tester" type="button" id="test_errors">TESTAR</button>
                                <button name="action" value="clearRecursos" type="submit">LIMPAR CACHE RECURSOS</button>
                                <button name="action" value="clearAll" type="submit">LIMPAR CACHE GERAL</button>
                                <button name="action" value="list" id="reload_log" type="button">RECARREGAR</button>
                            </form>
                        </div>
                        <div id="reload">
                        %s
                        </div>
                    </body>
                </html>', $this->getLog($message));
    }
    
    /**
     *
     * @param string $message 
     */
    public function getLog ($message = '')
    {
        printf('<span class="message"><strong>%s</strong></span>
                <div class="log"><textarea>%s</textarea></div>', $message, $this->render());
    }

    /**
     * @return LogErrorReader
     */
    public function tester ()
    {
        ini_set('display_errors', 'Off');
//        is_file();//???
        include_once('Log.php');
//        include('Log.php');
        return $this;
    }

}