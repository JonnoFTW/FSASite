
<div class="grid_9">
<div class="box">
<h2>Event Types</h2>

<?
foreach($EVENT_TYPE as $k=>$v) {
    echo heading(anchor("calendar/type/".$k,$v['type']),4);
    echo "<p>{$v['description']}</p>";
}
?>

</div>
