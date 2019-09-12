<?php
defined('_JEXEC') or die('Restricted access');

class SosCircolariModelCircolari extends JModelList
{
    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("oggetto, data_pubblicazione")
            ->from("sos_circolari");

        return $query;
    }
}
