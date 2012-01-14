
<div class="grid_9">
<div class="box">
<?
echo heading("Please select an event",2);
if(isset($warning))
    echo $warning;
    
foreach($events as $v)
echo $v;
?>
</div>
</div>
