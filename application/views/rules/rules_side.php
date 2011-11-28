<div class="grid_3">
<div class="box">
<h2>Rules</h2>
<div class="block" id="tables">
		<table>
			<colgroup>
				<col class="colA" />
			</colgroup>
			<tbody>
<?

foreach($side as $i){
	echo "<tr class='odd'><th>".anchor('rules/type/'.url_title($i['title']),$i['title'])."</th></tr>";	
}
?>
	</tbody>
</table>

</div>
</div>
<div class="box">
<h2>Venue</h2>
<div class="block" >
<p>
All competitions are held at Scotch College, unless otherwise stated.</br>
McBean Sports Centre</br>
Enter via Blythewood Rd</br>
Torrens Park, SA</br>
</p><center>
<? echo img('assets/images/sponsor1.png');?></center>
</div>
</div>
</div>