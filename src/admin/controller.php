<?php
/**
 * General Controller of SOS Circolari component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_sos_circolari
 * @since       0.0.1
 */

use Joomla\CMS\MVC\Controller\BaseController;

JHtml::_("behavior.formvalidation");

class SosCircolariController extends BaseController {
    protected $default_view = "circolari";
}