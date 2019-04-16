<?php
namespace Franky\Database;

class Debug
{
    var $m_msg;
    var $PHP_TUSAGE;
    var $PHP_RUSAGE;
    var $PHP_MEMORY_USAGE;

    function __construct()
    {
          $this->m_msg = isset($GLOBALS['debug_datbasea']) ? $GLOBALS['debug_datbasea'] : array();
    }

    function I_Init()
    {
      $this->onRequestStart();
    }

    function setMessage( $msg, $key='default' )
    {

            $this->m_msg[] = $msg;
            $GLOBALS['debug_datbasea'][] = $msg;
    }




        public function getMessages($inhtml=true)
        {
            if($inhtml)
            {
              $html = "<ul>";
              if(isset($this->m_msg))
              {
                  $mensajes = $this->m_msg;
                  foreach($mensajes as $msg)
                  {
                      $html .= "<li>" . $msg . "</li>";
                  }
              }
              $html .= "</ul>";

              return $html;
            }

            return $this->m_msg;

        }
}
?>
