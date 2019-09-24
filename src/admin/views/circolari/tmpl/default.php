<?php
defined('_JEXEC') or die('Restricted Access');

$document = JFactory::getDocument();
$document->addScript(JURI::root () .'media/com_sos_circolari/js/circolari.js');

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
            <th>Risposte</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($this->items)) : ?>
            <?php foreach ($this->items as $i => $row) : ?>
                <tr>
                    <td align="center">
                        <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_("index.php?option=com_sos_circolari&view=" . ($row->bozza == 1 ? "newcircolare" : "circolare") . "&id=$row->id"); ?>">
                            <?php echo $row->oggetto; ?>
                        </a>
                    </td>
                    <td><?php echo $row->numero ? "$row->numero - $row->anno_scolastico" : "Bozza"; ?></td>
                    <td><?php echo $row->bozza == 1 ? "<input type='button' value='Pubblica' onclick='publishCircolare(" . $row->id .")'/>" : "<a class='grid_true'>"; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->data_pubblicazione; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

