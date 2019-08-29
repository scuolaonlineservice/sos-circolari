<?php
$possibleAnswers = $this->state->get("possibleAnswers");
$circolare = $this->circolare;
$user = JFactory::getUser();

// Se l'utente e la circolare esistono
if ($user->username != null && $circolare->TemplateOpzioni!=1) {
  echo '<form action="" method="POST" id="answerForm">';

  // Se l'utente non ha già "firmato" la circolare
  if (!$this->isSigned) {
    // Se non è scaduta
    if (date("Y-m-d") < $circolare->DataFineInterazioni) {
      // Se le possibleAnswers sono un array
      if (is_array($possibleAnswers)) {
        // Input per tenere ID della scelta effettuata della scelta effettuata
        echo '<input type="hidden" name="idSceltaEffettuata" id="idSceltaEffettuata">
        <input type="hidden" name="nameSceltaEffettuata" id="nameSceltaEffettuata" value="test">';

        for ($i = 0; $i < count($possibleAnswers); $i++) {
          $currentAnswer = $possibleAnswers[$i];
          $buttonFunctions[$i] = "answerCallback('$currentAnswer', '$i')";
        }

        // Bottoni per le azioni [Presa Visione, Adesione, Non Adesione]
        $out = sosLibLayout::sosInputButtonFields("azioni", "Azioni", "", $possibleAnswers, $buttonFunctions, "style='margin-bottom: .4rem'");

        // Div per la conferma dopo aver premuto su un bottone delle azioni
        $htmlConfirmDiv = JText::_(
            "COM_SOSCIRCOLARI_PROMPT_INTERAZIONE_TEXT1"
          ).'
          <b id="azioneDaConfermare"></b>'.
          JText::_("COM_SOSCIRCOLARI_PROMPT_INTERAZIONE_TEXT2").'
          <input type="button" id="confermaRisposta" value="Conferma" class="sosButtonField" onclick="sendAnswer();" style="margin-bottom: .4rem">
          <input type="button" value="Annulla" class="sosButtonField" onclick="$(\'divConferma-block\').hide(); $(\'azioni-block\').show()">
          ';

        // Div delle conferme
        echo sosLibLayout::sosFieldsContainer(array(
            sosLibLayout::sosDivField("divConferma", "Attenzione", "", $htmlConfirmDiv, "sosWarningDiv")
          ),
          "sosCenterBlock"
        );

        // Nascondere il div delle conferme al caricamento della pagina
        echo "<script>jQuery('#divConferma-block').hide();</script>";
      }
    } else {
      // Se è scaduta
      $expiredText = JText::_("COM_SOSCIRCOLARI_PROMPT_AZIONESCADUTA");
      $expiredDate = UtilityHelper::formatTimeString($circolare->DataFineInterazioni);
      $finalDescription = "<p>$expiredtext<b> $expiredDate </b></p>";
      $out = sosLibLayout::sosDivField("azioni", "Azioni", "", $finalDescription, "sosOutOfDate");
    }
  } else {
    // Se la circolare è già firmata
    $actionDate = UtilityHelper::formatTimeString($this->isSigned->DataRisposta);
    foreach (json_decode($this->isSigned->Risposta) as $key => $value) {
      $actionExecuted = $value;
    }

    $actionConfirmedTextOne = JText::_("COM_SOSCIRCOLARI_PROMPT_AZIONECONFERMATA_TEXT1");
    $actionConfirmedTextTwo = JText::_('COM_SOSCIRCOLARI_PROMPT_AZIONECONFERMATA_TEXT2');
    $finalDescription = "<p>$actionConfirmedText <b>$actionExecuted</b> $actionConfirmedTextTwo <b>$actionDate</b></p>";
    $out = sosLibLayout::sosDivField("azioni", "Azioni", "", $finalDescription, "sosSuccessDiv");
  }

  echo sosLibLayout::sosFieldsContainer(array(
      $out
    ),
    "sosCenterBlock"
  );

  echo "</form>";
} else {
  if (JRequest::getVar('fm') == 1 && $circolare->TemplateOpzioni != 1) {
    $return = $redirectUrl = "index.php".str_replace(JURI::current(), "", JURI::getInstance());
    $loginText = JText::_('COM_SOSCIRCOLARI_PROMPT_ESEGUILOGIN');
    $loginLink = JURI::base().$return;

    $warningDiv = "<p>$loginText <a href='$loginLink'>Clicca Qui</a> per Effettuare il login.</p>";
    echo sosLibLayout::sosFieldsContainer(array(
        sosLibLayout::sosDivField("attenzione", "Attenzione", "", $warningDiv, "sosWarningDiv")
      ),
      "sosCenterBlock"
    );
  }
}