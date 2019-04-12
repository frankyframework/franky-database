<?php
namespace Franky\Database\Mysql;

class Delete
{
	var $m_ibd;

	function __construct($conexion = "conexion_bd")
	{
		$this->m_ibd = new \Franky\Database\IBD(new \Franky\Database\configure,$conexion, new \vendor\core\MYDEBUG);
	}

	function execute($tabla, $condiciones)
	{

		$sql = "DELETE FROM $tabla ".(!empty($condiciones) ? "WHERE $condiciones" : '');

		if (($result=$this->m_ibd->Execute($sql)) != IBD_SUCCESS)
		{

			return $result;
		}

		return CONSULTAS_SUCCESS;
	}

}
?>
