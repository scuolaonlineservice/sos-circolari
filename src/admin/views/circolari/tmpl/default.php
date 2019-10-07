<?php
defined('_JEXEC') or die('Restricted Access');

$document = JFactory::getDocument();
$document->addScript(JURI::root () .'media/com_sos_circolari/js/circolari.js');
$document->addStyleSheet(JURI::root () .'media/com_sos_circolari/css/icons.css');

?>
<form action="index.php?option=com_sos_circolari&view=circolari" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>
                <?php echo JHtml::_('grid.checkall'); ?>
            </th>
            <th>Oggetto</th>
            <th>Numero circolare</th>
            <th>Pubblicato</th>
            <th>Autore</th>
            <th>Data pubblicazione</th>
            <!--
                Colonna da aggiungere quando si avrà un'idea dei gruppi già esistenti
                (richiede conta degli utenti firmatari, INFO: guardare modulo vecchio)
            th>Risposte</th-->
        </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $row) { ?>
                <tr>
                    <td align="center">
                        <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_("index.php?option=com_sos_circolari&view=" . ($row->bozza == 1 ? "edit" : "circolare") . "&id=$row->id"); ?>">
                            <?php echo $row->oggetto; ?>
                        </a>
                    </td>
                    <td><?php echo $row->numero ? "$row->numero - $row->anno_scolastico" : "Bozza"; ?></td>
                    <td>
                        <?php
                            if ($row->data_fine_interazione) {
                                if (strtotime($row->data_fine_interazione) < time()) {
                                    echo "<a class='grid_expired' title='Risposte scadute'>";
                                } else {
                                    echo "<a class='grid_active' title='Risposte attive'>";
                                }
                            } else if ($row->bozza == 1) {
                                echo "<input type='button' value='Pubblica' onclick='publishCircolare(" . $row->id .")'/>";
                            } else {
                                echo "<a class='grid_true' title='Pubblicato'>";
                            }
                        ?>
                    </td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->data_pubblicazione; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

