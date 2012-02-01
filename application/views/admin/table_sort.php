<style type="text/css">
th.headerSortUp,th.headerSortDown{ 
    background-color: #555; 
    border: 1px solid black;
    color: #eff;
    
}
th.header { 
    cursor: pointer; 
    font-weight: bold; 
    padding-left: 20px; 
    border-right: 1px solid #dad9c7; 
    margin-left: -1px; 
}
.hovered {
    background-color:#CCC !important;
    cursor: hand;
    cursor: pointer;
}

</style>
<script type="text/javascript" src="<? echo base_url().'assets/scripts/jquery.tablesorter.min.js'; ?>"></script> 

<script type="text/javascript">
$(document).ready( function () {
    $("table").tablesorter({
        sortList: [[0,1]],
        textExtraction: function(node) {
            var n = $(node);
         //   console.log(n);
            if(n.has(":checkbox").length){ 
                var val = Boolean(n.find(":checkbox").is(":checked"))
              //  console.log("This is a checkbox value:")+val;
                return String(val);
            }
            if(n.has(":input").length) {
              //  console.log(n.find(":input").val());
                return n.find(":input").val();
            } else{
               // console.log(n.text());
                return n.text();
            }
        }
    });
    $(":checkbox").click( function () {
        $("table").trigger("update");
    });
    
    $('tbody tr').hover(
        function() {
            $(this).find("td").addClass("hovered");
        },
        function() {
            $(this).find("td").removeClass("hovered");
        }
    );
});
</script>
