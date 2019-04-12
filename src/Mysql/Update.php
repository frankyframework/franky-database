<?php
namespace Franky\Database\Mysql;

class Update
{
	var $m_ibd;


	function __construct($conexion = "conexion_bd")
	{
		$this->m_ibd = new \Franky\Database\IBD(new \Franky\Database\configure,$conexion,new \Franky\Database\Debug);
	}

	function execute($tabla, $nvoregistro, $condiciones)
	{
    $nvoregistroDB = "";
		foreach ($nvoregistro as $llave => $valor )
		{
			$nvoregistroDB .= "$llave='$valor', ";
		}

		$nvoregistroDB = substr($nvoregistroDB, 0, -2);

		$sql = "UPDATE $tabla SET $nvoregistroDB ".(!empty($condiciones) ? "WHERE $condiciones" : '');
        //    echo $sql; //exit;
		if (($result = $this->m_ibd->Execute($sql)) != IBD_SUCCESS)
		{

			return $result;
		}

		return CONSULTAS_SUCCESS;
	}
}
?>
