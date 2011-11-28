<div class="grid_12">
<div class="box">
	<h2>
		<a href="#" id="toggle-tables">Forms</a>
	</h2>
<div class="block" id="tables">
		<table>
			<colgroup>
				<col class="colA" />
				<col class="colB" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="3" class="table-head">Resources</th>
				</tr>
				<tr>
					<th>Link</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
<?
function form($i){
	echo "<tr class=\"odd\">
	<th class=\"fixed\">".anchor('assets/documents/'.$i['link'],$i['name'])."</th><td>{$i['description']}</td></tr>";
}
foreach($res as $i){form($i);}
 ?>
			</tbody>
		</table>
		<table>
			<colgroup>
				<col class="colA" />
				<col class="colB" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="3" class="table-head">Competition Resources</th>
				</tr>
				<tr>
					<th>Link</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
<?
foreach($comp as $i){
	form($i) ;
}
 ?>
 			</tbody>
		</table>
	</div>
</div>