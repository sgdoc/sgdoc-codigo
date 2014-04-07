<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
final class SynchronizerUnit {

    /**
     * @var SynchronizableUnit
     */
    private $_sync = NULL;

    /**
     * @return void
     * @param SynchronizableUnit
     */
    private function __construct(SynchronizableUnit $sync = NULL) {
        $this->_sync = $sync;
    }

    /**
     * @return SynchronizerUnit
     * @param SynchronizableUnit
     */
    public static function factory(SynchronizableUnit $sync = NULL) {
        return new self($sync);
    }

    /**
     * @return array
     */
    public function load() {
        return $this->_sync->load();
    }

    /**
     * @var array
     */
    private $_messages = array(
        1 => 'Sincronizacao disponivel!',
        2 => 'Sincronizacao em andamento...',
        3 => 'Sincronizacao indisponivel!',
        4 => 'Sincronizacao concluida com sucesso! Adds[%d] Changed[%d] Inactivated[%d] Pending[%d]',
        5 => 'Ocorreu um erro na sincronizacao!, verifique o arquivo de log "/tmp/ProviderWebServiceSyncUnits.log" e delete o arquivo "/tmp/ProviderWebServiceSyncUnits.status"',
    );

    /**
     * @return string 
     * 'legenda dos status' => 
     *      1 -> disponivel, 
     *      2 -> processando, 
     *      3 -> indisponivel
     */
    public function status() {

        if (!is_file('/tmp/ProviderWebServiceSyncUnits.status')) {
            $this->_writeStatusInFile(1);
        }

        $status = $this->_readStatusInFile();

        $response = array(
            'status' => $status,
            'message' => $this->_messages[$status]
        );

        return json_encode($response);
    }

    /**
     * @return string
     */
    private function _readStatusInFile() {

        if (!is_file('/tmp/ProviderWebServiceSyncUnits.status')) {
            $this->_writeStatusInFile(1);
        }

        return (integer) current(file('/tmp/ProviderWebServiceSyncUnits.status'));
    }

    /**
     * @return void
     * $param interger $status
     */
    private function _writeStatusInFile($status) {
        file_put_contents('/tmp/ProviderWebServiceSyncUnits.status', (integer) $status);
    }

    /**
     * @return void
     * $param string $error
     */
    private function _writeLogInFile($error) {
        file_put_contents('/tmp/ProviderWebServiceSyncUnits.log', $error);
    }

    /**
     * @return string 
     * 'legenda dos status' => 
     *      1 -> disponivel, 
     *      2 -> processando, 
     *      3 -> indisponivel, 
     *      4 -> sincronizacao concluida, 
     *      5 -> falha na sincronizacao'
     */
    public function startRemoteSync() {

        if ($this->_readStatusInFile() != 1) {
            return $this->status();
        }

        $countChanged = 0;
        $countAdd = 0;
        $countInactivated = 0;
        $countPending = 0;
        $error = '';

        $model = CFModelUnidade::factory();

        try {

            //sincronizacao iniciada...
            $this->_writeStatusInFile(2);

            $externals = $this->_sync->load();

            $model->beginTransaction();

            foreach ($externals as $external) {

                if (!Unit::factory()->isValid($external)) {
                    $error .= "A unidade [{$external->ID}][{$external->SIGLA}][{$external->NOME}] nao e uma unidade valida!<br>";
                    $countPending++;
                    continue;
                }

                $local = $model->find($external->ID);

                //verificar se a unidade externa jah esta cadastrada na base local...
                if (empty($local)) {
                    $model->insert((array) $external, false);
                    $countAdd++;
                    continue;
                }

                $inactive = false;

                //verificar se a registro externo deve ser desativado...
                if ($external->ST_ATIVO == 0) {

                    //verificar se existe algum processo ou documento associado a unidade...
                    if ($this->_existsProcessesOrDocumentsInUnitById($external->ID)) {
                        $error .= "A unidade {$external->NOME} ainda possui documento(s) e/ou processo(s) associado(s) \r\n";
                        $countPending++;
                        continue;
                    }
                    $inactive = true;
                }

                //atualizar unidade externa com a base local...
                if ($model->update((array) $external) == 1) {
                    $countChanged++;
                    if ($inactive) {
                        $countInactivated++;
                    }
                }
            }

            //sincronizacao finalizada...
            $status = 1;
            $model->commit();
            $this->_writeStatusInFile(1);
        } catch (Exception $e) {
            $this->_writeStatusInFile(5);
            $this->_writeLogInFile($e->getMessage());
            $model->rollback();
        }


        $response = array(
            'status' => ($status == 1) ? 4 : $status,
            'message' => ($status == 1) ? sprintf($this->_messages[4], $countAdd, $countChanged, $countInactivated, $countPending) : $this->_messages[$status],
            'error' => $error,
        );

        return json_encode($response);
    }

    /**
     * @return boolean
     * @param integer $id
     */
    private function _existsProcessesOrDocumentsInUnitById($id) {

        $documentos = CFModelDocumento::factory()->findByParam(array(
            'ID_UNID_AREA_TRABALHO' => $id,
            'ID_UNID_CAIXA_ENTRADA' => $id,
            'ID_UNID_CAIXA_SAIDA' => $id), true);

        if (!empty($documentos)) {
            return true;
        }

        $processos = CFModelProcesso::factory()->findByParam(array(
            'ID_UNID_AREA_TRABALHO' => $id,
            'ID_UNID_CAIXA_ENTRADA' => $id,
            'ID_UNID_CAIXA_SAIDA' => $id), true);

        if (!empty($processos)) {
            return true;
        }

        return false;
    }

}