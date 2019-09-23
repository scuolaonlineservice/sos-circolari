<?php
defined('_JEXEC') or die('Restricted access');
echo __DIR__ . "/utilities.php";
use \Joomla\Utilities\ArrayHelper;

class SosCircolariModelCircolari extends JModelList {
    public $id;
    public $title = "CIAONE";
    public $number;
    public $record_number;
    public $location;
    public $draft;
    public $groups;
    public $users;
    public $user_action;
    public $body;
    public $attachments;
    public $is_private;
    public $academic_year;
    public $userId;
    public $publication_date;

    public function __construct(array $config) {
        $this->title = $config["oggetto"];
        $this->body = $config["testo"];
        $this->draft = $config["bozza"];
        $this->is_private = $config["privata"];
        $this->record_number = $config["protocollo"];
        $this->location = $config["luogo"] ? $config["luogo"] : "Reggio Emilia";
        $this->user_action = $config["azioni_utente"];
        $this->attachments = $config["allegati"];
        $this->groups = $config["id_gruppi"];
        $this->users = $config["id_utenti"];
        $this->number = $config["numero"] ? (
                $config["bozza"] ? "NULL" : $this->getNumber()
            ) : "NULL";
        $this->publication_date = $config["data_pubblicazione"] ? (
                $config["bozza"] ? "NULL" : str_replace("-","",date("Y-m-d"))
            ) : "NULL";
        $this->academic_year = getAnnoScolastico();
        $this->userId = (int)(JFactory::getUser())->id;
    }

    private function getNumber() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("numero")
            ->from("sos_circolari")
            ->where("numero is not null")
            ->order("numero desc")
            ->setLimit("1");

        $db->setQuery($query)->execute();

        $result = $db->loadObjectList();
        return empty(ArrayHelper::fromObject($result[0])["numero"]) ? 
            1 : 
            ((int)ArrayHelper::fromObject($result[0])["numero"]) + 1;
    }

    
    private function createAttachment($db, $attachment) {
        $query = $db->getQuery(true);

        $columns = ["nome", "url"];
        $values = [$db->quote($attachment["nome"]), $db->quote($attachment["url"])];

        $query->insert("sos_allegati")
            ->columns($columns)
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();

        return $db->insertid();
    }

    private function linkAttachment($db, $attachment_id) {
        $query = $db->getQuery(true);

        $columns = ["id_allegato", "id_circolare"];
        $values = [$attachment_id, $this->id];

        $query->insert("sos_circolari_allegati")
            ->columns($columns)
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();
    }

    private function linkGroup($db, $group_id) {
        $query = $db->getQuery(true);

        $columns = ["id_gruppo", "id_circolare"];
        $values = [$group_id, $this->id];

        $query->insert("sos_gruppi_destinatari")
            ->columns($columns)
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();
    }

    private function linkUser($db, $user_id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $columns = ["id_utente", "id_circolare"];
        $values = [$user_id, $this->id];

        $query->insert("sos_utenti_destinatari")
            ->columns($columns)
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();
    }

    public function createCircolare() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $columns = [
            "numero",
            "oggetto",
            "testo",
            "autore",
            "bozza",
            "data_pubblicazione",
            "anno_scolastico",
            "azioni_utente",
            "protocollo",
            "privata",
            "luogo"
        ];

        $values = [
            $this->number,
            $db->quote($this->title),
            $db->quote($this->body),
            $this->userId,
            $this->draft ? 1 : 0,
            $this->publication_date,
            $db->quote($this->academic_year),
            $this->user_action,
            $db->quote($this->record_number),
            $this->is_private,
            $db->quote($this->location)
        ];

        $query->insert($db->quoteName("sos_circolari"))
            ->columns($db->quoteName($columns))
            ->values(implode(",", $values));

        $db->setQuery($query)->execute();

        $this->id = $db->insertid();

        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
               $id_allegato = $this->createAttachment($db, $attachment);
               $this->linkAttachment($db, $id_allegato);
            }
        }
        if (!empty($this->users)) {
            foreach ($this->users as $userId) {
               $this->linkUser($db, $userId);
            }
        }
        if (!empty($this->groups)) {
            foreach ($this->groups as $groupId) {
               $this->linkGroup($db, $groupId);
            }
        }
    }
    protected function getListQuery() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("sos_circolari.oggetto, sos_circolari.numero, sos_circolari.anno_scolastico, sos_circolari.bozza, 
                j_users.name, sos_circolari.data_pubblicazione, sos_risposte.id_utente, sos_risposte.id_azione_utente")
            ->from("sos_circolari")
            ->join("inner", "j_users ON sos_circolari.autore = j_users.id")
            ->join("inner", "sos_risposte ON sos_circolari.id = sos_risposte.id_circolare")
            ->order("numero desc");
        return $query;
    }

} // class Circolare

function deleteCircolare($id) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $conditions = ["id = " . $id];

    $query->delete("sos_circolari")
        ->where($conditions);

    $db->setQuery($query)->execute();
}

function getCircolare($id) {
    $db = JFactory::getDbo();
    $queryCircolare = $db->getQuery(true);
    $queryAttachments = $db->getQuery(true);
    $queryUsers = $db->getQuery(true);
    $queryGroups = $db->getQuery(true);

    $queryCircolare->select("numero, oggetto, testo, name, bozza, data_pubblicazione, 
            anno_scolastico, azione, protocollo, privata, luogo")
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
    return($circolare);
}



function readFrontendWidget() {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select("numero, oggetto")
        ->from("sos_circolari")
        ->where("bozza = 0 and privata = 0")
        ->order("numero desc");
    
    $db->setQuery($query)
        ->execute();

    $result = ArrayHelper::fromObject($db->loadObjectList());
    return $result;
}