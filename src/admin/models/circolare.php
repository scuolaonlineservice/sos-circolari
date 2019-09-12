<?php
defined ( '_JEXEC' ) or die ();
use \Joomla\Utilities\ArrayHelper;

function flat(array $elems, $key) {
    for ($i = 0; $i < count($elems); $i++) { $flat[$i] = $elems[$i][$key]; }
    return $flat;
}

class SosCircolariModelCircolare extends JModelList
{
    function getCircolare($id) {
        $db = JFactory::getDbo();

        $queryCircolare = $db->getQuery(true);
        $queryAttachments = $db->getQuery(true);
        $queryUsers = $db->getQuery(true);
        $queryGroups = $db->getQuery(true);

        $queryCircolare->select("numero, oggetto, testo, name, bozza, data_pubblicazione, anno_scolastico, azione, protocollo, privata, luogo")
            ->from("sos_circolari")
            ->join("inner", "j_users ON sos_circolari.autore = j_users.id")
            ->join("inner", "sos_azioni_utente ON sos_circolari.azioni_utente = sos_azioni_utente.id")
            ->where(["sos_circolari.id = " . $id]);
        $db->setQuery($queryCircolare)->execute();
        $circolare = ArrayHelper::fromObject($db->loadObjectList()[0]);

        $queryAttachments->select("nome")
            ->from("sos_allegati")
            ->join("inner","sos_circolari_allegati on sos_circolari_allegati.id_allegato = sos_allegati.id")
            ->where(["id_circolare = " . $id]);
        $db->setQuery($queryAttachments)->execute();
        $attachments = flat(ArrayHelper::fromObject($db->loadObjectList()), "nome");

        $queryUsers->select("name")
            ->from("j_users")
            ->join("inner","sos_utenti_destinatari on sos_utenti_destinatari.id_utente = j_users.id")
            ->where(["sos_utenti_destinatari.id_circolare = " . $id]);
        $db->setQuery($queryUsers)->execute();
        $users = flat(ArrayHelper::fromObject($db->loadObjectList()), "name");

        $queryGroups->select("title")
            ->from("j_usergroups")
            ->join("inner","sos_gruppi_destinatari on sos_gruppi_destinatari.id_gruppo = j_usergroups.id")
            ->where(["sos_gruppi_destinatari.id_circolare = " . $id]);
        $db->setQuery($queryGroups)->execute();
        $groups = flat(ArrayHelper::fromObject($db->loadObjectList()), "title");

        $circolare["utenti"] = $users;
        $circolare["gruppi"] = $groups;
        $circolare["allegati"] = $attachments;

        return((object) $circolare);
    }
}