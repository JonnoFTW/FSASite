
<div class="grid_9">
<div class="box">
<?
echo heading("Please select an event to add results for",2);
if(isset($warning))
    echo $warning;
foreach($events as $v) {
    echo heading(anchor('admin/result_entry/'.$v['event_id'],"{$v['name']} {$v['date']}"),3);
}
?>
</div>
</div>
