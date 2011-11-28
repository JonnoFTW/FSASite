
<div class='grid_9'>
<?
foreach($side as $i){
	echo "<div class=\"box\">";
	echo "<h2>".anchor('rules/type/'.$i['title'],$i['title'])."</h2>{$i['brief']}";	
	echo "</div>";
}
?>
</div>

