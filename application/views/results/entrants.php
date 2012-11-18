
<div class="grid_9">
    <div class="box">
		<?
            echo heading("Entrants for {$name} on {$date}: ",2);
        ?>
        <div class="block">
        <?
            echo $entrants;
        ?>
        
        </div>
        
        <?
            if($logged) {
                echo anchor('admin/results/entry/'.$event_id,'Update results for this event');
            }
          
        ?>
    </div>
</div>