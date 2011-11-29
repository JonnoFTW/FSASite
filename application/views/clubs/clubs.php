<div class="grid_2">
<div class="box">
<h2>Clubs</h2>
<div class="block" id="tables">
		<table>
			<colgroup>
				<col class="colA" />
			</colgroup>
			<tbody>
<?
foreach($club as $i){
	echo "<tr class='odd'><th><a href='#{$i['last_name']}'>{$i['last_name']}</a></th></tr>";	
}
?>
	</tbody>
</table>
</div>
</div>
</div>
<div class="grid_10">
<?
// put in a special div here to hold them all
foreach($club as $i){
	echo "<div id='{$i['last_name']}' class='box'>";
	echo "<h2>{$i['last_name']}</h2>";
    echo "<div class=\"block\">";
	echo "<p>Contact phone: {$i['phone']}</p>";
	echo "<p>Email: ".safe_mailto($i['email'],$i['email'])."</p>";
    echo "Address: <address>{$i['address_1']} </br> ";
    if ($i['address_2']) $i['address_2']."</br>";
    echo "{$i['post_code']} </br> {$i['state']} </address>";
	echo "<p>Description: {$i['description']}</p>";
	echo "</div>";
    echo "</div>";
}
?>
</div>