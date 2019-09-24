<?php
defined('_JEXEC') or die('Restricted Access');
$risposte = $this->items;
?>

<style>
    .circolare {
        border: darkgray 1px solid;
        border-radius: 3px;
        width: 45%;
        margin: auto;
        padding: 2%;
    }

    .numero { grid-area: numero; }

    .protocollo { grid-area: protocollo; }

    .oggetto { grid-area: oggetto; }

    .autore { grid-area: autore; }

    .luogo { grid-area: luogo; }

    .data { grid-area: data; }


    .heading {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr;
        grid-template-areas: "numero numero protocollo" "oggetto oggetto luogo" "autore autore data";
    }

    label {
        font-weight: bold;
        margin-top: 9px;
    }

    .align-right {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    input {
        width: 93%;
        background-color: white !important;
    }

    .box {
        width: 99%;
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 4px 6px;
        height: auto;
    }

    .high {
        height: 60vh;
        overflow: auto;
    }


    .body {
        margin-bottom: 5px;
    }

    .allegati-list {
        margin-bottom: 0;
        padding: 0.3em;
    }

    .answerable {
        background-color: palegreen;
        padding: 4px 6px;
    }

    .not-answerable {
        background: #ffc6c4;
        padding: 4px 6px;
    }

    .end-date-wrapper {
        padding: 0 !important;
        margin-top: 18px;
    }

    .numero { grid-area: numero; }

    .protocollo { grid-area: protocollo; }

    .luogo { grid-area: luogo; }

    .data { grid-area: data; }

    .oggetto { grid-area: oggetto; }

    .autore { grid-area: autore; }
</style>

<div class = "circolare">
    <div class="heading">
        <div class="numero">
            <label>Numero</label>
            <input type="text" value="<?php echo $this->circolare->numero; ?>" readonly>
        </div>
        <div class="protocollo align-right">
            <label>Protocollo</label>
            <input type="text" class = "align-right" value="<?php echo $this->circolare->protocollo; ?>" readonly>
        </div>
        <div class="luogo align-right">
            <label>Luogo</label>
            <input type="text" class = "align-right" value="<?php echo $this->circolare->luogo; ?>" readonly>
        </div>
        <div class="data align-right">
            <label>Data</label>
            <input type="text" class = "align-right" value="<?php echo $this->circolare->data_pubblicazione; ?>" readonly>
        </div>
        <div class="oggetto">
            <label>Oggetto</label>
            <input type="text" value="<?php echo $this->circolare->oggetto; ?>" readonly>
        </div>
        <div class="autore">
            <label>Autore</label>
            <input type="text" value="<?php echo $this->circolare->name; ?>" readonly>
        </div>
    </div>
    <div class = "body">
        <label>Testo circolare</label>
        <div class="box high"><?php echo $this->circolare->testo; ?></div>
    </div>
    <?php if (isset($this->circolare->allegati)): ?>
        <label>Allegati</label>
        <div class="box">
            <ul class="allegati-list">
                <?php
                    foreach ($this->circolare->allegati as $allegato) {
                        echo "<li>$allegato</li>";
                    }
                ?>
            </ul>
        </div>
    <?php endif ?>
    <?php if ($this->circolare->data_fine_interazione):?>
        <div class="box end-date-wrapper align-center">
            <?php
                $timestamp = strtotime($this->circolare->data_fine_interazione);
                echo $timestamp < time() ?
                    '<div class="not-answerable"> Non è possibile rispondere alla circolare dalla data <strong>' . date("d/m/Y", $timestamp) . '</strong></div>'
                :
                    '<div class="answerable">È possibile rispondere alla circolare fino alla data <strong>' . date("d/m/Y", $timestamp) . '</strong></div>'
            ?>
        </div>
        <label>Risposte</label>
        <div class="box">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nome</th>
                        <th>Risposta</th>
                        <th>Data risposta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($risposte as $risposta) {
                            echo implode("", [
                                "<tr>",
                                    "<td>$risposta->username</td>",
                                    "<td>$risposta->name</td>",
                                    "<td>$risposta->azione</td>",
                                    "<td>$risposta->data_risposta</td>",
                                "</tr>"
                            ]);
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>