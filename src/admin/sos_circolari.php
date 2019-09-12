<?php
defined('_JEXEC') or die('Restricted access');

$controller = JControllerLegacy::getInstance('SosCircolari');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

JLoader::register('UtilitiesHelper', JPATH_ROOT . '/administrator/components/com_sos_circolari/helpers/UtilitiesHelper.php');

$controller->redirect();
