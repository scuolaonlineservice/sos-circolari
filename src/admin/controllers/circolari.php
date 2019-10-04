<?php
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;

class SosCircolariControllerCircolari extends BaseController {
    public function add() {
        $this->setRedirect("index.php?option=com_sos_circolari&view=edit&new=true");
    }
}
