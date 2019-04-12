<?php
namespace Franky\Database\Mysql;


class From{


  private $from;
  private $join;


  public function __construct()
  {
    $this->from = '';
    $this->join = '';
  }

  public function addTable($table)
  {
    $this->from = $table;
  }

  public function addInner($table,$input1,$input2)
  {
      $this->join .=  'INNER JOIN '.$table .' ON '.$input1.'='.$input2.' ';
  }

  public function addLeft($table,$input1,$input2)
  {
      $this->join .=  'LEFT JOIN '.$table.' ON '.$input1.'='.$input2.' ';
  }

  public function get()
  {
      $sql = $this->from.' '.$this->join;
      $this->free();
      return $sql;
  }

  public function free()
  {
    $this->join = '';
  }


}

 ?>
