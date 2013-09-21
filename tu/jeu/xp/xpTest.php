<?php

use jeu\xp\Config;
use jeu\xp\XpAction;
use jeu\xp\XpCalculator;
use jeu\xp\XpActor;



class XpTest extends PHPUnit_Framework_TestCase{
	
	/**
	 * @dataProvider provider
	 * @covers jeu\xp\XpCalculator::getXp
	 */
	public function testCas1($actors, $actions){
		$this->assertTrue(true);
		$res = XpCalculator::getXp($actors[1], $actors[6], $actions[0]);
		$this->assertEquals($res->xpAtq, Config::XP_BASE_LANCEUR);
		$this->assertContains($res->xpDef, array(1,2));
	}
	
	/**
	 * @dataProvider provider
	 * @covers jeu\xp\XpCalculator::getXp
	 */
	public function testCas2($actors, $actions){
		$this->assertTrue(true);
		$res = XpCalculator::getXp($actors[1], $actors[6], $actions[0]);
		$this->assertContains($res->xpAtq, array(Config::XP_BASE_LANCEUR, Config::XP_BASE_LANCEUR+1));
		$this->assertEquals($res->xpDef, 1);
	}
	
	/**
	 * @dataProvider provider
	 * @covers jeu\xp\XpCalculator::getXp
	 */
	public function testCas3($actors, $actions){
		$this->assertTrue(true);
		$res = XpCalculator::getXp($actors[1], $actors[6], $actions[1]);
 		$this->assertContains($res->xpAtq, array(2,3);
		$this->assertEquals($res->xpDef, Config::XP_BASE_RECEVEUR);
	}
	
	public function provider(){
		return array(
			array(
				'actors' => array(
					null,
					new XpActor(1, 1, 2, XpActor::ANGE, 1, 0),//T1 ange de base
					new XpActor(2, 1, 1.5, XpActor::ANGE, 4, 0),//T4 de base
					new XpActor(3, 1, 2, XpActor::ANGE, 4, XpActor::LEGION_JUSTICE),//T1 ange justicier
					new XpActor(4, 1, 2, XpActor::ANGE, 4, XpActor::LEGION_DEFENSE),//T1 ange defenseur
					new XpActor(5, 5, 2, XpActor::ANGE, 4, 0),//T1 ange de rang d'xp 5
					new XpActor(6, 1, 2, XpActor::DEMON, 4, 0),//T1 démon de rang 1
					new XpActor(7, 5, 2, XpActor::DEMON, 4, 0),//T1 démon de rang 5
					new XpActor(8, 5, 2.5, XpActor::DEMON, 4, 0),//T1 démon de rang 1 avec 2.5 PA
					new XpActor(9, 5, 3, XpActor::DEMON, 4, 0),//T1 démon de rang 1 avec 3 PA
				),
				'actions' => array(
					new XpAction(1, false, false, 0),
					new XpAction(1, false, true, 0)
				)
			)
		);
	}
}