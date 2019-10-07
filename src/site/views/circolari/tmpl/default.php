<?php
$pagination = $this->pagination->getListFooter();
$items = $this->items;
?>

<table class="adminlist table">
    <thead class="sosFHeading">
    <th width="15%">
        Data
    </th>
    <th width="50%">
        Oggetto
    </th>
    </thead>
    <tfoot>
    <tr>
        <td colspan="15">
            <div class="sosFrontendPagination"> <?php echo $pagination;?></div>
        </td>
    </tr>
    </tfoot>
    <tbody>
    <?php
    foreach ( $items as $i => $item ) {
        ?>
        <tr class="row<?php echo $i % 2; ?>">
            <td class="">
                <?php echo $item->data_pubblicazione; ?>
            </td>
            <td class="">
                <?php echo $item->oggetto; ?>
            </td>
        </tr>
        <?php
    }
    ;?>
    </tbody>
</table>