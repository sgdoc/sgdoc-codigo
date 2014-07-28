<?php
/**
 * File for class QualitativoStructCredencialDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructCredencialDTO originally named credencialDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructCredencialDTO extends QualitativoStructBaseDTO
{
	/**
	 * The perfil
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $perfil;
	/**
	 * The senha
	 * @var string
	 */
	public $senha;
	/**
	 * The usuario
	 * @var string
	 */
	public $usuario;
	/**
	 * Constructor method for credencialDTO
	 * @see parent::__construct()
	 * @param int $_perfil
	 * @param string $_senha
	 * @param string $_usuario
	 * @return QualitativoStructCredencialDTO
	 */
	public function __construct($_perfil = NULL,$_senha = NULL,$_usuario = NULL)
	{
		QualitativoWsdlClass::__construct(array('perfil'=>$_perfil,'senha'=>$_senha,'usuario'=>$_usuario));
	}
	/**
	 * Get perfil value
	 * @return int|null
	 */
	public function getPerfil()
	{
		return $this->perfil;
	}
	/**
	 * Set perfil value
	 * @param int $_perfil the perfil
	 * @return int
	 */
	public function setPerfil($_perfil)
	{
		return ($this->perfil = $_perfil);
	}
	/**
	 * Get senha value
	 * @return string|null
	 */
	public function getSenha()
	{
		return $this->senha;
	}
	/**
	 * Set senha value
	 * @param string $_senha the senha
	 * @return string
	 */
	public function setSenha($_senha)
	{
		return ($this->senha = $_senha);
	}
	/**
	 * Get usuario value
	 * @return string|null
	 */
	public function getUsuario()
	{
		return $this->usuario;
	}
	/**
	 * Set usuario value
	 * @param string $_usuario the usuario
	 * @return string
	 */
	public function setUsuario($_usuario)
	{
		return ($this->usuario = $_usuario);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructCredencialDTO
	 */
	public static function __set_state(array $_array,$_className = __CLASS__)
	{
		return parent::__set_state($_array,$_className);
	}
	/**
	 * Method returning the class name
	 * @return string __CLASS__
	 */
	public function __toString()
	{
		return __CLASS__;
	}
}
?>