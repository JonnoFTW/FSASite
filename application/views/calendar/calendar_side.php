<div class="grid_3">
<div class="box">
<?
echo heading("Calendars",2);
echo "<div id=\"list-items\" class=\"block\">";
echo heading("Event Types",5);
$res = array();
foreach($this->data['EVENT_TYPE'] as $k=>$v){
    $res[] = anchor("calendar/type/".$k,$v['type']);
}
echo ul($res,array('class'=>'menu'));
echo heading("View local timetable for year:",5);

foreach($years as &$i){
    $i = anchor("calendar/type/Local/".$i['year'],$i['year']);
}
echo ul($years,array('class'=>'menu'));
?>

</div>
</div>
</div>
