<?php
defined('_JEXEC') or die('Restricted access');

class SosCircolariControllerCircolari extends JControllerForm {
    public function add() {
        $this->setRedirect ( 'index.php?option=com_sos_circolari&view=newcircolare' );
    }

    public function close() {
        $this->setRedirect ( 'index.php?option=com_sos_circolari' );
    }
}
