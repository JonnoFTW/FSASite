<div class="grid_9">
<div class="box">
<?
echo "<h2>{$type}</h2>";
foreach($days as $key => $i){//stick in a link to the results of this comp if curr_date > event_date
	echo "
<div id=\"tables\" class=\"block\">
	<table>
		<colgroup>
			<col class=\"colA\">
			<col class=\"colB\">
			<col class=\"colC\">
			<col class=\"colD\">
		</colgroup>
	<thead>
		<tr>
			<th class=\"table-head\" colspan=\"4\">{$key}</th>
		</tr>
	</thead>
	<tbody>";
	foreach($i as $event){
		echo "<tr class='odd'><th class=\"fixed\">".$event['time']."</th><td class=\"fixed\">".anchor('results/event/'.$event['event_id'],$event['name'])."</td><td class=\"fixed\">{$event['weapon']}</td><td class=\"fixed\">{$event['gender']}</td></tr>";
	}
	echo "
	</tbody>
	</table></div>";
}
?>
</div>
</div>

