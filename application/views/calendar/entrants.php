<div class="grid_9">
<div class="box">
Current entrants for <? echo $event['name'] ?> are:

<?
    foreach($entrant as $i) {
        echo anchor("results/users/{$i['uid']}","{$i['first_name']} {$i['last_name']}");
    }
?>

</div>
</div>