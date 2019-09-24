<?php
defined('_JEXEC') or die('Restricted access');

class SosCircolariTableSosCircolari extends JTable
{
    function __construct(&$db)
    {
        parent::__construct('#__com_sos_circolari', 'id', $db);
    }
}