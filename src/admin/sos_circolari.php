<?php
/*
$circolare = [
    "oggetto" => "test",
    "numero" => $bozza ? "NULL" : 8,
    "protocollo" => "4499/C",
    "luogo" => "Reggio Emilia",
    "bozza" => false,
    "id_utenti" => [951],
    "id_gruppi" => [1],
    "azioni_utente" => 1,
    "testo" => "testtestprova",
    "allegati" => [
        0 => [
            "nome" => "AllegatoDiProva",
            "url" => "link-AllegatoDiProva"
        ]
    ],
    "privata" => 1,
    "data_pubblicazione" => $bozza ? str_replace("-","",date("Y-m-d")) : "NULL"
];
*/

defined('_JEXEC') or die('Restricted access');

$controller = JControllerLegacy::getInstance('SosCircolari');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

$controller->redirect();