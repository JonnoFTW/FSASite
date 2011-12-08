<div class="grid_9">
<div class="box">
<style type="text/css">
.hovered {
    background-color:#CCC !important;
    cursor: hand;
    cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#tables tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
    $('tbody tr').hover(
        function() {
            $(this).find("td").addClass("hovered");
        },
        function() {
            $(this).find("td").removeClass("hovered");
        }
    );
});
</script>
<?
echo "<h2>{$type}</h2>";
foreach($days as $key => $i){//stick in a link to the results of this comp if curr_date > event_date
	echo "
<div id=\"tables\" class=\"block events\">
	<table>
		<colgroup>
			<col class=\"colA\">
			<col class=\"colB\">
			<col class=\"colC\">
			<col class=\"colD\">
			<col class=\"colE\">
		</colgroup>
	<thead>
		<tr>
			 <th class=\"table-head\" colspan=\"5\">{$key}</th>
		</tr>
        <tr class=\"odd\">
            <th>Time</th>
            <th>Title</th>
            <th>Weapon</th>
            <th>Gender</th>
            <th>Category</th>
        </tr>
	</thead>
	<tbody>";
	foreach($i as $event){
		echo "<tr class=\"odd\"><td class=\"fixed\">".anchor('results/event/'.$event['event_id'],$event['time'])."</td><td class=\"fixed\">{$event['name']}</td><td class=\"fixed\">{$event['weapon']}</td><td class=\"fixed\">{$event['gender']}</td><td class=\"fixed\">{$event['category']}</td></tr>";
	}
	echo "
	</tbody>
	</table></div>";
}
?>
</div>
</div>

