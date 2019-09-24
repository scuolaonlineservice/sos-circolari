<?php
defined('_JEXEC') or die('Restricted access');

JLoader::register('Utilities', __DIR__ . '/helpers/utilities.php');
JLoader::register('S3Helper', __DIR__ . '/helpers/s3.php');

$controller = JControllerLegacy::getInstance('SosCircolari');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

$controller->redirect();