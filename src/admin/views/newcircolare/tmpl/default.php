<?php
defined('_JEXEC') or die('Restricted Access');

$document = JFactory::getDocument();
$document->addScript(JURI::root () .'media/com_sos_circolari/js/functionViews/newcircolare.js');

?>

<style>
    .circolare {
        border: darkgray 1px solid;
        border-radius: 3px;
        width: 35%;
        margin: auto;
        padding: 2%;
    }

    .heading {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr;
        grid-template-areas: "numero numero protocollo" "oggetto oggetto luogo" "autore autore data";
    }

    .heading label {
        font-weight: bold;
    }

    .align-right {
        text-align: right;
    }


    input {
        width: 93%;
        background-color: white !important;
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

    .data { grid-area: data; }

    .oggetto { grid-area: oggetto; }

    .autore { grid-area: autore; }

    label[for="private"], label[for="testo"] {
        margin-top: 9px;
    }

    label[for="draft"], label[for="private"] {
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

    .destinatari-label {
        font-weight: bold;
        margin-top: 9px;
    }

</style>

<form class="circolare" action="index.php?option=com_sos_circolari&view=newcircolare" name="adminForm" enctype="multipart/form-data" method="post" id="adminForm">
    <div class="heading">
        <div class="numero">
            <label for ="numero">Numero</label>
            <input type="text" name="numero" id="numero" required>
        </div>
        <div class="protocollo align-right">
            <label for="protocollo">Protocollo</label>
            <input type="text" class = "align-right" name="protocollo" id="protocollo" required>
        </div>
        <div class="luogo align-right">
            <label for="luogo">Luogo</label>
            <input type="text" class = "align-right" name="luogo" id="luogo" required>
        </div>
        <div class="data align-right">
            <label for="data">Data</label>
            <input type="text" class = "align-right" name="data" id="data" required>
        </div>
        <div class="oggetto">
            <label for="oggetto">Oggetto</label>
            <input type="text" name="oggetto" id="oggetto" required>
        </div>
        <div class="autore">
            <label for="autore">Autore</label>
            <input type="text" name="autore" id="autore" required>
        </div>
    </div>
    <label for="draft">Bozza</label>
    <div class="wrapper">
        Sì <input type="radio" name="draft" value="true"/>
        No <input type="radio" name="draft" value="false" checked/>
    </div>
    <label for="private">Privata</label>
    <div class="wrapper">
        Sì <input type="radio" name="private" value="true"/>
        No <input type="radio" name="private" value="false" checked/>
    </div>
    <p class="destinatari-label">Destinatari</p>
    <div class="wrapper">
        <ul id="destinatari">
            <li><input type="checkbox" name="tutti" onclick="selectAllDestinatari()"><label for="tutti">Tutti</label></li>
            <?php
                $destinatari = array("Insegnanti di Classroom", "Docenti", "PersonaleATA", "PSiG");

                foreach ($destinatari as $group) {
                    echo "<li><input type='checkbox' name=$group><label for=$group>$group</label></li>";
                }
            ?>
        </ul>
    </div>
    <div class = "body">
        <label for="testo">Testo circolare</label>
        <?php
            $editor = & JFactory::getEditor();
            echo $editor->display("testo", null, "100%", 180, 90, 6, false);
        ?>
    </div>
    <div class = "allegati">
        <input type="button" value="Aggiungi allegato" onclick="addAllegato('ciao')" >
    </div>
    <input type="hidden" name="task" value="save"/>
    <?php echo JHtml::_('form.token'); ?>
</form>