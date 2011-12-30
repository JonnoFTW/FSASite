<div class="grid_9">
<div class="box">
<style type="text/css">
.hovered {
    background-color:#CCC;
    cursor: hand;
    cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#users tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
    $('tbody tr').hover(
        function() {
            $(this).find("td").addClass("hovered");
        },
        function() {
            $(this).find("td").removeClass("hovered");
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
});
</script>
<noscript>Please enable javascript to enable the search feature</noscript>
<h2>Search Users</h2>
<div class="block">
<form>
    <label for="filter">Search users</label>
    <input name="filterText" id="filterText" type="text"/>
</form>
</div>
<div class="block filterable">
<?
echo heading("Current users are",2); 
echo $user_table;

?>
</div>
</div>
</div>
