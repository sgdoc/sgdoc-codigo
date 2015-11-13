<?php

use Respect\Validation\Rules\Length;
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

	private static $situacaoLegislacao = array('0' => 'Não requer ajuste legal/normativa/procedimentos',
											   '1' => 'Requer criação legal/normativa/procedimentos',
											   '2' => 'Requer alteração legal/normativa/procedimentos',
											   '3' => 'Requer votação legal/normativa/procedimentos',
											   '4' => 'Situação legal/normativa/procedimentos atendida'
											  );
	
	
	public static function getSituacoesLegislacao() {
		return self::$situacaoLegislacao;
	}
	
	public static function getSituacaoLegislacao($idSituacaoLegislacao) {
		if (array_key_exists($idSituacaoLegislacao, self::$situacaoLegislacao)) {
			return self::$situacaoLegislacao[$idSituacaoLegislacao];
		}
		return '';
	}
	
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
	 * Retorna os dados do prazo e da extensão snas
	 * @param string $prazo
	 * @throws Exception
	 * @return multitype:|boolean
	 */
	public static function getPrazoExtensao($prazo = false) {
		try {
			 
			if (!$prazo) {
				throw new Exception($e);
			}
	
			$sql = '
				select 
					p.sq_prazo, 
					p.nu_proc_dig_ref, 
					p.nu_proc_dig_res, 
					p.id_usuario_destino, 
					p.id_usuario_origem, 
					p.id_usuario_resposta, 
					p.id_unid_origem, 
					p.id_unid_destino, 
					to_char(p.dt_prazo::timestamp with time zone, \'dd/mm/yyyy\'::text) AS dt_prazo,
					p.dt_resposta, 
					p.fg_status, 
					p.tx_resposta, 
					p.tx_solicitacao, 
					p.id_unidade_usuario_resposta,
					e.id, 
					e.nu_proc_dig_ref_pai, 
					coalesce(e.ha_vinculo, false) as ha_vinculo,
					coalesce(e.legislacao_situacao, 0) as legislacao_situacao,
					e.legislacao_descricao, 
					e.dt_minuta_resposta, 
					e.id_prazo_pai,
					d.assunto, 
					d.assunto_complementar
				from sgdoc.tb_controle_prazos p
				  left join sgdoc.ext__snas__tb_controle_prazos e on (e.id=p.sq_prazo)
				  left join sgdoc.tb_documentos_cadastro d ON d.digital::text = p.nu_proc_dig_ref::text
				where p.sq_prazo = :id;';
			
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam('id', $prazo, PDO::PARAM_INT);
			$stmt->execute();
	
			$out = $stmt->fetch(PDO::FETCH_ASSOC);
	
			if (!empty($out)) {
				$out = array_change_key_case(($out), CASE_LOWER);
				$out['legislacao_situacao_descricao'] = self::$situacaoLegislacao[$out['legislacao_situacao']];
				return $out;
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
				return self::getPrazoExtensao($out) ;
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
	
			$sql = "SELECT 
						cp.sq_prazo, 
						trim(ext.nu_proc_dig_ref_pai) AS digital_pai, 
						cp.nu_proc_dig_ref AS nu_ref, 
  						COALESCE(pro_int.interessado, doc.interessado) AS interessado,
  						uso.nome AS nm_usuario_origem, 
						uno.nome AS nm_unidade_origem,
						usr.nome AS nm_usuario_resposta, 
						unr.nome AS nm_unidade_resposta,
  						to_char(cp.dt_prazo::timestamp with time zone, 'dd/mm/yyyy'::text) AS dt_prazo,
						doc.tipo, 
						cp.tx_solicitacao, 
						doc.assunto, 
						doc.assunto_complementar,
						cp.fg_status, 
						cp.tx_resposta, 
						coalesce(ext.ha_vinculo, false) as ha_vinculo,
						coalesce(ext.legislacao_situacao, 0) as legislacao_situacao, 
						ext.legislacao_descricao
					FROM sgdoc.tb_controle_prazos cp
					  	LEFT JOIN sgdoc.ext__snas__tb_controle_prazos ext ON ext.id = cp.sq_prazo
					  	LEFT JOIN sgdoc.tb_unidades uno ON uno.id = cp.id_unid_origem
					  	LEFT JOIN sgdoc.tb_usuarios uso ON uso.id = cp.id_usuario_origem
					  	LEFT JOIN sgdoc.tb_unidades unr ON unr.id = cp.id_unidade_usuario_resposta
					  	LEFT JOIN sgdoc.tb_usuarios usr ON usr.id = cp.id_usuario_resposta
						LEFT JOIN sgdoc.tb_documentos_cadastro doc ON doc.digital::text = cp.nu_proc_dig_ref::text
					  	LEFT JOIN (sgdoc.tb_processos_cadastro pro_cad JOIN sgdoc.tb_processos_interessados pro_int ON pro_int.id = pro_cad.interessado)
							ON pro_cad.numero_processo::text = cp.nu_proc_dig_ref::text
					WHERE cp.sq_prazo = ?;";
			
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(1, $seqPrazo, PDO::PARAM_INT);
			$stmt->execute();
	
			$out = $stmt->fetch(PDO::FETCH_ASSOC);
	
			if (!empty($out)) {
				$out = array_change_key_case(($out), CASE_LOWER);
				
				//BUSCA OS PRAZOS FILHOS
				$out['prazos_filhos'] = null;
				$sql = "SELECT e.id, replace(ps.tx_trilha_hierarq, 'PR/', '') as sigla,
						  to_char(p.dt_prazo::timestamp with time zone, 'dd/mm/yyyy'::text) AS dt_prazo
						FROM sgdoc.ext__snas__tb_controle_prazos e
						  inner join sgdoc.tb_controle_prazos p on (e.id=p.sq_prazo)
						  inner join sgdoc.tb_unidades u on (p.id_unidade_usuario_resposta=u.id)
						  inner join sgdoc.tb_pessoa_siorg ps on (u.co_siorg = ps.co_siorg)
						where e.id_prazo_pai = ? and p.dt_resposta is not null;";
				$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
				$stmt->bindParam(1, $seqPrazo, PDO::PARAM_INT);
				$stmt->execute();
				$outFilhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (!empty($outFilhos)) {
					$out['prazos_filhos'] = json_encode($outFilhos);
				}
				
				$out['legislacao_situacao_descricao'] = self::$situacaoLegislacao[$out['legislacao_situacao']];
   				return $out;
			}
	
			return false;
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	}
	
	public static function salvarPrazo(Prazo $prazo) {
		try {
			Controlador::getInstance()->getConnection()->connection->beginTransaction();
			
			self::inserirPrazo($prazo);
			
			Controlador::getInstance()->getConnection()->connection->commit();
			
			return new Output(array('success' => 'true', 'message' => 'Prazo cadastrado com sucesso!'));
			
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}
	
	private static function inserirPrazo(Prazo $prazo) {

			if (!isset($prazo->prazo->id_usuario_destino) || $prazo->prazo->id_usuario_destino == '') {
				$prazo->prazo->id_usuario_destino = NULL;
			}

			$prazo->id_unid_origem = isset($prazo->prazo->id_unid_origem) ? $prazo->prazo->id_unid_origem : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
			
			$prazo->id_usuario_origem = Controlador::getInstance()->usuario->ID;
			
			$dt_prazo = Util::formatDate($prazo->prazo->dt_prazo);
			
			$pai =  strlen($prazo->prazo->nu_proc_dig_ref_pai) > 0 ? $prazo->prazo->nu_proc_dig_ref_pai : null;
			
			$idPrazoPai = null;
			if (isset($prazo->prazo->id_prazo_pai)) {
				$idPrazoPai = ($prazo->prazo->id_prazo_pai > 0) ? $prazo->prazo->id_prazo_pai : null;
			}
			
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

			$sttt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO EXT__SNAS__TB_CONTROLE_PRAZOS (ID,NU_PROC_DIG_REF_PAI, ID_PRAZO_PAI) VALUES (:id, :dig_ref_pai, :id_pai);");
			$sttt->bindParam(id, $lastIdPrazo, PDO::PARAM_INT);
			$sttt->bindParam(dig_ref_pai, $pai, PDO::PARAM_STR);
			$sttt->bindParam(id_pai, $idPrazoPai, PDO::PARAM_INT);
			$sttt->execute();

			new Log('TB_CONTROLE_PRAZOS', $lastIdPrazo, Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');
	}

	/**
	 * Cria múltiplos prazos para uma unidade de destino
	 * @param Prazo $prazo
	 */
	public static function encaminharPrazos(Prazo $prazos) {
		
		try {
			Controlador::getInstance()->getConnection()->connection->beginTransaction();
		
			$arrId = explode(',', trim($prazos->prazo->prazos, ','));
			if (!array_walk($arrId, 'is_numeric')) {
				throw new Exception('Prazos inválidos.');
			}
			
			$idUnidDestino = $prazos->prazo->id_unid_destino;
			$idUsuarioDestino = null;
			if (isset($prazos->prazo->id_usuario_destino) && $prazos->prazo->id_usuario_destino != '') {
				$idUsuarioDestino = $prazos->prazo->id_usuario_destino;
			}
			
			$dataPrazo = $prazos->prazo->dt_prazo;
			
			$txtComp = '';
			if (isset($prazos->prazo->tx_solicitacao) && $prazos->prazo->tx_solicitacao != '') {
				$txtComp = "\n\n" . trim($prazos->prazo->tx_solicitacao);
			}
			
			foreach ($arrId as $idPrazoPai) {
				$prazo = self::getPrazoExtensao($idPrazoPai);
				if (!$prazo) {
					throw new Exception('Prazo não localizado.');
				}
				$novoPrazo = new Prazo();
				$novoPrazo->nu_proc_dig_ref = $prazo['nu_proc_dig_ref'];
				$novoPrazo->id_usuario_origem = Controlador::getInstance()->usuario->ID;
				$novoPrazo->id_usuario_destino = $idUsuarioDestino;
				$novoPrazo->id_unid_origem = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
				$novoPrazo->id_unid_destino = $idUnidDestino;
				$novoPrazo->dt_prazo = $dataPrazo;
				$novoPrazo->tx_solicitacao = $prazo['tx_solicitacao'] . $txtComp;
				$novoPrazo->nu_proc_dig_ref_pai = $prazo['nu_proc_dig_ref_pai'];
				$novoPrazo->id_prazo_pai = $idPrazoPai;
				
				self::inserirPrazo($novoPrazo);
			}
		
			Controlador::getInstance()->getConnection()->connection->commit();
		
			return new Output(array('success' => 'true', 'message' => 'Prazos encaminhados com sucesso!'));
		
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
		 
	}
	
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
		
			self::salvarResposta($prazo);
			
			self::enviarResposta($prazo);
		
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
	private function enviarResposta(Prazo $prazo) {
		
		$oUsuario = Controlador::getInstance()->usuario;
		$id_unidade_usuario_resposta = Controlador::getInstance()->usuario->ID_UNIDADE;
		
		$usuario = $oUsuario->ID;
		$unidade = $oUsuario->ID_UNIDADE_ORIGINAL;
		$prazo->prazo->id_usuario_resposta = $usuario;
		$prazo->prazo->fg_status = 'RP';

		$stmt = Controlador::getInstance()->getConnection()->connection->prepare("
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

	private function salvarResposta(Prazo $prazo) {
		
		if (!isset($prazo->prazo->nu_proc_dig_res) ||
				(strtolower($prazo->prazo->nu_proc_dig_res) == 'null')) {
			$prazo->prazo->nu_proc_dig_res = NULL;
		}
		
		$stmt = Controlador::getInstance()->getConnection()->connection->prepare("
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
		
		if (is_null($prazo->prazo->ha_vinculo)) {
			$prazo->prazo->ha_vinculo = false;
		} else {
			if (!is_bool($prazo->prazo->ha_vinculo)) {
				$prazo->prazo->ha_vinculo = ($prazo->prazo->ha_vinculo == 'true');
			}
		}
		
		if (is_null($prazo->prazo->legislacao_situacao)) {
			$prazo->prazo->legislacao_situacao = 0;
		}
		
		if ($prazo->prazo->legislacao_situacao == 0) {
			$prazo->prazo->legislacao_descricao = '';
		}
		
		$stmtExt = Controlador::getInstance()->getConnection()->connection->prepare("
				UPDATE sgdoc.ext__snas__tb_controle_prazos
					SET ha_vinculo = :havinc,
						legislacao_situacao = :legsit,
						legislacao_descricao = :legdsc,
						dt_minuta_resposta = current_date
					WHERE id = :prazo;
			");
		$stmtExt->bindParam('havinc', $prazo->prazo->ha_vinculo, PDO::PARAM_BOOL);
		$stmtExt->bindParam('legsit', $prazo->prazo->legislacao_situacao, PDO::PARAM_INT);
		$stmtExt->bindParam('legdsc', $prazo->prazo->legislacao_descricao, PDO::PARAM_STR);
		$stmtExt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
		$stmtExt->execute();
		
		if ($prazo->prazo->ha_vinculo == false) {
			//Exclui as metas e ações se o usuário informar que não há vinculo com o PPA
			$sql = 'update snas.tb_prazo_vinculo_ppa set st_ativo = 0 where id_prazo = :prazo;';
			$stmtDel = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmtDel->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
			$stmtDel->execute();
			
			$sql = 'update snas.tb_prazo_vinculo_ppa_acoes set st_ativo = 0 where id_prazo = :prazo;';
			$stmtDel = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmtDel->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
			$stmtDel->execute();
		}
		
		new Log('TB_CONTROLE_PRAZOS', $prazo->prazo->sq_prazo, Zend_Auth::getInstance()->getIdentity()->ID, 'salvar resposta');
		 
	}
	
	private function salvarMinuta($sqPrazo) {
		
		$stmtExt = Controlador::getInstance()->getConnection()->connection->prepare("
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
	
			self::salvarResposta($prazo);
	
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
			
			if (trim($prazo->prazo->metas, ',') != '') {
				$sql = 'insert into snas.tb_prazo_vinculo_ppa(id_prazo, codigo_orgao, codigo_programa, codigo_objetivo, codigo_meta, exercicio)
						select :prazo, o."codigoOrgao", m."codigoPrograma", m."codigoObjetivo", m."codigoMeta"::text, m.exercicio
						from snas.tb_siop_metas m
						left join snas.tb_prazo_vinculo_ppa v on 
							(v.id_prazo = :prazo and v.st_ativo = 1 
							and v.codigo_programa=m."codigoPrograma" 
							and v.codigo_objetivo=m."codigoObjetivo"
							and v.codigo_meta=m."codigoMeta"::text
							and v.exercicio=m.exercicio)
						inner join snas.tb_siop_objetivos o on
							(o."codigoPrograma"=m."codigoPrograma" and o."codigoObjetivo"=m."codigoObjetivo" and o.exercicio=m.exercicio)
						where v.id is null and m.exercicio = :exercicio and m."identificadorUnico" = :meta;';
					
		   		$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			
				$stmt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
				$stmt->bindParam('exercicio', $prazo->prazo->ano_exercicio, PDO::PARAM_INT);
	
				$arrMetas = explode(',', trim($prazo->prazo->metas, ','));
				foreach ($arrMetas as $meta) {
					$stmt->bindParam('meta', $meta, PDO::PARAM_INT);
			   		$stmt->execute();
				}
			}

			if (trim($prazo->prazo->acoes_po, ',') != '') {
				$sql = 'insert into snas.tb_prazo_vinculo_ppa_acoes(id_prazo, codigo_orgao, codigo_programa, codigo_acao, exercicio,
						  id_unico_acao, id_unico_localizador, codigo_localizador, id_unico_plano_orcamentario, codigo_plano_orcamentario)
						select :prazo, a."codigoOrgao", a."codigoPrograma", a."codigoAcao", a.exercicio,
						  a."identificadorUnico", l."identificadorUnico", l."codigoLocalizador", p."identificadorUnico", p."planoOrcamentario"
						from snas.tb_siop_acoes a
						  inner join snas.tb_siop_localizadores l on
							(l."identificadorUnicoAcao"=a."identificadorUnico" and l.exercicio=a.exercicio and l."identificadorUnico" = :local)
						  inner join snas.tb_siop_planos_orcamentarios p on
							(p."identificadorUnicoAcao"=a."identificadorUnico" and l.exercicio=a.exercicio and p."identificadorUnico" = :po)
						  left join snas.tb_prazo_vinculo_ppa_acoes v on
							(v.id_prazo = :prazo and v.st_ativo = 1
							and v.codigo_orgao=a."codigoOrgao" and v.codigo_programa=a."codigoPrograma" and v.codigo_acao=a."codigoAcao" and v.exercicio=a.exercicio
							and v.codigo_localizador=l."codigoLocalizador" and v.codigo_plano_orcamentario=p."planoOrcamentario")
						where v.id is null and a.exercicio = :exercicio and a."identificadorUnico" = :acao;';
				 
				$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
				
				$stmt->bindParam('prazo', $prazo->prazo->sq_prazo, PDO::PARAM_INT);
				$stmt->bindParam('exercicio', $prazo->prazo->ano_exercicio, PDO::PARAM_INT);
				
				$arrAcoesPO = explode(',', trim($prazo->prazo->acoes_po, ','));
				foreach ($arrAcoesPO as $acao_po) {
					$arrIds = explode('|', $acao_po);
					$stmt->bindParam('acao', $arrIds[0], PDO::PARAM_INT);
					$stmt->bindParam('local', $arrIds[1], PDO::PARAM_INT);
					$stmt->bindParam('po', $arrIds[2], PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			self::salvarMinuta($prazo->prazo->sq_prazo);
			
			Controlador::getInstance()->getConnection()->connection->commit();
		
			return new Output(array('success' => 'true', 'message' => 'Metas/Ações selecionadas com sucesso!'));
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}

	public static function excluirPpaResposta($tipo = false, $idVinculo = false) {
		try {
			
			if (!$tipo || (($tipo != 'meta') && ($tipo != 'acao'))) {
				throw new Exception('Informe se é a exclusão de uma Meta ou Ação.');
			}
			
			if (!$idVinculo) {
				throw new Exception('Informe um vínculo.');
			}
			
			Controlador::getInstance()->getConnection()->connection->beginTransaction();
	
			$msg = '';
			
			if ($tipo == 'meta') {
				$sql = 'update snas.tb_prazo_vinculo_ppa set st_ativo = 0 where id = ?;';
				$msg = 'Meta excluída com sucesso!';
			} else {
				$sql = 'update snas.tb_prazo_vinculo_ppa_acoes set st_ativo = 0 where id = ?;';
				$msg = 'Ação excluída com sucesso!';
			}
			
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
	
			$stmt->bindParam(1, $idVinculo, PDO::PARAM_INT);
			
   			$stmt->execute();
			 
			Controlador::getInstance()->getConnection()->connection->commit();
	
			return new Output(array('success' => 'true', 'message' => $msg));
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
			
			self::salvarMinuta($upload->idPrazo);
			
			Controlador::getInstance()->getConnection()->connection->commit();
	
			return new Output(array('success' => 'true', 'message' => "Arquivo salvo com sucesso!"));
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}
	
	public static function excluirAnexoResposta($idAnexo = false) {
		try {
			
			Controlador::getInstance()->getConnection()->connection->beginTransaction();
			
			self::excluirArquivoAnexo($idAnexo);
			
			Controlador::getInstance()->getConnection()->connection->commit();
	
			return new Output(array('success' => 'true', 'message' => 'Anexo excluído com sucesso!'));
		
		} catch (Exception $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}
	
	private function excluirArquivoAnexo($idAnexo = false) {
		
		if (!$idAnexo) {
			throw new Exception('Informe um anexo.');
		}
		
		$sqlArquivo = 'select nome_arquivo_sistema, count(*) as total from snas.tb_prazo_anexos where nome_arquivo_sistema in (select nome_arquivo_sistema from snas.tb_prazo_anexos where id = ?) group by nome_arquivo_sistema;';
		$stmtArquivo = Controlador::getInstance()->getConnection()->connection->prepare($sqlArquivo);
		$stmtArquivo->bindParam(1, $idAnexo, PDO::PARAM_INT);
		$stmtArquivo->execute();
		$out = $stmtArquivo->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($out) && $out[0]['TOTAL'] == 1) {
			$arquivo = __CAM_UPLOAD__ . $out[0]['NOME_ARQUIVO_SISTEMA'];
			unlink( $arquivo );
		}
		
		$sqlDelete = 'delete from snas.tb_prazo_anexos where id = ?;';
		$stmtDelete = Controlador::getInstance()->getConnection()->connection->prepare($sqlDelete);
		$stmtDelete->bindParam(1, $idAnexo, PDO::PARAM_INT);
		$stmtDelete->execute();
		
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
					  inner join snas.tb_siop_programas p on 
						(p."codigoPrograma"=v.codigo_programa and p."codigoOrgao"=v.codigo_orgao and p.exercicio=v.exercicio)
					  inner join snas.tb_siop_objetivos o on 
						(o."codigoPrograma"=v.codigo_programa and o."codigoOrgao"=v.codigo_orgao and o."codigoObjetivo"=v.codigo_objetivo and o.exercicio=v.exercicio)
					  inner join snas.tb_siop_metas m on 
						(m."codigoObjetivo"=v.codigo_objetivo and m."codigoMeta"::text=v.codigo_meta and m.exercicio=v.exercicio)
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

	public static function listarAcoesPpa($idPrazo = false) {
		try {
	
			if (!$idPrazo) {
				throw new Exception('Informe um prazo.');
			}
	
			$sql = 'select v.id, v.exercicio,
					  (v.codigo_programa || \' - \' || p.titulo) as programa,
					  (v.codigo_acao || \' - \' || a.titulo) as acao,
					  (v.codigo_localizador || \' - \' || l.descricao) as localizador,
					  (v.codigo_plano_orcamentario || \' - \' || po.titulo) as p_o,
					  trim(to_char(e."dotacaoAtual", \'9G999G999G990D99\')) as val_dotacao_atual,
					  trim(to_char(e.empenhado, \'9G999G999G990D99\')) as val_empenhado,
					  trim(to_char(e.liquidado, \'9G999G999G990D99\')) as val_liquidado,
					  trim(to_char(e."percentualLiquidadoEmpenhado", \'9G990D99\')) as per_liq_emp
					from snas.tb_prazo_vinculo_ppa_acoes v
					  inner join snas.tb_siop_orgaos o on 
						(o."codigoOrgao"::text=v.codigo_orgao and o.exercicio=v.exercicio and o."tipoOrgao" = \'U\')
					  inner join snas.tb_siop_programas p on
						(p."codigoOrgao"=o."codigoOrgaoPai" and p."codigoPrograma"=v.codigo_programa and p.exercicio=v.exercicio)
					  inner join snas.tb_siop_acoes a on
						(a."codigoOrgao"=v.codigo_orgao and a."codigoPrograma"=v.codigo_programa and a."codigoAcao"=v.codigo_acao and a.exercicio=v.exercicio)
					  inner join snas.tb_siop_localizadores l on
						(l."identificadorUnicoAcao"=v.id_unico_acao and l.exercicio=v.exercicio and l."identificadorUnico"=v.id_unico_localizador)
					  inner join snas.tb_siop_planos_orcamentarios po on
						(po."identificadorUnicoAcao"=v.id_unico_acao and po.exercicio=v.exercicio and po."identificadorUnico"=v.id_unico_plano_orcamentario)
					  inner join snas.tb_siop_exec_orcam_plano_orcam e on
						(e."codigoOrgao"=v.codigo_orgao and e."codigoPrograma"=v.codigo_programa and e."codigoAcao"=v.codigo_acao 
						and e."codigoLocalizador"=v.codigo_localizador and e."planoOrcamentario"=v.codigo_plano_orcamentario and e.exercicio=v.exercicio)
					  where v.id_prazo = ? and v.st_ativo = 1
					  order by v.codigo_programa, v.codigo_acao, v.exercicio;';
			
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
	
	/**
	 * Concatena os textos da resposta de um prazo filho com o prazo pai,
	 * incluíndo objetivos/metas e ações PPA/LOA, e arquivos anexos 
	 * @param string $idPrazoPai
	 * @param string $idPrazoFilho
	 * @throws Exception
	 * @return Output
	 */
	public static function concatenarResposta($idPrazoPai = false, $idPrazoFilho = false) {
		
		try {
		
			if (!$idPrazoPai) {
				throw new Exception('Informe o prazo PAI.');
			}
			
			if (!$idPrazoFilho) {
				throw new Exception('Informe o prazo FILHO.');
			}
			
			$prazoPai = self::getPrazoResposta($idPrazoPai);
			if (!$prazoPai) {
				throw new Exception('Prazo PAI não localizado.');
			}
			
			$prazoFilho = self::getPrazoResposta($idPrazoFilho);
			if (!$prazoFilho) {
				throw new Exception('Prazo FILHO não localizado.');
			}

			$respostaPrazo = new Prazo();
			$respostaPrazo->prazo->sq_prazo = $idPrazoPai;
			$respostaPrazo->prazo->nu_proc_dig_res = null;
			
			$respostaPrazo->prazo->tx_resposta = '';
			if (trim($prazoPai['tx_resposta']) != '') {
				$respostaPrazo->prazo->tx_resposta = trim($prazoPai['tx_resposta']) . "\n\n";
			}
			$respostaPrazo->prazo->tx_resposta .= trim($prazoFilho['tx_resposta']);
			
			$respostaPrazo->prazo->ha_vinculo = ($prazoPai['ha_vinculo'] == false) ? $prazoFilho['ha_vinculo'] : $prazoPai['ha_vinculo'];

			if ($prazoPai['legislacao_situacao'] == 0) {
				$respostaPrazo->prazo->legislacao_situacao = $prazoFilho['legislacao_situacao'];
				$respostaPrazo->prazo->legislacao_descricao = $prazoFilho['legislacao_descricao'];
			} else {
				if ($prazoFilho['legislacao_situacao'] > 0) {
					$respostaPrazo->prazo->legislacao_descricao = self::getSituacaoLegislacao($prazoPai['legislacao_situacao'])
																	. ":\n" . $prazoPai['legislacao_descricao'];
					$respostaPrazo->prazo->legislacao_situacao = $prazoFilho['legislacao_situacao'];
					$respostaPrazo->prazo->legislacao_descricao .= "\n\n" . self::getSituacaoLegislacao($prazoFilho['legislacao_situacao'])
																	. ":\n" . $prazoFilho['legislacao_descricao'];
				} else {
					$respostaPrazo->prazo->legislacao_situacao = $prazoPai['legislacao_situacao'];
					$respostaPrazo->prazo->legislacao_descricao = $prazoPai['legislacao_descricao'];
				}
			}

			Controlador::getInstance()->getConnection()->connection->beginTransaction();
			
			self::salvarResposta($respostaPrazo);
			
			//OBJETIVOS E METAS
			$sql = 'insert into snas.tb_prazo_vinculo_ppa (id_prazo, codigo_orgao, codigo_programa, codigo_objetivo, codigo_meta, exercicio)
					select :prazoPai, f.codigo_orgao, f.codigo_programa, f.codigo_objetivo, f.codigo_meta, f.exercicio
					from snas.tb_prazo_vinculo_ppa f
					  left join snas.tb_prazo_vinculo_ppa p on (
						p.id_prazo = :prazoPai and
						p.st_ativo = 1 and
						p.codigo_orgao=f.codigo_orgao and
						p.codigo_programa=f.codigo_programa and
						p.codigo_objetivo=f.codigo_objetivo and
						p.codigo_meta=f.codigo_meta and
						p.exercicio=f.exercicio
					  )
					where f.id_prazo = :prazoFilho and f.st_ativo = 1 and p.id is null;';
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam('prazoPai', $idPrazoPai, PDO::PARAM_INT);
			$stmt->bindParam('prazoFilho', $idPrazoFilho, PDO::PARAM_INT);
			$stmt->execute();
			
			//AÇÕES
			$sql = 'insert into snas.tb_prazo_vinculo_ppa_acoes (id_prazo, codigo_orgao, codigo_programa, codigo_acao, exercicio)
					select :prazoPai, f.codigo_orgao, f.codigo_programa, f.codigo_acao, f.exercicio
					  from snas.tb_prazo_vinculo_ppa_acoes f
						left join snas.tb_prazo_vinculo_ppa_acoes p on (
						  p.id_prazo = :prazoPai and
						  p.st_ativo = 1 and
						  p.codigo_orgao=f.codigo_orgao and
						  p.codigo_programa=f.codigo_programa and
						  p.codigo_acao=f.codigo_acao and
						  p.exercicio=f.exercicio
						)
					where f.id_prazo = :prazoFilho and f.st_ativo = 1 and p.id is null;';
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam('prazoPai', $idPrazoPai, PDO::PARAM_INT);
			$stmt->bindParam('prazoFilho', $idPrazoFilho, PDO::PARAM_INT);
			$stmt->execute();

			//ANEXOS
			$anexos = self::listarArquivosAnexos($idPrazoFilho);

			$raiz = '/' . ((string) Util::gerarRaiz($prazoPai['digital_pai'], __CAM_UPLOAD__)) .
					'/' . $prazoPai['digital_pai'] . '/pdf/';
			$dirDest = __CAM_UPLOAD__ . $raiz;
			if (!is_dir($dirDest)) {
				@mkdir($dirDest, 0777, true);
			}
			
			$sql = 'INSERT INTO snas.tb_prazo_anexos (id_prazo, nome_arquivo_sistema, nome_original, dt_upload, id_pessoa)
					VALUES (:prazoPai, :arquivo, :nome, :data, :usuario);';
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

			for ($i=0;$i<count($anexos);$i++) {
				$arqOrigem = __CAM_UPLOAD__ . trim($anexos[$i]['NOME_ARQUIVO_SISTEMA']);
				$arqDestino = $dirDest . substr(trim($anexos[$i]['NOME_ARQUIVO_SISTEMA']), -36);
				$nomeSistema = $raiz . substr(trim($anexos[$i]['NOME_ARQUIVO_SISTEMA']), -36);
				
				$stmt->bindParam('prazoPai', $idPrazoPai, PDO::PARAM_INT);
				$stmt->bindParam('arquivo', $nomeSistema, PDO::PARAM_STR);
				$stmt->bindParam('nome', $anexos[$i]['NOME_ORIGINAL'], PDO::PARAM_STR);
				$stmt->bindParam('data', Util::formatDate($anexos[$i]['DT_UPLOAD']), PDO::PARAM_STR);
				$stmt->bindParam('usuario', $anexos[$i]['ID_PESSOA'], PDO::PARAM_INT);
				$stmt->execute();
				
				if (!file_exists($arqDestino)) {
					if (file_exists($arqOrigem)) {
						if (!copy($arqOrigem, $arqDestino)) {
							throw new Exception("Erro ao copiar o arquivo anexo: {$anexos[$i]['NOME_ORIGINAL']}");
						}
					} else {
						throw new Exception("Não foi possível localizar o arquivo anexo: {$anexos[$i]['NOME_ORIGINAL']}");
					}
				}
			}
			
			Controlador::getInstance()->getConnection()->connection->commit();
			return new Output(array('success' => 'true', 'message' => 'Resposta concatenada com sucesso!'));
			
		} catch (PDOException $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}

	
	/**
	 * Exclui os dados da resposta do prazo informado,
	 * inclusive objetivos/metas e ações PPA/LOA, e arquivos anexos
	 * @param string $idPrazo
	 * @throws Exception
	 * @return Output
	 */
	public static function limparResposta($idPrazo = false) {
		 
		try {
			 
			if (!$idPrazo) {
				throw new Exception('Informe o prazo.');
			}
			
			$respostaPrazo = new Prazo();
			$respostaPrazo->prazo->sq_prazo = $idPrazo;
			$respostaPrazo->prazo->nu_proc_dig_res = null;
			$respostaPrazo->prazo->tx_resposta = '';
			//Este atributo setado como false, excluirá os vínculos com o PPA/LOA
			$respostaPrazo->prazo->ha_vinculo = false;
			$respostaPrazo->prazo->legislacao_situacao = 0;
			$respostaPrazo->prazo->legislacao_descricao = '';
			
			Controlador::getInstance()->getConnection()->connection->beginTransaction();
			
			//ANEXOS
			$anexos = self::listarArquivosAnexos($idPrazo);
			if (is_array($anexos)) {
				for ($i=0;$i<count($anexos);$i++) {
					self::excluirArquivoAnexo($anexos[$i]['ID']);
				}
			}
			
			self::salvarResposta($respostaPrazo);
			
			new Log('TB_CONTROLE_PRAZOS', $idPrazo, Zend_Auth::getInstance()->getIdentity()->ID, 'Limpar resposta');
			
			Controlador::getInstance()->getConnection()->connection->commit();
			
			return new Output(array('success' => 'true', 'message' => 'Os dados da resposta foram limpos com sucesso!'));
			
		} catch (PDOException $e) {
			Controlador::getInstance()->getConnection()->connection->rollBack();
			return new Output(array('success' => 'false', 'error' => $e->getMessage()));
		}
	}
}
