<?php
namespace Franky\Database;

$error_code=200;

define ("IBD_SUCCESS",              $error_code++);
define ("IBD_ERR_CANTCONNECT",      $error_code++);
define ("IBD_ERR_DBUNAVAILABLE",    $error_code++);
define ("IBD_ERR_CANTSELECT",       $error_code++);
class IBD
{

			var $m_dbResultados;
			var $m_link;
      var $m_conexion_db;
			var $m_row;
      private $configure;
			private $debug;

	function __construct(
		\Franky\Database\configure $MyConfigure,
		$conexion = "conexion_bd",
		\Franky\Database\Debug $MyDebug
		)
	{
		$this->m_link=0;
		$this->m_dbResultados=array();
    $this->m_conexion_db = $conexion;
    $this->m_row = 0;
    $this->configure = $MyConfigure;
		$this->debug = $MyDebug;
	}

	function ConectarBD()
	{



                try {
                    $this->m_link = new \PDO($this->configure->getDRIVE($this->m_conexion_db).":host=".$this->configure->getCONECTHOST($this->m_conexion_db).";dbname=".$this->configure->getDBNAME($this->m_conexion_db),
                    $this->configure->getCONECTUSER($this->m_conexion_db), $this->configure->getCONECTPASSWORD($this->m_conexion_db));
                    $this->m_link->exec("SET CHARACTER SET utf8");

                    return IBD_SUCCESS;
                }
                catch(PDOException $e)
                {
                    $this->debug->setMessage("No se puede hacer la conexion. (".$e->getMessage().")","sql");
                    return IBD_ERR_CANTCONNECT;
                }
	}

	function Execute($consulta)
	{
		if(($result=$this->ConectarBD())!=IBD_SUCCESS)
		{
			return $result;
		}

                $sql_time_i = explode(" ",microtime());

		$result= $this->m_link->exec($consulta);

		$sql_time_f = explode(" ",microtime());
                $sql_time =  round(((float)$sql_time_f[0]+(float)$sql_time_f[1])-((float)$sql_time_i[0]+(float)$sql_time_i[1]),3);

		if(!$result)
		{
                    $this->debug->setMessage("[Result: :(][time: ".$sql_time."]".$consulta,"sql");
                    return IBD_ERR_DBUNAVAILABLE;
		}

                $this->debug->setMessage("[Result: ".$result."][time: ".$sql_time." seg.]".$consulta,"sql");

		return IBD_SUCCESS;
	}

        function Query($origen, $consulta)
	{
		$this->m_row = 0;
		if(($result=$this->ConectarBD())!=IBD_SUCCESS)
		{

			return $result;
		}

                $sql_time_i = explode(" ",microtime());

		$result= $this->m_link->query($consulta);


		$sql_time_f = explode(" ",microtime());
                $sql_time =  round(((float)$sql_time_f[0]+(float)$sql_time_f[1])-((float)$sql_time_i[0]+(float)$sql_time_i[1]),3);

		if(!$result)
		{

                    $this->debug->setMessage("[Result: :(][time: ".$sql_time."]".$consulta,"sql");
                    return IBD_ERR_DBUNAVAILABLE;
		}


                $this->m_dbResultados[$origen]=$result->fetchAll();



                $this->debug->setMessage("[Result: ".$this->NumeroRegistros($origen)."][time: ".$sql_time." seg.]".$consulta,"sql");

		return IBD_SUCCESS;
	}

	function Fetch($origen)
	{

            if(isset($this->m_dbResultados[$origen][$this->m_row]))
            {
                $row = $this->m_dbResultados[$origen][$this->m_row];

                $this->m_row++;
                return $row;
            }

            return false;
	}

	function  Liberar($origen)
	{
            if(isset($this->m_dbResultados[$origen]))
            {
                    $this->m_dbResultados[$origen]=array();
            }

            return IBD_SUCCESS;
	}

	function NumeroRegistros($origen)
	{

		if(isset($this->m_dbResultados[$origen]))
		{
                    return count($this->m_dbResultados[$origen]);

		}

		return 0;
	}

	function UltimoID()
	{
		if ( ! $this->m_link )
		{
			return 0;
		}
		return $this->m_link->lastInsertId();
	}
}
?>
