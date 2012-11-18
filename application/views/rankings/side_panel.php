<div class="grid_3">
<div class="box">
<h2>Find Rankings</h2>
<div class="block" id="login-forms">
    <form id="ranks">
		<fieldset class="login">
			<legend>Select Category</legend>
			<p>                
                <?
                    echo form_label('Weapon','weapon');
                    echo form_dropdown('weapon',array_merge(array(''=>''),$WEAPONS));
                ?>
			</p>
			<p>
				<?
                    echo form_label('Category','Category');
                    echo form_dropdown('weapon',array_merge(array(''=>''),$CATEGORIES));
                ?> 
			</p>
            <p>
				<?
                    echo form_label('Year','year');
                    echo form_dropdown('year',$years,max($years));
                ?>
			</p>
            <p>
				<?
                    echo form_label('Gender','gender');
                    echo form_dropdown('gender',array_merge(array(''=>''),$GENDERS));
                ?>
			</p>
			<input class="button" type="submit" value="Search" />
            <script>
            $('form#ranks').submit( function(e) {
                e.preventDefault();
                var weapon =   $('select:eq(0)');
                var category = $('select:eq(1)');
                var year =     $('select:eq(2)');
                var gender =   $('select:eq(3)');
                if(year.val() && weapon.val() && category.val() && gender.val()) {
                    window.location ='<? echo base_url();?>rankings/rank/'+year.val()+'/'+weapon.val()+'/'+category.val()+'/'+gender.val();
                } else {
                    var empty = '#FF6B6B';
                    for(i in [year,weapon,gender]) {
                        if(!i.val())
                            i.css('background-color',empty);
                        else
                            i.css('background-color','#fff');
                    }
                    return false;
                }
            });
            </script>
		</fieldset>
	</form>
</div>
</div>
</div>