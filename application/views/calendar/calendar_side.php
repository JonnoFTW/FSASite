<div class="grid_3">
<div class="box">
<?
echo heading("Calendars",2);
echo "<div id=\"list-items\" class=\"block\">";
echo heading("Event Types",5);
$res = array(
    anchor("calendar/type/Events","Events"),
    anchor("calendar/type/Local","Local"),
    anchor("calendar/type/National","National"),
    anchor("calendar/type/Robyn-Chaplin","Robyn Chaplin")
);
echo ul($res,array('class'=>'menu'));
echo heading("View local timetable for year:",5);

foreach($years as &$i){
    $i = anchor("calendar/year/".$i['year'],$i['year']);
}
echo ul($years,array('class'=>'menu'));
?>

</div>
</div>
</div>
