<?php
defined('_JEXEC') or die('Restricted access');
use \Joomla\Utilities\ArrayHelper;

class SosCircolariControllerEdit extends JControllerLegacy
{
    public function cancel() {
        $this->setRedirect('index.php?option=com_sos_circolari');
    }

    protected function insertGroups($circolareId) {
        $selectedGroups = ArrayHelper::fromObject(array_filter(Utilities::getGroups(), function($group) {
            return isset($_POST["group-$group->id"]) ? true : false;
        }));

        if (isset($selectedGroups)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $columns = ["id_gruppo", "id_circolare"];
            $values = [];

            foreach ($selectedGroups as ["id" => $groupId]) {
                array_push($values, "$groupId,$circolareId");
            }

            $query
                ->insert($db->quoteName("#__com_sos_gruppi_destinatari"))
                ->columns($db->quoteName($columns))
                ->values($values);

            $db->setQuery($query)->execute();
        }
    }

    protected function updateGroups($circolareId) {
        $oldGroups = Utilities::getCircolareGroups($circolareId);

        $selectedGroups = Utilities::flat(ArrayHelper::fromObject(array_filter(Utilities::getGroups(), function($group) {
            return isset($_POST["group-$group->id"]) ? true : false;
        })), "id");

        $groupsToAdd = array_filter($selectedGroups, function($groupId) use ($oldGroups) {
            return !in_array($groupId, $oldGroups);
        });

        $groupsToDelete = array_filter($oldGroups, function ($groupId) use ($selectedGroups) {
            return !in_array($groupId, $selectedGroups);
        });

        $db = JFactory::getDbo();

        $columns = ["id_gruppo", "id_circolare"];

        //Insert query
        if (isset($groupsToAdd)) {
            $query = $db->getQuery(true);
            $values = [];
            foreach ($groupsToAdd as $groupId) {
                array_push($values, "$groupId,$circolareId");
            }

            $query
                ->insert($db->quoteName("#__com_sos_gruppi_destinatari"))
                ->columns($db->quoteName($columns))
                ->values($values);

            $db->setQuery($query)->execute();
        }

        //Delete query
        if (isset($groupsToDelete)) {
            $query = $db->getQuery(true);
            $values = [];
            foreach ($groupsToDelete as $groupId) {
                array_push($values, "$groupId,$circolareId");
            }

            $query
                ->delete($db->quoteName("#__com_sos_gruppi_destinatari"))
                ->columns($db->quoteName($columns))
                ->values($values);

            $db->setQuery($query)->execute();
        }
    }

    protected function insertAllegati($circolareId) {
        //foreach ($allegati as $file) {
        //    $name = $file["name"];
        //    $content = base64_encode(file_get_contents($file["name"]));
        //
        //    var_dump(S3Helper::upload($name, $content));
        //}

        $allegati = Utilities::rearrangeArray($_FILES["allegati"]);

        if (isset($allegati)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $columns = ["id_circolare", "nome"];
            $values = [];

            foreach ($allegati as ["name" => $name]) {
                array_push($values, $circolareId . "," . $db->quote($name));
            }

            $query
                ->insert($db->quoteName("#__com_sos_allegati"))
                ->columns($db->quoteName($columns))
                ->values($values);

            $db->setQuery($query)->execute();
        }
    }

    protected function deleteAllegati($circolareId) {
        $allegati = $_POST["delete"];

        if (isset($allegati)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query
                ->delete($db->quoteName("#__com_sos_allegati"))
                ->where("id_circolare=$circolareId");

            $db->setQuery($query)->execute();
        }
    }

    public function save() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $userId = JFactory::getUser()->id;
        $dataFineInterazione = $_POST["data-fine-interazione"] ? $db->quoteName(date("Y-m-d", strtotime($_POST["data-fine-interazione"]))) : "NULL";
        $bozza = $_POST["draft"] === "true" ? 1 : 0;
        $annoScolastico = Utilities::getAnnoScolastico();
        $azioneUtente = Utilities::getAzioneIdFromDescription($_POST['action']);
        $privata = $_POST["private"] === "true" ? 1 : 0;

