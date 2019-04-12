<?php
namespace Franky\Database;

class configure
{
    private $db;
    function __construct()
    {

        $this->db = include((!empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : __DIR__)."/configure/databases.php");

    }

    public function getCONECTHOST($conexion)
    {
        return $this->db[$conexion]["host"];
    }
    public function getCONECTUSER($conexion)
    {
        return $this->db[$conexion]["usuario"];
    }
    public function getCONECTPASSWORD($conexion)
    {
        return $this->db[$conexion]["password"];
    }

    public function getDBNAME($conexion)
    {
        return $this->db[$conexion]["basedatos"];
    }

    public function getDRIVE($conexion)
    {
        return $this->db[$conexion]["drive"];
    }

}
?>
