<?php
    $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>
<?php if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination">
        <?php

        	/* How many pages need to be shown before and after the current page */
        	$showBeforeAndAfter = 5;

  			/* Current Page */
        	$currentPage = $paginator->getCurrentPage();
        	$lastPage = $paginator->getLastPage();


        	/* Check if the pages before and after the current really exist */
        	$start = $currentPage - $showBeforeAndAfter;      	

        	if($start < 1){
        		
        		$diff = $start - 1;

        		$start = $currentPage - ($showBeforeAndAfter + $diff);
        	}


        	$end = $currentPage + $showBeforeAndAfter;

        	if($end > $lastPage){

        		$diff = $end - $lastPage;
        		$end = $end - $diff;
        	}

            echo $presenter->getPrevious('&lt;&lt;');



           	echo $presenter->getPageRange($start, $end);

            echo $presenter->getNext('&gt;&gt;');
        ?>
    </ul>
<?php endif; ?>