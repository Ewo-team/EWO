<?php

use jeu\xp\Config;
use jeu\xp\XpActor;


class RatioPaTest extends PHPUnit_Framework_TestCase{
	
	/**
	 * @covers jeu\xp\XpActor::getRatioPA
	 * @dataProvider provider
	 */
	public function testBase($actor, $value){
		$this->assertEquals($actor->getRatioPA(), $value);
	}
	
	public function provider(){
		return array(
			array(new XpActor(1, 1, 2, XpActor::ANGE, 1, 0),1),
			array(new XpActor(1, 1, 4, XpActor::ANGE, 1, 0),0.5),
			array(new XpActor(1, 1, 1.5, XpActor::ANGE, 4, 0),2/1.5)
		);
	}
}