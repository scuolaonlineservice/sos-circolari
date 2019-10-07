<?php
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;

JLoader::register('Utilities', __DIR__ . '/helpers/utilities.php');
JLoader::register('S3Helper', __DIR__ . '/helpers/s3.php');

$controller = BaseController::getInstance('SosCircolari');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

$controller->redirect();