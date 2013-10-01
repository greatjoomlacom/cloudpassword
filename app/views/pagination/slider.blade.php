<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

@if($paginator->getLastPage() > 1)
    <div class="text-center pagination-container">
        <ul class="pagination">
            <?php echo $presenter->render(); ?>
        </ul>
        <div class="count-info">
            {{ Lang::get('shared.pagination.filtered', array('filtered' => count($paginator->getCollection()), 'total' => $paginator->getTotal())) }}
        </div>
    </div>
@endif