        $id = $_POST["id"] ?? null;

        if ($id) {
            //Update

            if ($bozza) {
                $fields = [
                    $db->quoteName("data_fine_interazione") . "=" . $dataFineInterazione,
                    $db->quoteName("oggetto") . "=" . $db->quote($_POST["oggetto"]),
                    $db->quoteName("testo") . "=" . $db->quote($_POST["testo"]),
                    $db->quoteName("azioni_utente") . "=" . $azioneUtente,
                    $db->quoteName("protocollo") . "=" . $db->quote($_POST["protocollo"]),
                    $db->quoteName("privata") . "=" . $privata,
                    $db->quoteName("luogo") . "=" . $db->quote($_POST["luogo"])
                ];
            } else {
                $fields = [
                    $db->quoteName("numero") . "=" . $_POST["numero"],
                    $db->quoteName("data_pubblicazione") . "= CURRENT_TIMESTAMP()",
                    $db->quoteName("data_fine_interazione") . "=" . $dataFineInterazione,
                    $db->quoteName("oggetto") . "=" . $db->quote($_POST["oggetto"]),
                    $db->quoteName("testo") . "=" . $db->quote($_POST["testo"]),
                    $db->quoteName("bozza") . "=" . $bozza,
                    $db->quoteName("azioni_utente") . "=" . $azioneUtente,
                    $db->quoteName("protocollo") . "=" . $db->quote($_POST["protocollo"]),
                    $db->quoteName("privata") . "=" . $privata,
                    $db->quoteName("luogo") . "=" . $db->quote($_POST["luogo"])
                ];
            }

            $this->insertAllegati($id);
            $this->deleteAllegati($id);

            $query
                ->update($db->quoteName("#__com_sos_circolari"))
                ->set($fields)
                ->where("id=$id");
            $this->updateGroups($id);
            $this->executeQuery($db, $query, "La circolare è stata aggiornata con successo");

        } else {
            //Insert

            if ($bozza) {
                $columns = [
                    "oggetto",
                    "testo",
                    "autore",
                    "bozza",
                    "data_fine_interazione",
                    "anno_scolastico",
                    "azioni_utente",
                    "protocollo",
                    "privata",
                    "luogo"
                ];

                $values = [
                    $db->quote($_POST["oggetto"]),
                    $db->quote($_POST["testo"]),
                    $userId,
                    $bozza,
                    $dataFineInterazione,
                    $db->quote($annoScolastico),
                    $azioneUtente,
                    $db->quote($_POST["protocollo"]),
                    $privata,
                    $db->quote($_POST["luogo"])
                ];
            } else {
                $columns = [
                    "numero",
                    "oggetto",
                    "testo",
                    "autore",
                    "bozza",
                    "data_pubblicazione",
                    "data_fine_interazione",
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
                    "CURRENT_TIMESTAMP()",
                    $dataFineInterazione,
                    $db->quote($annoScolastico),
                    $azioneUtente,
                    $db->quote($_POST["protocollo"]),
                    $privata,
                    $db->quote($_POST["luogo"])
                ];
            }

            $query
                ->insert($db->quoteName("#__com_sos_circolari"))
                ->columns($db->quoteName($columns))
                ->values(implode(",", $values));

            $this->executeQuery($db, $query, "La circolare è stata pubblicata con successo");

            $id = $db->insertid();
            $this->insertAllegati($id);
            $this->insertGroups($id);
        }
    }

    protected function executeQuery($db, $query, $message) {
        $this->setRedirect("index.php?option=com_sos_circolari");

        $app = JFactory::getApplication();
        try {
            $db->setQuery($query)->execute();
            $app->enqueueMessage($message);
        }
        catch (Exception $error){
            $app->enqueueMessage($error->getMessage(), "error");
        }
    }

    public function directPublish() {
        $id =  $_POST["publish"];

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->update($db->quoteName("#__com_sos_circolari"))
            ->set($db->quoteName("numero") . "=" . Utilities::getNumero())
            ->set($db->quoteName("bozza") . "=0")
            ->where("id=$id");

        $this->executeQuery($db, $query, "La circolare è stata pubblicata con successo");
    }
}