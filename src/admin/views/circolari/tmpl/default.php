<?php
defined('_JEXEC') or die('Restricted Access');
?>

<form action="index.php?option=com_sos_circolari&view=circolari" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>
                <?php echo JHtml::_('grid.checkall'); ?>
            </th>
            <th>Oggetto</th>
            <th>Autore</th>
            <th>Anno scolastico</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($this->items)) : ?>
            <?php foreach ($this->items as $i => $row) : ?>
                <tr>
                    <td><?php echo $row->numero; ?></td>
                    <td align="center">
                        <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_sos_circolari&task=helloworld.edit&id=' . $row->id); ?>">
                            <?php echo $row->oggetto; ?>
                        </a>
                    </td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->anno_scolastico; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>