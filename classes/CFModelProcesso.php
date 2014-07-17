<?php

class CFModelProcesso extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_PROCESSOS_CADASTRO';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = '';

    /**
     * @var array
     */
    protected $_fields = array(
        'NUMERO_PROCESSO' => 'string',
        'ORIGEM' => 'integer',
        'INTERESSADO' => 'integer',
        'ASSUNTO' => 'integer',
        'ASSUNTO_COMPLEMENTAR' => 'string',
        'CPF_CNPJ' => 'string',
        'RESPONSAVEL' => 'string',
        'PROCEDENCIA' => 'string',
        'DT_AUTUACAO' => 'date',
        'DT_PRAZO' => 'date',
        'FG_PRAZO' => 'integer',
        'DT_CADASTRO' => 'date',
        'USUARIO' => 'integer',
        'ID_UNIDADE_USUARIO' => 'integer',
        'ID_UNID_CAIXA_ENTRADA' => 'integer',
        'ID_UNID_AREA_TRABALHO' => 'integer',
        'ID_UNID_CAIXA_SAIDA' => 'integer',
        'EXTERNO' => 'string',
        'ULTIMO_TRAMITE' => 'string'
    );

}