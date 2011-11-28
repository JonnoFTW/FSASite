<div class="grid_12">
<div class="box">
	<h2>
		<a href="#" id="toggle-tables">Todos</a>
	</h2>
    <div class="block" id="login-forms">
    <? echo form_open('todo/finished');?>
    <fieldset class="login">
    <legend>Todo</legend>
    <div class="block" id="tables">
		<table>
			<colgroup>
				<col class="colA" />
				<col class="colB" />
				<col class="colC" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="3" class="table-head">Things that need to be done</th>
				</tr>
				<tr>
					<th>Description</th>
					<th>Done?</th>
					<th>Finished</th>
				</tr>
			</thead>
			<tbody>
<?
function form($i){
	echo "<tr class=\"odd\">
	<th class=\"fixed\">{$i['todo']}</th><td>{$i['done']}</td><td><input type='checkbox' name='{$i['id']}'/></td></tr>";
}
foreach($todo as $i){form($i);}
 ?>
			</tbody>
		</table>
        <p><label>The magic word</label><input type="password" name="pass"/></p>
        <p><label>New item</label><input type="text" name="new"/></p>
        <?
        echo form_submit(array('class'=>'button','name'=>'Submit'),'Submit');
        echo "<h3>{$warn}</h3>";
        ?>
        </form>
    </div>
	</div>
</div>