<?php
namespace Franky\Database\Mysql;


class Where{


  private $where;


  public function __construct()
  {
    $this->where = '';
  }

  public function concat($sql)
  {
    $this->where .= ' '.$sql;
  }

  public function addAnd($key,$val,$operation)
  {
      $this->where .= (!empty($this->where) ? ' and ' : '').$key.' '.$operation.' \''.$val.'\' ';
  }

  public function addOr($key,$val,$operation)
  {
      $this->where .= (!empty($this->where) ? ' or ' : '').$key.' '.$operation.' \''.$val.'\' ';
  }

  public function get()
  {
      $bad = ["and","or"];
      $primeros = ["("];
      $primero = true;
      $tokens = explode(" ",$this->where);
      foreach($tokens as $k => $token)
      {

          if($primero)
          {
              if(!empty($token))
              {
                if(in_array(strtolower($token),$bad))
                {
                  unset($tokens[$k]);
                }
                $primero = false;
              }
          }
          if(in_array(strtolower($token),$primeros))
          {
              $primero = true;
          }


      }
      $result = implode(" ",$tokens);
      $this->free();
      return trim($result);
  }

  public function free()
  {
    $this->where = '';
  }


}

 ?>
