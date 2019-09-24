<?php
defined ( '_JEXEC' ) or die ();

class SosCircolariModelEdit extends JModelList
{
    function getCircolare($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from('#__com_sos_circolari');
        $query->where ("id=$id");

        $db->setQuery($query);

        $result = $db->loadObject();
        return $result;
    }

    function getNumero() {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("MAX(numero) as max")
            ->from("#__com_sos_circolari");

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result->max ? $result->max + 1 : 1;
    }

    function getAzioniUtente() {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("*")
            ->from("#__com_sos_azioni_utente");

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    function getGroups() {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("title")
            ->from("j_usergroups");

        $db->setQuery($query);
        $result = $db->loadObjectList();

        $groups =  array_map(function ($group) {
            return $group->title;
        }, $result);

        return $groups;

    }

    function getAllegati($id) {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("id, nome")
            ->from("#__com_sos_allegati")
            ->where("id_circolare=$id");

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }
}