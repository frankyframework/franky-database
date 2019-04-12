<?php
namespace Franky\Database\Mysql;

class Select
{
	var $m_ibd;
	var $m_key;

	function __construct($conexion = "conexion_bd")
	{
		$this->m_ibd = new \Franky\Database\IBD(new \Franky\Database\configure,$conexion, new \Franky\Database\Debug);
	}


	function execute($tabla, $campos, $condiciones, $group, $order,$limit= "")
	{

		$sql = "SELECT ".implode(', ',$campos)." FROM $tabla ".(!empty($condiciones) ? "WHERE $condiciones " : '');

		if(!empty($group))
		{
			$sql .= "GROUP BY $group ";
		}
		if(!empty($order))
		{
			$sql .= "ORDER BY $order ";
		}
                if(!empty($limit))
		{
			$sql .= "LIMIT $limit ";
		}
 		//echo $sql."<br />\n";
    $key = time();

		if(($result=$this->m_ibd->Query($key, $sql)) != IBD_SUCCESS)
		{

			return $result;
		}

		if(($result=$this->m_ibd->NumeroRegistros($key)) <1 )
		{

			$this->m_ibd->Liberar($key);
			return CONSULTAS_ERR_NOROWS;
		}
		$this->m_key=$key;

		return CONSULTAS_SUCCESS;
	}


	function Fetch()
	{


		if ( ! $this->m_key )
		{

			return 0;
		}

		if ( ! $this->m_ibd )
		{

			return 0;
		}

		return $this->m_ibd->Fetch( $this->m_key );

	}

	function NumeroRegistros()
	{


		if ( ! $this->m_key )
		{

			return 0;
		}

		if ( ! $this->m_ibd )
		{

			return 0;
		}

		return $this->m_ibd->NumeroRegistros($this->m_key);
	}

	function Free()
	{


		if ( ! $this->m_key )
		{
			return 0;
		}

		if ( ! $this->m_ibd )
		{
			return 0;
		}

		$this->m_ibd->Liberar( $this->m_key );
		$this->Iniciar();
		return CONSULTAS_SUCCESS;
	}


	function NuevaConsulta()
	{


		if ( ! $this->m_key )
		{

			return 0;
		}

		if ( ! $this->m_ibd )
		{

			return 0;
		}

		$this->m_ibd->Liberar( $this->m_key );
		$this->m_key = 0;
		return CONSULTAS_SUCCESS;
	}
}
?>
