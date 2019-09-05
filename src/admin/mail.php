<?php

defined('_JEXEC') or die;
if(!class_exists('CircolariDebugHelper')) {
    require JPATH_COMPONENT_ADMINISTRATOR.'/helpers/debug.php';
}


/**
 * Users component helper.
 *
 * @since  1.6
 */
class MailHelper{
    /*
     * Invia l'email dopo aver creato una circolare
     * La circolare è passata come argomento della funzione
    */
    public static function sendNewsletterEmail($newsletter) {
        // Dichiarazione delle variabili che serviranno
        $currentDate = date("d/m/Y");
        $id = $newsletter["IdCircolare"];
        $number = $newsletter["NumeroCircolare"];
        $actionNeeded = $newsletter["azioneRichiesta"];
        $subject = $newsletter["oggetto"];
        $expireDate = $newsletter["dataScadenza"];
        $formattedExpireDate = UtilityHelper::formatTimeString($expireDate);
        $recipients = $newsletter["gruppiDestinatari"];
        $href = JURI::root()."index.php?option=com_sos_circolari&view=Dettagli&Id=$id&fm=1";

        $replyTitle = "";
        $replyBody = "";

        // Se la circolare richiede una risposta da parte del personale a cui viene inviata
        if ($actionNeeded != 1) {
            $replyTitle = "É richiesta risposta entro il $formattedExpireDate";
            $replyBody = "É richiesta risposta entro il <b>$formattedExpireDate</b>.";
        }

        // Titolo della circolare e dell'email
        $title = "Circolare N. $number: $subject $replyTitle";

        // Oggetto della circolare e dell'email
        $body = "In data <b>$currentDate</b> è stata pubblicata la circolare N.<b>$number: $subject</b>.<br> <a href='$href'>Clicca qui</a> per visualizzare e rispondere alla circolare.<br> $replyBody <hr><i>Questo messaggio è stato generato automaticamente.<br>Per dare risposta cliccare sul link sopra indicato.<br>Non rispondere a questa mail.</i>";

        // Invia l'email a tutto il personale che fa parte del gruppo selezionato nella circolare
        self::sendMailToGroup($title, $body, $recipients);
    }

    /**
     * @param $circolare
     */
    public static function generateAndSandMail($circolare) {

        $href = JURI::root () . 'index.php?option=com_sos_circolari&view=Dettagli&Id=' . $circolare ["IdCircolare"] . '&fm=1';
        $richiestaRispostaTitolo = '';
        $richiestaRispostaCorpo = '';
        $aggiuntaRisposta = '';

        if ($circolare ['azioneRichiesta'] != 1) {
            $richiestaRispostaTitolo = '. è richiesta risposta entro il ' . UtilityHelper::formatTimeString ( $circolare ['dataScadenza'] );
            $richiestaRispostaCorpo = 'è richiesta risposta entro il <b>' . UtilityHelper::formatTimeString ( $circolare ['dataScadenza'] ) . '.</b>';
            $aggiuntaRisposta = 'e rispondere al';
        }
        $titolo = 'Circolare N.' . $circolare ["NumeroCircolare"] . ': ' . $circolare ["oggetto"] . $richiestaRispostaTitolo;
        $corpo = 'In data <b>' . date ( "d/m/Y" ) . '</b> è stata pubblicata la circolare N.<b>' . $circolare ['NumeroCircolare'] . ': ' . $circolare ['oggetto'] . '</b>.<br>' . '<a href="' . $href . '">Clicca qui</a> per visualizzare ' . $aggiuntaRisposta . 'la circolare.<br>' . $richiestaRispostaCorpo . '<hr><i>Questo messaggio � stato generato automaticamente.<br>Per dare risposta cliccare sul link sopra indicato.<br>Non rispondere a questa mail.</i>';

        self::sendMailToGroup ( $titolo, $corpo, $circolare ["gruppiDestinatari"] );
    }

    /**
     * @param $circolare
     */
    public static function sendMailDeletedCircolari($circolare) {

        $titolo = "Circolare " . $circolare->NumeroCircolare . " eliminata";
        $corpo = "La circolare " . $circolare->Oggetto . " è stata Eliminata";

        self::sendMailToGroup ( $titolo, $corpo, $circolare->GruppiDestinatari);
    }


    /**
     * @param $subject
     * @param $body
     * @param $groupId
     * @param bool|false $recursive
     * @param null $sender
     */
    public static function sendMailToGroup($subject, $body, $groupId, $recursive=false, $sender = null)
    {
        $googleGroupEmails = UtilityHelper::getGroupMailById($groupId);
        self::sendMail($subject, $body, $googleGroupEmails, $sender);
    }

    /**
     * @param $subject
     * @param $body
     * @param $userIdOrName
     * @param null $sender
     */
    public static function sendMailToUser($subject, $body, $userIdOrName, $sender = null)
    {
        if (!is_array($userIdOrName)) $userIdOrName=array($userIdOrName);

        foreach ($userIdOrName as $key=>$value){
            sleep(2);
            $user = JFactory::getUser($value);
            $recipient[] = $user->email;
        }
        self::sendMail($subject, $body, $recipient, $sender);
    }







    /**
     * Invia una mail ad uno o pi� "$recipient" che quindi pu� essere un array di mail o una singola mail.
        "$sender" � un array di 2 valori, al primo campo va la mail, al secondo il nome con il quale sar�
        visualizzata. Se non specificato questi due valori saranno presi da quanto specificato nel config del sito.
     *
     * @param $subject
     * @param $body
     * @param $recipient
     * @param null $sender
     */
    public static function sendMail($subject, $body, $recipient, $sender = null)
    {
        $mailer = JFactory::getMailer();

        if($sender == null)
        {
            $config = JFactory::getConfig();
            $sender = array(
                $config->get( 'config.mailfrom' ),
                $config->get( 'config.fromname' )
            );

        }

        $mailer->setSender($sender);
        $mailer->addBcc($recipient);

        $mailer->setSubject($subject);

        $mailer->isHTML(true);
        $mailer->Encoding ='base64';
        $mailer->setBody($body);
        $result = $mailer->Send();

        if(!$result){
            CircolariDebugHelper::printError('sendedMail.log', 'Email non Inviata a '.json_encode($recipient));
        }
    }
}