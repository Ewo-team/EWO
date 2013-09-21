<?php

use jeu\xp\Config;
use jeu\xp\XpAction;
use jeu\xp\XpCalculator;
use jeu\xp\XpActor;


class XpAjustementTest extends PHPUnit_Framework_TestCase{
	
	/**
	 * @covers jeu\xp\XpCalculator::ajustXp
	 */
	public function testBase(){
		for($i = 0; $i < 500;++$i){
			$v = mt_rand(0,900);
			$this->assertEquals(XpCalculator::ajustXp($v), $v);
		}
	}
	
	/**
	 * @covers jeu\xp\XpCalculator::ajustXp
	 */
	public function testDecimalPositiv(){
		$v = 2.8;
		$rm = 0;
		$rp = 0;
		$t = 1000;
		for($i = 0; $i < $t;++$i){
			if(XpCalculator::ajustXp($v) == floor($v))
				++$rm;
			else
				++$rp;
		}
		$this->assertEquals(8,round(10*$rp/$t));
	}

	/**
	 * @covers jeu\xp\XpCalculator::ajustXp
	 */
	public function testDecimalNegativ(){
		$v = -2.8;
		$rm = 0;
		$rp = 0;
		$t = 1000;
		for($i = 0; $i < $t;++$i){
			$c = XpCalculator::ajustXp($v);
			if($c == floor($v))
				++$rm;
			else
				++$rp;
		}
		$this->assertEquals(8,round(10*$rp/$t));
	}
}