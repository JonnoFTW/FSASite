<div class="grid_3">
<div class="box">
<h2>Search Competition Results</h2>
<div class="block" id="login-forms">
	<? echo form_open('results/search');?>
		<fieldset class="login">
			<legend>Search</legend>
			<p>Items can be left blank</p>
			<p>
				<label>Category</label>
				<select name="category">
				<? foreach(array("","U11","U13","U15","U17","U20","Novice","Intermediate","Open","Veteran") as $i){echo "<option value='{$i}'>{$i}</option>";} ?>
				</select>
				
			</p>
			<p>
				<label>Weapon: </label>
				<select name="weapon">
				<? foreach(array("","Foil","Epee","Sabre") as $i){echo "<option value='{$i}'>{$i}</option>";} ?>
				</select>
			</p>
			<p>
				<label>Date from:</label>
				<script>
				$(function() {
						$( "#from" ).datepicker();
					});
				</script>
				<input id="from" name="from" type="text">
			</p>
			<p>
				<label>Date to:</label>
				<script>
				$(function() {
					$( "#to" ).datepicker();
				});
				</script>
				<input id="to" name="to" type="text">
			</p>
			<input class="button" type="submit" value="Search" />
		</fieldset>
	</form>
</div>
</div>
</div>