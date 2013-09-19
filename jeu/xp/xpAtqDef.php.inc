<?php

namespace jeu\xp;

/**
 * @abstract Conteneur de résultat pour action simple
 * @author Kamule
 */
class XpAtqDef{
	public $xpAtq;
	public $xpDef;

	public function __construct($xpAtq, $xpDef){
		$this->xpAtq = $xpAtq;
		$this->xpDef = $xpDef;
	}
}