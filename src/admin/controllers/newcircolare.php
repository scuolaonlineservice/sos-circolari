<?php
defined('_JEXEC') or die('Restricted access');

function getAnnoScolastico() {
    $month = idate("m");
    $dacademic_year = idate("d");
    if ($dacademic_year < 31 && $month < 8) {
        return "" . (idate("Y") -1) . "/" . idate("Y");
    } else {
        return "" . idate("Y") . "/" . (idate("Y") + 1 );
    }
}

class SosCircolariControllerNewCircolare extends JControllerLegacy {
    public function save() {
        //echo(base64_encode(file_get_contents($_FILES["test"]['tmp_name'])));

        $userId = JFactory::getUser()->id;
        $bozza = $_POST["draft"] === "true" ? 1 : 0;

        //print_r($formData->getBase64('test'));
        //print_r($formData);

        /*$db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $columns = [
            "numero",
            "oggetto",
            "testo",
            "autore",
            "bozza",
            //"data_pubblicazione",
            "anno_scolastico",
            "azioni_utente",
            "protocollo",
            "privata",
            "luogo"
        ];

        $values = [
            $_POST["numero"],
            $db->quote($_POST["oggetto"]),
            $db->quote($_POST["testo"]),
            $userId,
            $bozza,
            //$_POST["data"],
            $db->quote(getAnnoScolastico()),
            1,//$this->user_action,
            $db->quote($_POST["protocollo"]),
            0,//is private,
            $db->quote($_POST["luogo"])
        ];

        $query->insert($db->quoteName("sos_circolari"))
            ->columns($db->quoteName($columns))
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();*/
    }
}
