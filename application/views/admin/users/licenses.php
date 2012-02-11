<div class="grid_9">
<div class="box">
<script type="text/javascript">
$(document).ready(function() {
    //add index column with all content.
    $(".filterable tr:has(td)").each(function(){
        var t = $(this).text().toLowerCase(); //all row text
        $("<td class='indexColumn'></td>").hide().text(t).appendTo(this);
    });//each tr
    $("#filterText").keyup(function(){
        var s = $(this).val().toLowerCase().split(" ");
        //show all rows.
        $(".filterable tr:hidden").show();
        $.each(s, function(){
            $(".filterable tr:visible .indexColumn:not(:contains('"+ this + "'))").parent().hide();
        });
    });
    $("form").submit( function (event) {
        event.preventDefault();
        // Will only send those users who have been updated
        var fencers = [];
        $("form > table > tbody > tr").each( function() {
            var t = $(this);
            if(t.children().find('.changed').length){
                var f = {'uid':t.find('input[name="uid"]').val(),
                         'type':t.find("select").val(),
                         'licensed':t.find(":checkbox").is(":checked") };
                fencers.push(f);
            }
        });
        console.log(fencers);
        $.ajax({
            url: '<? echo site_url('/admin/user/update_licenses'); ?>',
            type: 'POST',
            data: {'data':JSON.stringify(fencers)},
            success: function(data) {
                $("#report").html(data).hide().fadeIn(1000);
            }
        });
    });
    //add index column with all content.
    $(".filterable tr:has(td)").each(function(){
        var t = $(this).text().toLowerCase(); //all row text
        $("<td class='indexColumn'></td>").hide().text(t).appendTo(this);
    });//each tr
    $("#filterText").keyup(function(){
        var s = $(this).val().toLowerCase().split(" ");
        //show all rows.
        $(".filterable tr:hidden").show();
        $.each(s, function(){
            $(".filterable tr:visible .indexColumn:not(:contains('"+ this + "'))").parent().hide();
        });
    });
    $(":input").change( function() {
        if(!$(this).hasClass("changed")) {
            $(this).addClass("changed");
        }
   });
});
</script>
<noscript>Please enable javascript to enable the search and update features</noscript>
<h2>Search Users</h2>
<div class="block">
    <label for="filter">Search users</label>
    <input name="filterText" id="filterText" type="text"/>
</form>
</div>
<div class="block filterable">

<form>
<p>
<?
echo heading("Registered people's licenses",2); 
echo $user_table;
?>
<label for="submit">Save changes</label>
<? echo form_submit('save','Save'); ?>
</p>
</form>
<div id="report"></div>
</div>
</div>
</div>
