<?php
use PHPUnit\Framework\TestCase;
use Franky\Database\configure;

class ConfigureTest extends TestCase
{
    public function testGet()
    {
          $Configure = new configure();
          $this->assertSame($Configure->getCONECTHOST('conexion_bd'),'test');
          return $data;
    }
}
