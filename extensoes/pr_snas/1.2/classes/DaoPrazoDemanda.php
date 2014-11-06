<?php

/*
 * Copyright 2008 ICMBio
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
 * */

include(__BASE_PATH__ . '/classes/DaoPrazo.php');

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class DaoPrazoDemanda extends DaoPrazo {

    /**
     * 
     */
    public static function getPrazo($prazo = false, $campo = false) {
        try {
        	
            $campo = $campo ? $campo : '*';

            if (!$prazo) {
                throw new Exception($e);
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_CONTROLE_PRAZOS WHERE SQ_PRAZO = ? LIMIT 1");
            $stmt->bindParam(1, $prazo, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                $out = array_change_key_case(($out), CASE_LOWER);

                if ($campo === '*') {
                    return $out;
                }
                return $out[strtolower($campo)];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Retorna o primeiro prazo (demanda) de uma demanda
     */
    public static function getPrimeiroPrazo($digDemanda = false) {
    	try {
    		
    		if (!$digDemanda) {
    			throw new Exception($e);
    		}
    	
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT MIN(SQ_PRAZO) AS ID FROM TB_CONTROLE_PRAZOS WHERE NU_PROC_DIG_REF = ?");
    		$stmt->bindParam(1, $digDemanda, PDO::PARAM_STR);
    		$stmt->execute();
    	
    		$out = $stmt->fetch(PDO::FETCH_COLUMN);
    	
    		if (!empty($out)) {
    			//$out = array_change_key_case(($out), CASE_LOWER);

    			return self::getPrazo($out) ;
    		}
    		return false;
    		
    	} catch (PDOException $e) {
    		throw new Exception($e);
    	}
    }

	/**
	 * Carrega dados do prazo e da resposta
	 * @param string $seqPrazo
	 * @throws Exception
	 * @return Ambigous <multitype:, mixed>|Ambigous <>|boolean
	 */
    public static function getPrazoResposta($seqPrazo = false) {
    	try {
    		 
    		if (!$seqPrazo) {
    			throw new Exception($e);
    		}
    
    		$sql = "SELECT cp.sq_prazo, trim(ext.nu_proc_dig_ref_pai) AS digital_pai, cp.nu_proc_dig_ref AS nu_ref, 
  						COALESCE(pro_int.interessado, doc.interessado) AS interessado,
  						uso.nome AS nm_usuario_origem, uno.nome AS nm_unidade_origem, 
  						to_char(cp.dt_prazo::timestamp with time zone, 'dd/mm/yyyy'::text) AS dt_prazo,
    					doc.tipo, cp.tx_solicitacao, doc.assunto, doc.assunto_complementar,
    					cp.fg_status, cp.tx_resposta, coalesce(ext.ha_vinculo, true) as ha_vinculo,
    					coalesce(ext.legislacao_situacao, 0) as legislacao_situacao, ext.legislacao_descricao
					FROM sgdoc.tb_controle_prazos cp
					  	LEFT JOIN sgdoc.ext__snas__tb_controle_prazos ext ON ext.id = cp.sq_prazo
					  	LEFT JOIN sgdoc.tb_unidades uno ON uno.id = cp.id_unid_origem
					  	LEFT JOIN sgdoc.tb_usuarios uso ON uso.id = cp.id_usuario_origem
					  	LEFT JOIN sgdoc.tb_documentos_cadastro doc ON doc.digital::text = cp.nu_proc_dig_ref::text
					  	LEFT JOIN (sgdoc.tb_processos_cadastro pro_cad
					  	JOIN sgdoc.tb_processos_interessados pro_int ON pro_int.id = pro_cad.interessado) ON pro_cad.numero_processo::text = cp.nu_proc_dig_ref::text
					WHERE cp.sq_prazo = ?;";
    		
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    		$stmt->bindParam(1, $seqPrazo, PDO::PARAM_INT);
    		$stmt->execute();
    
    		$out = $stmt->fetch(PDO::FETCH_ASSOC);
    
    		if (!empty($out)) {
    			$out = array_change_key_case(($out), CASE_LOWER);
   				return $out;
    		}
    
    		return false;
    	} catch (PDOException $e) {
    		throw new Exception($e);
    	}
    }
    
    /**
     * 
     */
    public static function salvarPrazo(Prazo $prazo) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!isset($prazo->prazo->id_usuario_destino) || $prazo->prazo->id_usuario_destino == '') {
                $prazo->prazo->id_usuario_destino = NULL;
            }

            $prazo->id_unid_origem = isset($prazo->prazo->id_unid_origem) ?
                    $prazo->prazo->id_unid_origem : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $prazo->id_usuario_origem = Controlador::getInstance()->usuario->ID;
            
            $dt_prazo = Util::formatDate($prazo->prazo->dt_prazo);
            $pai =  strlen($prazo->prazo->nu_proc_dig_ref_pai) > 0 ? $prazo->prazo->nu_proc_dig_ref_pai : null ;
            
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CONTROLE_PRAZOS (NU_PROC_DIG_REF, ID_USUARIO_ORIGEM, ID_USUARIO_DESTINO, ID_UNID_ORIGEM, ID_UNID_DESTINO, DT_PRAZO, TX_SOLICITACAO)
            VALUES (?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $prazo->prazo->nu_proc_dig_ref, PDO::PARAM_STR);
            $stmt->bindParam(2, $prazo->prazo->id_usuario_origem, PDO::PARAM_INT);
            $stmt->bindParam(3, $prazo->prazo->id_usuario_destino, PDO::PARAM_INT);
            $stmt->bindParam(4, $prazo->prazo->id_unid_origem, PDO::PARAM_INT);
            $stmt->bindParam(5, $prazo->prazo->id_unid_destino, PDO::PARAM_INT);
            $stmt->bindParam(6, $dt_prazo, PDO::PARAM_STR);
            $stmt->bindParam(7, $prazo->prazo->tx_solicitacao, PDO::PARAM_STR);
            $stmt->execute();
            
            $lastIdPrazo = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CONTROLE_PRAZOS_SQ_PRAZO_SEQ');

            $sttt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO EXT__SNAS__TB_CONTROLE_PRAZOS (ID,NU_PROC_DIG_REF_PAI) VALUES (?,?)");
            $sttt->bindParam(1, $lastIdPrazo, PDO::PARAM_INT);
            $sttt->bindParam(2, $pai, PDO::PARAM_STR);
            $sttt->execute();

            new Log('TB_CONTROLE_PRAZOS', $lastIdPrazo, Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Prazo cadastrado com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function removerPrazo($digital) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CONTROLE_PRAZOS_SQ_PRAZO_SEQ');

            $stml = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_LOGS WHERE NM_TABELA = 'TB_CONTROLE_PRAZOS' AND ID_REGISTRO = ?");
            $stml->bindParam(1, $id, PDO::PARAM_INT);
            $stml->execute();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_CONTROLE_PRAZOS WHERE NU_PROC_DIG_REF = ?");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'error' => 'Prazo excluido com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    public static function responderPrazo(Prazo $prazo) {
    	try {
    	
    		Controlador::getInstance()->getConnection()->connection->beginTransaction();
    	
    		self::salvarResposta(Controlador::getInstance()->getConnection(), $prazo);
    		
    		self::enviarResposta(Controlador::getInstance(), $prazo);
    	
    		Controlador::getInstance()->getConnection()->connection->commit();
    	
    		return new Output(array('success' => 'true', 'message' => 'Prazo respondido com sucesso!'));
    	} catch (Exception $e) {
    		Controlador::getInstance()->getConnection()->connection->rollBack();
    		return new Output(array('success' => 'false', 'error' => $e->getMessage()));
    	}
    	
    }
    
    /**
     * A ação só é permitida aos usuários que estão relacionados como destinatários
     * ou àqueles que efetivamente estejam lotados na unidade de destino, não sendo
     * possível realizar, por exemplo, a usuários que trocaram de Unidade 
     * temporariamente
     */
    private function enviarResposta(Controlador $controle, Prazo $prazo) {
    	
		$oUsuario = $controle->usuario;
		$id_unidade_usuario_resposta = $controle->usuario->ID_UNIDADE;
		
		$usuario = $oUsuario->ID;
		$unidade = $oUsuario->ID_UNIDADE_ORIGINAL;
		$prazo->prazo->id_usuario_resposta = $usuario;
		$prazo->prazo->fg_status = 'RP';

		$stmt = $controle->getConnection()->connection->prepare("
                    UPDATE TB_CONTROLE_PRAZOS 
                    SET ID_USUARIO_RESPOSTA = :usu_resp, 
                        DT_RESPOSTA = CURRENT_TIMESTAMP(0), 
                        FG_STATUS = :status,
                        ID_UNIDADE_USUARIO_RESPOSTA = :unid_resp
                    WHERE SQ_PRAZO = :prazo 
                        AND (ID_USUARIO_DESTINO = :usu_dest OR ID_UNID_DESTINO = :unid_dest)
                        AND FG_STATUS = 'AR'
		");

		$stmt->bindParam('usu_resp', $prazo->prazo->id_usuario_resposta, PDO::PARAM_INT);
		$stmt->bindParam('status', $prazo->prazo->fg_status, PDO::PARAM_STR);
		$stmt->bindParam('unid_resp', $id_unidade_usuario_resposta);
		$stmt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
		$stmt->bindParam('usu_dest', $prazo->prazo->id_usuario_resposta, PDO::PARAM_INT);
		$stmt->bindParam('unid_dest', $unidade, PDO::PARAM_INT);
		$stmt->execute();
		
		new Log('TB_CONTROLE_PRAZOS', $prazo->prazo->sq_prazo, Zend_Auth::getInstance()->getIdentity()->ID, 'responder');
    }

    private function salvarResposta(Connection $cnn, Prazo $prazo) {
    	
    	if (!isset($prazo->prazo->nu_proc_dig_res) ||
    			(strtolower($prazo->prazo->nu_proc_dig_res) == 'null')) {
    		$prazo->prazo->nu_proc_dig_res = NULL;
    	}
    	
    	$stmt = $cnn->connection->prepare("
                    UPDATE TB_CONTROLE_PRAZOS
                    SET NU_PROC_DIG_RES = :digital,
                        TX_RESPOSTA = :resposta
                    WHERE SQ_PRAZO = :prazo
                        AND FG_STATUS = 'AR'
            ");
    	
    	$stmt->bindParam('digital', $prazo->prazo->nu_proc_dig_res, PDO::PARAM_STR);
    	$stmt->bindParam('resposta', $prazo->prazo->tx_resposta, PDO::PARAM_STR);
    	$stmt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
    	$stmt->execute();
    	
    	if (is_null($prazo->prazo->ha_ppa)) {
    		$prazo->prazo->ha_ppa = false;
    	}
    	
    	if (is_null($prazo->prazo->sit_legis)) {
    		$prazo->prazo->sit_legis = 0;
    	}
    	 
    	$stmtExt = $cnn->connection->prepare("
				UPDATE sgdoc.ext__snas__tb_controle_prazos
					SET ha_vinculo = :happa,
						legislacao_situacao = :legsit,
						legislacao_descricao = :legdsc,
    					dt_minuta_resposta = current_date
					WHERE id = :prazo;
            ");
    	$stmtExt->bindParam('happa', $prazo->prazo->ha_ppa, PDO::PARAM_BOOL);
    	$stmtExt->bindParam('legsit', $prazo->prazo->sit_legis, PDO::PARAM_INT);
    	$stmtExt->bindParam('legdsc', $prazo->prazo->desc_legis, PDO::PARAM_STR);
    	$stmtExt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
    	$stmtExt->execute();
    	
    	if ($prazo->prazo->ha_ppa == 'false') {
    		//Exclui as metas se o usuário informar que não há vinculo com o PPA
    		$sql = 'update snas.tb_prazo_vinculo_ppa set st_ativo = 0 where id_prazo = :prazo;';
    		$stmtDel = $cnn->connection->prepare($sql);
    		$stmtDel->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
    		$stmtDel->execute();
    	}
    	
    	new Log('TB_CONTROLE_PRAZOS', $prazo->prazo->sq_prazo, Zend_Auth::getInstance()->getIdentity()->ID, 'salvar resposta');
    	 
    }
    
    private function salvarMinuta(Connection $cnn, $sqPrazo) {
    	
    	$stmtExt = $cnn->connection->prepare("
				UPDATE sgdoc.ext__snas__tb_controle_prazos
				SET dt_minuta_resposta = current_date
				WHERE id = :prazo;
            ");
    	$stmtExt->bindParam('prazo', $sqPrazo, PDO::PARAM_INT);
    	$stmtExt->execute();
    	
    }
    
    public static function salvarRespostaPrazo(Prazo $prazo) {
    	try {
    
    		Controlador::getInstance()->getConnection()->connection->beginTransaction();
    
    		self::salvarResposta(Controlador::getInstance()->getConnection(), $prazo);
    
    		Controlador::getInstance()->getConnection()->connection->commit();
    
    		return new Output(array('success' => 'true', 'message' => 'Resposta salva com sucesso!'));
    	} catch (Exception $e) {
    		Controlador::getInstance()->getConnection()->connection->rollBack();
    		return new Output(array('success' => 'false', 'error' => $e->getMessage()));
    	}
    }
    
	public static function salvarPpaResposta(Prazo $prazo) {
		try {
        
			Controlador::getInstance()->getConnection()->connection->beginTransaction();

			$sql = 'insert into snas.tb_prazo_vinculo_ppa(id_prazo, codigo_orgao, codigo_programa, codigo_objetivo, codigo_meta, exercicio)
					select :prazo, :orgao, m."codigoPrograma", m."codigoObjetivo", m."codigoMeta"::text, m.exercicio
					from snas.tb_siop_metas m left join snas.tb_prazo_vinculo_ppa v on 
						(v.id_prazo = :prazo and v.codigo_orgao = :orgao and v.codigo_programa=m."codigoPrograma" 
                        and v.codigo_objetivo=m."codigoObjetivo" and v.codigo_meta=m."codigoMeta"::text and v.exercicio=m.exercicio)
					where v.id is null and m.exercicio = :exercicio and m."identificadorUnico" = :meta;';
				
       		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
        
        	$stmt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
        	$stmt->bindParam('orgao', $prazo->prazo->id_unidade, PDO::PARAM_STR);
        	$stmt->bindParam('exercicio', $prazo->prazo->ano_exercicio, PDO::PARAM_INT);

        	$arrMetas = split(',', trim($prazo->prazo->metas, ','));
        	foreach ($arrMetas as $meta) {
	        	$stmt->bindParam('meta', $meta, PDO::PARAM_STR);
	       		$stmt->execute();
        	}
        	
        	self::salvarMinuta(Controlador::getInstance()->getConnection(), $prazo->prazo->sq_prazo);
        	
			Controlador::getInstance()->getConnection()->connection->commit();
        
			return new Output(array('success' => 'true', 'message' => 'Metas selecionadas com sucesso!'));
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
    }

    public static function excluirPpaResposta($idVinculo = false) {
    	try {
    		
    		if (!$idVinculo) {
    			throw new Exception('Informe um vínculo.');
    		}
    		
    		Controlador::getInstance()->getConnection()->connection->beginTransaction();
    
    		$sql = 'update snas.tb_prazo_vinculo_ppa set st_ativo = 0 where id = ?;';
    
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    
    		$stmt->bindParam(1, $idVinculo, PDO::PARAM_INT);
    		
   			$stmt->execute();
    		 
    		Controlador::getInstance()->getConnection()->connection->commit();
    
    		return new Output(array('success' => 'true', 'message' => 'Metas excluída com sucesso!'));
    	} catch (Exception $e) {
    		Controlador::getInstance()->getConnection()->connection->rollBack();
    		return new Output(array('success' => 'false', 'error' => $e->getMessage()));
    	}
    }

    public static function incluirAnexoResposta(UploaderPdfResposta $upload) {
    	try {
    
    		Controlador::getInstance()->getConnection()->connection->beginTransaction();
    
    		$sql = 'INSERT INTO snas.tb_prazo_anexos(id_prazo, nome_arquivo_sistema, nome_original, dt_upload, id_pessoa)
    				VALUES (:prazo, :arquivo, :nome, CURRENT_TIMESTAMP, :usuario);';
    
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    
    		$arquivo = str_replace(__CAM_UPLOAD__, '', $upload->uploadPath) . $upload->img_name . '.pdf';
    		
    		$stmt->bindParam('prazo', $upload->idPrazo, PDO::PARAM_INT);
    		$stmt->bindParam('arquivo', $arquivo, PDO::PARAM_STR);
    		$stmt->bindParam('nome', $upload->originalName, PDO::PARAM_STR);
    		$stmt->bindParam('usuario', Controlador::getInstance()->usuario->ID, PDO::PARAM_INT);
    		
    		$stmt->execute();
    		
    		self::salvarMinuta(Controlador::getInstance()->getConnection(), $upload->idPrazo);
    		
    		Controlador::getInstance()->getConnection()->connection->commit();
    
    		return new Output(array('success' => 'true', 'message' => 'Arquivo salvo com sucesso!'));
    	} catch (Exception $e) {
    		Controlador::getInstance()->getConnection()->connection->rollBack();
    		return new Output(array('success' => 'false', 'error' => $e->getMessage()));
    	}
    }
    
    public static function excluirAnexoResposta($idAnexo = false) {
    	try {
    		
    		if (!$idAnexo) {
    			throw new Exception('Informe um anexo.');
    		}
    		
    		Controlador::getInstance()->getConnection()->connection->beginTransaction();
    
    		$sql = 'update snas.tb_prazo_anexos set st_ativo = 0 where id = ?;';
    
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    
    		$stmt->bindParam(1, $idAnexo, PDO::PARAM_INT);
    		
   			$stmt->execute();
   			
    		Controlador::getInstance()->getConnection()->connection->commit();
    
    		return new Output(array('success' => 'true', 'message' => 'Metas selecionadas com sucesso!'));
    	} catch (Exception $e) {
    		Controlador::getInstance()->getConnection()->connection->rollBack();
    		return new Output(array('success' => 'false', 'error' => $e->getMessage()));
    	}
    }
    
    public static function listarObjetivosMetasPpa($idPrazo = false) {
    	try {
    
    		if (!$idPrazo) {
    			throw new Exception('Informe um prazo.');
    		}
    
    		$sql = 'select v.id, v.exercicio,
    					(v.codigo_programa || \' - \' || p.titulo) as programa,
  						(v.codigo_objetivo || \' - \' || o.enunciado) as objetivo,
  						(v.codigo_meta || \' - \' || m.descricao) as meta
					from snas.tb_prazo_vinculo_ppa v
					  inner join snas.tb_siop_programas p on (p."codigoPrograma"=v.codigo_programa and p.exercicio=v.exercicio)
					  inner join snas.tb_siop_objetivos o on (o."codigoObjetivo"=v.codigo_objetivo and o.exercicio=v.exercicio)
					  inner join snas.tb_siop_metas m on (m."codigoMeta"::text=v.codigo_meta and m.exercicio=v.exercicio)
					where v.id_prazo = ? and v.st_ativo = 1
					order by v.codigo_programa, v.codigo_objetivo, v.codigo_meta, v.exercicio;';
    
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    		$stmt->bindParam(1, $idPrazo, PDO::PARAM_INT);
    		$stmt->execute();
    
    		$out = $stmt->fetchAll(PDO::FETCH_ASSOC);

    		if (!empty($out)) {
    			return $out;
    		}
    
    		return false;
    	} catch (PDOException $e) {
    		throw new Exception($e);
    	}
    }
    
    public static function listarArquivosAnexos($idPrazo = false) {
    	try {
    
    		if (!$idPrazo) {
    			throw new Exception('Informe um prazo.');
    		}
    
    		$sql = "
    			SELECT 
    				tpa.id as id
    				, tpa.id_prazo as id_prazo
    				, tpa.nome_arquivo_sistema as nome_arquivo_sistema
    				, tpa.nome_original as nome_original
    				, tpa.st_ativo as st_ativo
    				, TO_CHAR(tpa.dt_upload, 'DD/MM/YYYY') as dt_upload
    				, tpa.id_pessoa as id_pessoa
    				, tu.nome as nome_pessoa
  				FROM 
    				snas.tb_prazo_anexos tpa
    				, sgdoc.tb_usuarios tu
    			WHERE 
    				tpa.id_prazo = :prazo
    				and tu.id = tpa.id_pessoa
    			ORDER BY tpa.nome_original;
    		";
    
    		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    		$stmt->bindValue(':prazo', $idPrazo, PDO::PARAM_INT);
    		$stmt->execute();
    
    		$out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    		if (!empty($out)) {
    			return $out;
    		}
    
    		return false;
    	} catch (PDOException $e) {
    		throw new Exception($e);
    	}
    }
    
}
