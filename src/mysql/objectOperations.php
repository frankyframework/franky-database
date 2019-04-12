<?php
namespace Franky\Database\Mysql;


$error_code=200;
define ("CONSULTAS_SUCCESS",        $error_code++);
define("REGISTRO_SUCCESS",          $error_code++);
define("REGISTRO_ERROR",            $error_code++);
define("CONSULTAS_ERR_NOROWS",			$error_code++);


abstract class   objectOperations
{
		protected $m_total;
    protected $m_result;
		protected $m_ultimoID;
    protected $m_consultas;
		protected $insert;
		protected $update;
		protected $delete;
    protected $m_row;
    protected $m_page;
    protected $m_tampag;
    protected $m_grupo;
    protected $m_orden;
		protected $where;
		protected $from;

	function __construct($conexion = "conexion_bd")
	{
		$this->m_total		= 0;
		$this->m_consultas	= new \Franky\Database\mysql\Select($conexion);
		$this->insert	= new \Franky\Database\mysql\Insert($conexion);
		$this->update	= new \Franky\Database\mysql\Update($conexion);
		$this->delete	= new \Franky\Database\mysql\Delete($conexion);
		$this->where	= new \Franky\Database\mysql\Where();
		$this->from	= new \Franky\Database\mysql\From();
    $this->m_results	= array();
    $this->m_row            = 0;
    $this->m_ultimoId       = 0;
    $this->m_page           = 1;
    $this->m_tampag         = 1;
    $this->m_grupo          = "";
    $this->m_orden          = "";

	}

	public function where()
	{
		return $this->where;
	}

	public function from()
	{
		return $this->from;
	}

  function setPage($val)
	{
		$this->m_page = $val;
	}
	function setTampag($val)
	{
		$this->m_tampag = $val;
	}
        function setGrupo($val)
	{
		$this->m_grupo = $val;
	}
        function setOrdensql($val)
	{
		$this->m_orden = $val;
	}
	function getTotal()
	{
		return $this->m_total;
	}
	function getUltimoID()
	{
		return $this->m_ultimoID;
	}
	function getColeccion($campos,$total=true)
	{
		$condiciones =  $this->where()->get();

		if(!is_array($campos) || empty($campos))
    {
        return REGISTRO_ERROR;
    }




	  $reg1 = ($this->m_page-1) * $this->m_tampag;
		$this->m_row = 0;
		$this->m_consultas->NuevaConsulta();

    $from = $this->from()->get();
	  if($total)
	  {
	      $this->m_consultas->execute($from,array($campos[0]),$condiciones, $this->m_grupo,$this->m_orden);
	      $this->m_total = $this->m_consultas->NumeroRegistros();
	  }

		$result = $this->m_consultas->execute($from,$campos,$condiciones, $this->m_grupo,$this->m_orden,"$reg1, ".$this->m_tampag);
	  if(!$total)
	  {

	      $this->m_total = $this->m_consultas->NumeroRegistros();
	  }

	  if($result == CONSULTAS_SUCCESS)
		{
          $n = 0;
					while($registro = $this->m_consultas->Fetch())
					{

              foreach($registro as $campo => $valor)
              {

                  $this->m_result[$n][$campo] = $valor;



              }
              $n++;
						}
						return REGISTRO_SUCCESS;
					}

				$this->m_total = 0;
				return $result;



	}



	function eliminarRegistro()
	{

				if($this->delete->execute($this->from()->get(),$this->where()->get()) != CONSULTAS_SUCCESS)
				{
		   			return REGISTRO_ERROR;
				}
				return REGISTRO_SUCCESS;
	}

	function guardarRegistro($nvoregistro)
	{

            if(!is_array($nvoregistro) && empty($nvoregistro))
            {
                return REGISTRO_ERROR;
            }
            if($this->insert->execute($this->from()->get(), $nvoregistro) != CONSULTAS_SUCCESS)
            {
                return REGISTRO_ERROR;
            }

            $this->m_ultimoID = $this->insert->UltimoID();

            return REGISTRO_SUCCESS;
	}

	function editarRegistro($nvoregistro)
	{
            if(!is_array($nvoregistro) && empty($nvoregistro))
            {
                return REGISTRO_ERROR;
            }

            if($this->update->execute($this->from()->get(), $nvoregistro,$this->where()->get()) != CONSULTAS_SUCCESS)
            {
            	return REGISTRO_ERROR;
            }

            return REGISTRO_SUCCESS;
	}

        function getRows()
        {
            if(isset($this->m_result[$this->m_row]))
            {
                $row = $this->m_result[$this->m_row];
                $this->m_row++;

                return $row;
            }
            else
            {
                return false;
            }

        }

        function jsonResponse($status = '200')
        {
            header("Content-Type: application/json");
            $data = array();
            $data["status"] = $status;
            $data["data"] = $this->m_result;

            return json_encode($data);

	}


        function free()
        {
						$this->where->free();
            $this->m_result = array();
        }
}

?>
