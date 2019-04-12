<?php
namespace Franky\Database\Mysql;

class Insert
{
	var $m_ibd;

	function __construct($conexion = "conexion_bd")
	{
		$this->m_ibd = new \Franky\Database\IBD(new \Franky\Database\configure,$conexion, new \vendor\core\MYDEBUG);
	}

	function execute($tabla, $nvoregistro)
	{
    	$sql = "INSERT INTO $tabla (".implode(', ', array_keys($nvoregistro)).") VALUES ('".implode('\', \'', $nvoregistro)."')";
      //  echo $sql;
			//	   exit;
  		if (($result = $this->m_ibd->Execute($sql)) != IBD_SUCCESS)
  		{
  			return $result;
  		}

		  return CONSULTAS_SUCCESS;
	}

	function UltimoID()
	{
  		if ( ! $this->m_ibd )
  		{
  			return 0;
  		}
  		return $this->m_ibd->UltimoID();
	}

}
?>
