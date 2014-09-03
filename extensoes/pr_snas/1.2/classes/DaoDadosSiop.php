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
			
			$sql = '
					select 
						stsp."codigoPrograma" as cod_programa
						, stsp.titulo as tit_programa
					from 
						snas.tb_siop_programas stsp
						, snas.tb_siop_orgaos stso
						, sgdoc.tb_pessoa_siorg stps
						, sgdoc.tb_unidades stu	
					where
						stu.id = :unidade
						and stu.co_siorg = stps.co_siorg
						and stso."orgaoSiorg" = stps.co_siorg::text
						and stso.exercicio = :ano
						and stso."codigoOrgao"::text = stsp."codigoOrgao"::text
						and stsp.exercicio = :ano 
						and stsp."snExclusaoLogica" = false
					order by 
						stsp.titulo
					';
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindValue(':unidade', $unidade, PDO::PARAM_STR);
			$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
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
	
	public static function getProgramaVinculado($idVinculo = false) {
		try {
			
			if (!$idVinculo) {
				throw new Exception('Informe um vínculo.');
			}
	
			$sql = 'select v.id, v.exercicio, v.codigo_orgao as unidade, v.codigo_programa as cod_programa, p.titulo as tit_programa
					from snas.tb_prazo_vinculo_ppa v
					  inner join snas.tb_siop_programas p on (p."codigoPrograma"=v.codigo_programa and p.exercicio=v.exercicio)
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
	 * Lista os objetivos e metas de um programa, em um exercício (ano)
	 */
	public static function getObjetivosMetas($programa = false, $ano = false) {
		try {
				
			if (!$programa) {
				throw new Exception('Informe um programa.');
			}
	
			self::validarExercicio($ano);
				
			$sql = 'select o."codigoObjetivo" as cod_objetivo, o.enunciado as dsc_objetivo,
						m."identificadorUnico" as id_meta, m."codigoMeta" as cod_meta, m.descricao as dsc_meta
					from snas.tb_siop_objetivos o 
  						left join snas.tb_siop_metas m on (m."codigoObjetivo"=o."codigoObjetivo" and m."codigoPrograma"=o."codigoPrograma" and m.exercicio=o.exercicio)
					where o."codigoPrograma" = ? and o.exercicio = ? and o."snExclusaoLogica" = false
					order by o."codigoObjetivo", m."codigoMeta";';

			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindParam(1, $programa, PDO::PARAM_STR);
			$stmt->bindParam(2, $ano, PDO::PARAM_INT);
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
	 * Lista as ações de um órgão (unidade) em um programa, em um exercício (ano)
	 */
	public static function getAcoes($programa = false, $unidade = false, $ano = false) {
		try {
				
			if (!$programa) {
				throw new Exception('Informe um programa.');
			}
			
			if (!$unidade) {
				throw new Exception('Informe uma unidade (órgão).');
			}

			self::validarExercicio($ano);
				
			$sql = '
					select 
						a."codigoAcao" as cod_acao
						, a.titulo as tit_acao
						, e."dotacaoAtual" as val_dotacao_atual
						, e.empenhado as val_empenhado
						, e.liquidado as val_liquidado
						, e."percentualLiquidadoEmpenhado" as per_liq_emp
					from 
						sgdoc.tb_unidades u
						, sgdoc.tb_pessoa_siorg p
						, snas.tb_siop_orgaos o
						, snas.tb_siop_acoes a
  						inner join snas.tb_siop_execucao_orcamentaria e on (e."codigoAcao"=a."codigoAcao" and e.exercicio=a.exercicio)
					where 
						u.id = :unidade
						and u.co_siorg = p.co_siorg
						and o."orgaoSiorg" = p.co_siorg::text
						and o.exercicio = :ano
						and a."codigoOrgao"::text = o."codigoOrgao"::text
						and a."codigoPrograma" = :programa
						and a.exercicio = :ano and 
						a."snExclusaoLogica" = false;';
				
			$stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
			$stmt->bindValue(':unidade', $unidade, PDO::PARAM_STR);
			$stmt->bindValue(':programa', $programa, PDO::PARAM_STR);
			$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
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
  					  v.codigo_objetivo as cod_objetivo, o.enunciado as dsc_objetivo,
  					  v.codigo_meta as cod_meta, m.descricao as dsc_meta
					from snas.tb_prazo_vinculo_ppa v
					  inner join snas.tb_siop_objetivos o on (o."codigoObjetivo"=v.codigo_objetivo and o.exercicio=v.exercicio)
					  inner join snas.tb_siop_metas m on (m."codigoMeta"::text=v.codigo_meta and m.exercicio=v.exercicio)
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