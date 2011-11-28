<div class="grid_3">
<div class="box">
<h2>Calendars</h2>
<div class="block" >
<h3>Event Types </h3>
<ul>
<?
$res = array("Events","Local","National","Robyn Chaplin");
foreach($res as $i){
	echo "<li>".anchor("calendar/type/".url_title($i),$i)."</li>\n";	
}
echo "</ul><h3>View local timetable for year:</h3></th></tr>\n<ul>\n";
foreach($years as $i){
    echo "<li>".anchor("calendar/year/".$i['year'],$i['year'])."</li>\n";
}
?>
</ul>


</div>
</div>
</div>