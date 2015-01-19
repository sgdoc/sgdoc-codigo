<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class ProviderWebServiceSyncUnits {

    /**
     * @return string 
     * 'legenda dos status' => 
     *      1 -> disponivel, 
     *      2 -> processando, 
     *      3 -> indisponivel
     */
    public function status() {
        return SynchronizerUnit::factory()->status();
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
        $namespace = __ADAPTER_SINCRONIZACAO_UNIDADE__;
        return SynchronizerUnit::factory(new $namespace)->startRemoteSync();
    }

}