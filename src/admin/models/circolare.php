<?php
defined ( '_JEXEC' ) or die ();
use \Joomla\Utilities\ArrayHelper;

class SosCircolariModelCircolare extends JModelList
{
    function getCircolare($id) {
        $db = JFactory::getDbo();

        $queryCircolare = $db->getQuery(true);
        $queryAttachments = $db->getQuery(true);
        $queryUsers = $db->getQuery(true);
        $queryGroups = $db->getQuery(true);

        $queryCircolare->select("numero, oggetto, testo, name, bozza, data_pubblicazione, data_fine_interazione, anno_scolastico, azione, protocollo, privata, luogo")
            ->from("#__com_sos_circolari")
            ->join("inner", "j_users ON #__com_sos_circolari.autore = j_users.id")
            ->join("inner", "#__com_sos_azioni_utente ON #__com_sos_circolari.azioni_utente = #__com_sos_azioni_utente.id")
            ->where(["#__com_sos_circolari.id = " . $id]);
        $db->setQuery($queryCircolare)->execute();
        $circolare = ArrayHelper::fromObject($db->loadObjectList()[0]);

        $queryAttachments->select("nome")
            ->from("#__com_sos_allegati")
            ->where(["id_circolare=" . $id]);
        $db->setQuery($queryAttachments)->execute();
        $attachments = Utilities::flat(ArrayHelper::fromObject($db->loadObjectList()), "nome");

        $queryUsers->select("name")
            ->from("j_users")
            ->join("inner","#__com_sos_utenti_destinatari on #__com_sos_utenti_destinatari.id_utente = j_users.id")
            ->where(["#__com_sos_utenti_destinatari.id_circolare = " . $id]);
        $db->setQuery($queryUsers)->execute();
        $users = Utilities::flat(ArrayHelper::fromObject($db->loadObjectList()), "name");

        $queryGroups->select("title")
            ->from("j_usergroups")
            ->join("inner","#__com_sos_gruppi_destinatari on #__com_sos_gruppi_destinatari.id_gruppo = j_usergroups.id")
            ->where(["#__com_sos_gruppi_destinatari.id_circolare = " . $id]);
        $db->setQuery($queryGroups)->execute();
        $groups = Utilities::flat(ArrayHelper::fromObject($db->loadObjectList()), "title");

        $circolare["utenti"] = $users;
        $circolare["gruppi"] = $groups;
        $circolare["allegati"] = $attachments;

        return((object) $circolare);
    }

    function getListQuery() {
        $id = JFactory::getApplication()->input->get->get("id");
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select("#__com_sos_circolari_risposte.azione, #__com_sos_circolari_risposte.data_risposta, j_users.username, j_users.name")
            ->from("#__com_sos_circolari_risposte")
            ->join("inner","j_users on #__com_sos_circolari_risposte.id_utente = j_users.id")
            ->where(["#__com_sos_circolari_risposte.id_circolare = " . $id]);

        return $query;
    }
}