<?php

/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 *
 *
 */
include_once('formatter.php');

class tp extends formatter {
    const TP = 0;
    const TP_WITH_DEATH = 1; //Non, ce n'est pas se téléporter main dans la main avec le héro de dark siders 2
    //c'est quand le TP tue le supérieur

    public function printPublic(&$bdd) {
        switch (parent::getEvent()->getState()) {
            case tp::TP_WITH_DEATH:
                return 'a t&eacutel&eacuteport&eacute mais en est mort';
            default:
                return 'a t&eacutel&eacuteport&eacute';
        }
    }

    public function printPrivate(&$bdd) {
            $private = parent::getEvent()->infos->getPrivateInfos();
        $result = 'Position : '.$private['x'].'/'.$private['y'].' ('.$private['plan'].')';
        if(parent::getEvent()->getState() == tp::TP_WITH_DEATH){
		$res=parent::chkSrc(parent::getEvent()->getSrc(),parent::getEvent()->getDst());
		if($res){
                    $result .= '<br />perte : -'.$private['perte'].' xp';
		}
        }

        return $result;
    }

    public function getBackground() {
        switch (parent::getEvent()->getState()) {
            case tp::TP_WITH_DEATH:
                return '#FFCCCC';
            default:
                return '#CCCCCC';
        }
    }

}
