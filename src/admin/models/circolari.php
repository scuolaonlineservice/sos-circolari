<?php
defined('_JEXEC') or die('Restricted access');

class SosCircolariModelCircolari extends JModelList
{
    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("#__com_sos_circolari.id, #__com_sos_circolari.numero, #__com_sos_circolari.oggetto, #__com_sos_circolari.anno_scolastico, #__com_sos_circolari.bozza, j_users.name, #__com_sos_circolari.data_pubblicazione")
            ->from("#__com_sos_circolari")
            ->join("inner", "j_users ON #__com_sos_circolari.autore = j_users.id")
            ->order("numero desc");

        return $query;
    }
}
