<div class="grid_3">
<div class="box">
<h2>Search Competition Results</h2>
<div class="block" id="login-forms">
	<? echo form_open('results/search');?>
		<fieldset class="login">
			<legend>Search by Fencer</legend>
            <p>
                <?
                    echo form_label('Name','name');
                    echo form_input('name');
                ?>
            </p>
			<legend>Search by Event</legend>
			<p> 
                <?
                    echo form_label('Category','category');
                    echo form_dropdown('category',$CATEGORIES);
                ?>
				
			</p>
			<p>                
                <?
                    echo form_label('Weapon','weapon');
                    echo form_dropdown('weapon',$WEAPONS);
                ?>
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