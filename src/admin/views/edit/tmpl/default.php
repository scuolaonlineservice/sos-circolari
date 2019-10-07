<?php
defined('_JEXEC') or die('Restricted Access');

$document = JFactory::getDocument();
$document->addScript(JURI::root () .'media/com_sos_circolari/js/edit.js');

$new = isset($this->circolare) ? false : true;
?>

<style>
    .circolare {
        border: darkgray 1px solid;
        border-radius: 3px;
        width: 45%;
        margin: auto;
        padding: 2%;
    }

    .heading {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        grid-template-areas: "numero numero protocollo" "oggetto oggetto luogo";
    }

    .heading label, label[for="action"], label[for="data-fine-interazione"] {
        font-weight: bold;
    }

    select {
        width: 100%;
    }


    .align-right {
        text-align: right;
    }


    input {
        width: 93%;
        background-color: white !important;
    }

    input[name="numero"] {
        background-color: #eee !important;
    }

    textarea {
        width: 99%;
        height: 50vh;
        resize: none;
        background-color: white !important;
    }

    .numero { grid-area: numero; }

    .protocollo { grid-area: protocollo; }

    .luogo { grid-area: luogo; }

    .oggetto { grid-area: oggetto; }

    label[for="private"], label[for="testo"] {
        margin-top: 9px;
        font-weight: bold;
    }

    .radio-buttons label {
        font-weight: bold;
    }

    .wrapper {
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 2%;
    }

    ul {
        list-style: none;
        padding: 0px;
        margin: 0px;
    }

    ul input[type="checkbox"] {
        float: left;
        width: 25px;
        height: 25px;
    }

    ul label {
        padding: 1%;
    }

    .external-label {
        font-weight: bold;
        margin-top: 9px;
    }

    .new-allegati {
        margin-top: 5%;
        display: none;
    }

    .allegati-wrapper li {
        border: 1px #ccc solid;
        padding: 0.3em 0.5em;
        margin-bottom: 1em;
    }

    .allegati-wrapper li input {
        float: right;
    }

    input[type="file"] {
        display: none;
    }

    .toggle-editor {
        display: none;
    }

    .date-picker {
        display: none;
    }

</style>

<form class="circolare" action="index.php?option=com_sos_circolari&view=edit" name="adminForm" enctype="multipart/form-data" method="post" id="adminForm">
    <div class="heading">
        <div class="numero">
            <label for ="numero">Numero</label>
            <input type="text" name="numero" id="numero" value="<?php echo $this->numero?>" readonly>
        </div>
        <div class="protocollo align-right">
            <label for="protocollo">Protocollo</label>
            <input type="text" class="align-right" name="protocollo" id="protocollo" value="<?php echo $new ? "" : $this->circolare->protocollo?>">
        </div>
        <div class="luogo align-right">
            <label for="luogo">Luogo</label>
            <input type="text" class = "align-right" name="luogo" id="luogo" value="<?php echo $new ? "" : $this->circolare->luogo?>">
        </div>
        <div class="oggetto">
            <label for="oggetto">Oggetto</label>
            <input type="text" name="oggetto" id="oggetto" value="<?php echo $new ? "" : $this->circolare->oggetto?>">
        </div>
    </div>
    <div class="radio-buttons">
        <label for="draft">Bozza</label>
        <div class="wrapper">
            Sì <input type="radio" name="draft" value="true" <?php echo $new ? "" : "checked"?>/>
            No <input type="radio" name="draft" value="false" <?php echo $new ? "checked" : ""?>/>
        </div>
        <label for="private">Privata</label>
        <div class="wrapper">
            Sì <input type="radio" name="private" value="true" <?php echo $new ? "" : $this->circolare->privata == 1 ? "checked" : ""?>/>
            No <input type="radio" name="private" value="false" <?php echo $new ? "checked" : $this->circolare->privata == 0 ? "checked" : "" ?>/>
        </div>
    </div>
    <p class="external-label">Destinatari</p>
    <div class="wrapper">
        <ul id="destinatari">
            <li><input type="checkbox" name="tutti" onclick="selectAllDestinatari()"><label for="tutti">Tutti</label></li>
            <?php
                $filteredGroups = array_filter($this->gruppiDestinatari, function($group) {
                    return !in_array($group["id"], $this->excludedGroups);
                });

                foreach ($filteredGroups as ["id" => $id, "title" => $title]) {
                    $checked = in_array($id, $this->selectedGroups) ? "checked" : "";
                    echo implode("", [
                        "<li>",
                            "<input type='checkbox' name='group-$id' $checked>",
                            "<label for=group-$id>$title</label>",
                        "</li>"
                    ]);
                }
            ?>
        </ul>
        <label for="action">Azione richiesta (tipo di risposta richiesto ai destinatari della circolare)</label>
        <select name="action" onchange="onActionChange()">
            <?php
                foreach($this->azioniUtente as $row) {
                    echo "<option id='azione-$row->id' ". ($new ? "" : ($this->circolare->azioni_utente == $row->id ? "selected" : "")) . ">$row->azione</option>";
                }
            ?>
        </select>
        <div class="date-picker">
            <label for="data-fine-interazione">Data di scadenza (data entro la quale l'utente può rispondere alla circolare)</label>
            <?php echo JHTML::calendar($this->circolare->data_fine_interazione, "data-fine-interazione", NULL, "%d-%m-%Y"); ?>
        </div>
    </div>
    <div class = "body">
        <label for="testo">Testo circolare</label>
        <?php
            $editor = & JFactory::getEditor();
            echo $editor->display("testo", ($new ? "" : $this->circolare->testo), "100%", 180, 90, 6, false);
        ?>
    </div>
    <p class="external-label">Allegati</p>
    <div class="allegati-wrapper wrapper">
        <?php
            if (isset($this->allegati)) {
                echo "<ul class=\"existing-allegati-list\">";
                    foreach ($this->allegati as $allegato) {
                        echo "<li id='allegato-$allegato->id'>$allegato->nome" . '<input type="button" value="X" onclick="addAllegatoToRemoveList(\'allegato-' . $allegato->id . '\')"></li>';
                    }
                echo "</ul>";
                echo "<ul class='allegati-to-delete'></ul>";
            }
        ?>
        <ul class="new-allegati-list"></ul>
        <div class="new-allegati"></div>
        <input type="button" value="Aggiungi allegato" onclick="addAllegato()">
    </div>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="selected-action" value="azione-0"/>
    <?php echo $new ? "" : '<input type="hidden" name="id" value="' . $this->circolare->id . '">'?>
    <?php echo $new ? "" : '<input type="hidden" name="allegatiToDelete" value="[]">'?>
    <?php echo JHtml::_('form.token'); ?>
</form>