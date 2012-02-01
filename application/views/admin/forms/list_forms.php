<script type="text/javascript">
$(document).ready( function() { 
    // When update form is submitted
    $("form#form_list").submit( function(event) {
        event.preventDefault();
        var forms = {}; // hold all the data from the list of forms
        $("form#form_list  table  tr:not(:first)").each( function() {
            var id = $(this).find('td > a').first().attr("name");
            forms[id] = {}; 
            $(this).find(":input:not(:last)").each( function() {
                forms[id][$(this).attr("name")] = $(this).val();
            });
            forms[id]["delete"] =$(this).find(":input:checked").first().val();
        });
        var del = $("form#form_list  table  tr  td  input:checked").length;
        if(del){
            var answer = confirm('You are about to delete '+del+' forms, is this okay?');
        }
        else {
            var answer = true;
        }
        if(answer) {
            $.ajax({
                url : "<? echo site_url("admin/forms/update"); ?>",
                type : "POST",
                data : {"forms_list": JSON.stringify(forms)},
                success: function(data) {
                    $("#list_report").html(data).hide().fadeIn(1000);
                }
            });
        }
        console.log(forms);
    });
});
</script>

<div class="grid_9">
    <div class="box">
		<?
            echo heading("Available forms are: ",2);
        ?>
        <div  class="block">
        Make your changes to as many files as you wish, then click 'submit'. Note, the "Name" field will be the name of the file when it is downloaded.
        
        <?
            echo '<form id="form_list">';
            echo form_fieldset("Edit Forms");
            echo $forms;
            echo form_submit('Submit','Submit');
            echo '<div id="list_report"></div>';
            echo form_fieldset_close();
            echo form_close();
        ?>
        </div>
		<?
            echo heading("Add a new form: ",2);
        ?>
        <div class="block">
        If you do not specify a name, the name of the uploaded file will be used instead.
        <?
            echo form_open_multipart('admin/forms/add');
            echo form_fieldset("New form");
            echo "<p>";
            echo form_label("Name","name");
            echo form_input("name");
            echo "</p>";
            
            echo "<p>";
            echo form_label("Description","description");
            echo form_input("description");
            echo "</p>";
            
            echo "<p>";
            echo form_label("Type","type");
            echo form_dropdown('type',array("res"=>"Resource","comp"=>"Competion Resources"));
            echo "</p>";
            
            echo "<p>";
            echo form_label("File","userfile");
            echo form_upload('userfile');
            echo "</p>";
            
            echo form_submit('Submit','Submit');
            echo form_fieldset_close();
            echo form_close();
        ?>
        
        </div>        
    </div>
</div>