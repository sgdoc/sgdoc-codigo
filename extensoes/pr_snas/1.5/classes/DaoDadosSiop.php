<?php
class DaoDadosSiop {
	
	/**
	 * Lista os programas de um órgão (unidade), em um exercício (ano)
	 */
	public static function getProgramas($unidade = false, $ano = false) {
		try {
			
			if (!$unidade) {
				throw new Exception('Informe uma unidade (órgão).');
			}

			self::validarExercicio($ano);
			
			$sql = 'select distinct u.id as id_unidade, u.nome as nome_unidade, prg."codigoOrgao" as codigo_orgao_siop,
						prg."codigoPrograma" as cod_programa, prg.titulo as tit_programa, u_pai.id as id_unidade_pai
					from sgdoc.tb_unidades u
					  inner join sgdoc.tb_pessoa_siorg ps on (u.co_siorg = ps.co_siorg)
					  left join sgdoc.tb_pessoa_siorg ps_pai on (ps_pai.co_orgao = ps.co_orgao_pai)
					  left join sgdoc.tb_unidades u_pai on (u_pai.co_siorg = ps_pai.co_siorg)
					  left join snas.tb_siop_orgaos o on (o."orgaoSiorg" = (ps.co_orgao::integer)::text and o.exercicio = :ano and o."tipoOrgao" = \'O\')
					  left join snas.tb_siop_programas prg on (o."codigoOrgao"::text = prg."codigoOrgao" and prg.exercicio = :ano and prg."snExclusaoLogica" = false)
					where u.id = :unidade
					order by prg.titulo;';
			
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			
			$unidadeBusca = $unidade;
			
			do {
				$stmt->bindValue(':unidade', $unidadeBusca, PDO::PARAM_INT);
				$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
				$stmt->execute();
				
				$out = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
				$unidadeBusca = 0;
				
				if (!empty($out)) {
					if (!is_null($out[0]['COD_PROGRAMA'])) {
						if (is_null($out[(count($out)-1)]['COD_PROGRAMA'])) {
							array_pop($out);
						}
						return $out;
					}
					$unidadeBusca = is_null($out[0]['ID_UNIDADE_PAI']) ? 0 : $out[0]['ID_UNIDADE_PAI'];
				}
			} while ($unidadeBusca > 0);
	
			return false;
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	}
	
	public static function getProgramaVinculado($idVinculo = false) {
		try {
			
			if (!$idVinculo) {
				throw new Exception('Informe um vínculo.');
			}
	
			$sql = 'select v.id, v.exercicio, v.codigo_orgao as unidade, v.codigo_programa as cod_programa, p.titulo as tit_programa
					from snas.tb_prazo_vinculo_ppa v
					  inner join snas.tb_siop_programas p on (p."codigoPrograma"=v.codigo_programa and p."codigoOrgao"=v.codigo_orgao and p.exercicio=v.exercicio)
					where v.id = ? limit 1;';
						
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(1, $idVinculo, PDO::PARAM_INT);
			$stmt->execute();
	
			$out = $stmt->fetch(PDO::FETCH_ASSOC);

			if (!empty($out)) {
				return $out;
			}
	
			return false;
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	}
	
	/**
	 * Lista os objetivos e metas de um programa, de um órgão SIOP, em um exercício (ano).
	 * Opcionalmente, ao informar um id de um prazo, listará os id dos vínculos existentes
	 */
	public static function getObjetivosMetas($programa = false, $orgao = false, $ano = false, $prazo = false) {
		try {
				
			if (!$programa) {
				throw new Exception('Informe um programa.');
			}
			
			if (!$orgao) {
				throw new Exception('Informe um órgão SIOP.');
			}
			
			self::validarExercicio($ano);
				
			$sql = 'select o."codigoObjetivo" as cod_objetivo, o.enunciado as dsc_objetivo,
						m."identificadorUnico" as id_meta, m."codigoMeta" as cod_meta, m.descricao as dsc_meta';

			if ($prazo) {
				$sql .= ', v.id as vinculo';
			}
			
			$sql .= ' from snas.tb_siop_objetivos o 
  						left join snas.tb_siop_metas m on (m."codigoObjetivo"=o."codigoObjetivo" and m."codigoPrograma"=o."codigoPrograma" and m.exercicio=o.exercicio) ';
			if ($prazo) {
				$sql .= 'left join snas.tb_prazo_vinculo_ppa v on
						  (v.id_prazo = :prazo and v.st_ativo = 1 
							and m."codigoPrograma"=v.codigo_programa and m."codigoObjetivo"=v.codigo_objetivo 
							and m."codigoMeta"::text=v.codigo_meta and m.exercicio=v.exercicio) ';
			}
			$sql .= 'where o."codigoPrograma" = :programa and o."codigoOrgao" = :orgao and o.exercicio = :ano and o."snExclusaoLogica" = false
					order by o."codigoObjetivo", m."codigoMeta";';

			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(':programa', $programa, PDO::PARAM_STR);
			$stmt->bindParam(':orgao', $orgao, PDO::PARAM_STR);
			$stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
			if ($prazo) {
				$stmt->bindParam(':prazo', $prazo, PDO::PARAM_INT);
			}
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
	 * Lista as ações de um órgão (unidade) em um programa, de um órgão SIOP, em um exercício (ano)
	 * Opcionalmente, ao informar um id de um prazo, listará os id dos vínculos existentes
	 */
	public static function getAcoes($programa = false, $orgao = false, $ano = false, $prazo = false) {
		try {
				
			if (!$programa) {
				throw new Exception('Informe um programa.');
			}
			
			if (!$orgao) {
				throw new Exception('Informe um órgão SIOP.');
			}

			self::validarExercicio($ano);
				
			$sql = 'select a."codigoAcao" as cod_acao, a.titulo as tit_acao, a."identificadorUnico" as id_acao, 
						e."dotacaoAtual" as val_dotacao_atual, e.empenhado as val_empenhado,
						e.liquidado as val_liquidado, e."percentualLiquidadoEmpenhado" as per_liq_emp';
			
			if ($prazo) {
				$sql .= ', v.id as vinculo';
			}

			$sql .=	' from snas.tb_siop_orgaos o
					  inner join snas.tb_siop_acoes a on (a."codigoOrgao"=o."codigoOrgao"::text and a.exercicio=o.exercicio)
					  inner join snas.tb_siop_execucao_orcamentaria e on
						(e."codigoOrgao"=a."codigoOrgao" and e."codigoAcao"=a."codigoAcao" and e."codigoPrograma"=a."codigoPrograma" and e.exercicio=a.exercicio) ';
			
			if ($prazo) {
				$sql .= 'left join snas.tb_prazo_vinculo_ppa_acoes v on
						  (v.id_prazo = :prazo and v.st_ativo = 1
							and a."codigoOrgao"=v.codigo_orgao and a."codigoPrograma"=v.codigo_programa 
							and a."codigoAcao"=v.codigo_acao and a.exercicio=v.exercicio) ';
			}
				
			$sql .= 'where 
					  o."codigoOrgaoPai" = :orgao
					  and o."tipoOrgao" = \'U\'
					  and o.exercicio = :ano
					  and a."codigoPrograma" = :programa
					  and a."snExclusaoLogica" = false
					order by a."codigoAcao";';
				
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindValue(':orgao', $orgao, PDO::PARAM_STR);
			$stmt->bindValue(':programa', $programa, PDO::PARAM_STR);
			$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
			if ($prazo) {
				$stmt->bindParam(':prazo', $prazo, PDO::PARAM_INT);
			}
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

	public static function getObjetivoMetaVinculado($idVinculo = false) {
		try {
			 
			if (!$idVinculo) {
				throw new Exception('Informe um vínculo.');
			}
	
			$sql = 'select v.id, v.exercicio,
					  v.codigo_programa as cod_programa, p.titulo as tit_programa,
					  v.codigo_objetivo as cod_objetivo, o.enunciado as dsc_objetivo,
					  v.codigo_meta as cod_meta, m.descricao as dsc_meta
					from snas.tb_prazo_vinculo_ppa v
					  inner join snas.tb_siop_objetivos o on 
					    (o."codigoOrgao"=v.codigo_orgao and o."codigoPrograma"=v.codigo_programa and o."codigoObjetivo"=v.codigo_objetivo and o.exercicio=v.exercicio)
					  inner join snas.tb_siop_metas m on 
					    (m."codigoPrograma"=v.codigo_programa and m."codigoObjetivo"=v.codigo_objetivo and m."codigoMeta"::text=v.codigo_meta and m.exercicio=v.exercicio)
					  inner join snas.tb_siop_programas p on 
					    (p."codigoOrgao"=v.codigo_orgao and p."codigoPrograma"=v.codigo_programa and p.exercicio=v.exercicio)
					where v.id = ?;';
	
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(1, $idVinculo, PDO::PARAM_INT);
			$stmt->execute();
	
			$out = $stmt->fetch(PDO::FETCH_ASSOC);
	
			if (!empty($out)) {
				return $out;
			}
	
			return false;
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	
	}
	
	public static function getAcaoVinculado($idVinculo = false) {
		try {
			 
			if (!$idVinculo) {
				throw new Exception('Informe um vínculo.');
			}
	
			$sql = 'select v.id, v.exercicio,
					  v.codigo_programa as cod_programa, p.titulo as tit_programa,
					  v.codigo_acao as cod_acao, a.titulo as tit_acao,
					  trim(to_char(e."dotacaoAtual", \'9G999G999G990D99\')) as val_dotacao_atual,
					  trim(to_char(e.empenhado, \'9G999G999G990D99\')) as val_empenhado,
					  trim(to_char(e.liquidado, \'9G999G999G990D99\')) as val_liquidado,
					  trim(to_char(e."percentualLiquidadoEmpenhado", \'9G990D99\')) as per_liq_emp
					from snas.tb_prazo_vinculo_ppa_acoes v
					  inner join snas.tb_siop_orgaos o on 
					    (o."codigoOrgao"::text=v.codigo_orgao and o.exercicio=v.exercicio)
					  inner join snas.tb_siop_programas p on 
					    (p."codigoOrgao"=o."codigoOrgaoPai" and p."codigoPrograma"=v.codigo_programa and p.exercicio=v.exercicio)
					  inner join snas.tb_siop_acoes a on
					    (a."codigoOrgao"=v.codigo_orgao and a."codigoPrograma"=v.codigo_programa and a."codigoAcao"=v.codigo_acao and a.exercicio=v.exercicio)
					  inner join snas.tb_siop_execucao_orcamentaria e on
					    (e."codigoOrgao"=v.codigo_orgao and e."codigoPrograma"=v.codigo_programa and e."codigoAcao"=v.codigo_acao and e.exercicio=v.exercicio)
					where v.id = ?;';
	
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(1, $idVinculo, PDO::PARAM_INT);
			$stmt->execute();
	
			$out = $stmt->fetch(PDO::FETCH_ASSOC);
	
			if (!empty($out)) {
				return $out;
			}
	
			return false;
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	}
	
	/**
	 * Retorna o código de um orgão do SIOP, em um exercício (ano),
	 * a partir de uma unidade do SGDoc
	 * @param string $idUnidade
	 */
	public static function getOrgaoSiop($idUnidade = false, $ano = false) {

		try {
			if (!$idUnidade) {
				throw new Exception('Informe o ID de uma unidade do sistema SGDoc.');
			}
			
			self::validarExercicio($ano);
			
			$sql = 'select o."codigoOrgao" as cod_orgao
					from sgdoc.tb_unidades u
						inner join sgdoc.tb_pessoa_siorg p on (u.co_siorg = p.co_siorg)
						inner join snas.tb_siop_orgaos o on (o."orgaoSiorg" = (p.co_orgao::integer)::text)
					where u.id = :unidade  and o.exercicio = :ano and o."tipoOrgao" = \'O\';';
			
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindValue(':unidade', $idUnidade, PDO::PARAM_INT);
			$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
			$stmt->execute();
			
			$out = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if (!empty($out)) {
				return $out[0]['cod_orgao'];
			}
			
			return false;
			
		} catch (PDOException $e) {
			throw new Exception($e);
		}
	}
	
	private function validarExercicio($ano=false) {
		if (!$ano) {
			throw new Exception('Informe o exercício (ano).');
		}
		
		$anoInicial = Config::factory()->getParam('ws.siop.exercicio.inicial');
		if ($ano < $anoInicial) {
			throw new Exception("Exercício (ano) inválido, inferior a $anoInicial.");
		}
		
		if ($ano > date('Y')) {
			throw new Exception("Exercício (ano) inválido, superior ao ano corrente.");
		}
		
		return true;
	}
}