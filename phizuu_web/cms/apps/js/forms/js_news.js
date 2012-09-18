$(document).ready(function() {
    
    $("#newsSortable").sortable({
        handle : '.dragHandle'
    });

    //$("#tourSortable").disableSelection();
    /*$('#tourSortable').sortable({
        update: function(event, ui) { alert('er'); }
    });*/
    $('#newsSortable').bind('sortupdate', function(event, ui) {
        $("#newsSortable").sortable( 'disable' );
        $("#newsSortable").css('cursor', 'wait');
        $("#newsSortable .dragHandle").css('cursor', 'wait');
        var order = $('#newsSortable').sortable('serialize');
        $.post('../../../controller/news_all_controller.php?action=order&'+order, function(data) {
            $("#newsSortable").sortable( 'enable' );
            $("#newsSortable .dragHandle").css('cursor', 'move');
            $("#newsSortable").css('cursor', '');
        });


    });


    // wait for form submission
    $("#form").submit(function() {
        // get the input element and text
        var name = $("#title");
        var date = $("#date");
        var notes = $("#notes");
        var count = $("#count");
	
        var txt_name = name.val();
        var txt_date = date.val();
        var txt_notes = notes.val();
        var txt_count = count.val();
        var txt_count1 =parseInt(txt_count)+1;
        
        // check if text was entered
        if(txt_name.length > 0) {

           
            //post data to process.php and get json
            $.post('../../../controller/news_newline_controller.php', {
                name: txt_name,
                date: txt_date,
                notes: txt_notes
            }, function(data) {
                var element = $('<li>' + data.text + '</li>');
                //element.prependTo("#queue").slideDown();
                element.appendTo("#newsSortable").slideDown();
                // clear input field
                name.val('');
                date.val('');
                notes.val('');
                count.val(txt_count1);
                document.getElementById('buttonContainer').style.display="none";


                showEdits();
                            
            }, 'json');

        }

        // prevent default form action
        return false;
    });


    showEdits();
	 

});


function calendar(btn,id,div_id){
    var cal = Calendar.setup({
        onSelect: function(cal) {
            cal.hide()
            var selectionObject = cal.selection;
            var selectedDate = selectionObject.get();
            if(selectedDate != ""){

 
                var date=selectionObject.print("%Y-%m-%d");
                $.post('../../../controller/news_inline_controller.php', {
                    id: div_id,
                    value: date
                }, function(data) {
			
                    // document.getElementById(div_id).innerHTML=selectionObject.print("%Y-%m-%d");
                    document.getElementById(id).innerHTML=selectionObject.print("%Y-%m-%d");

                });
            }

        }
    });


    cal. manageFields(btn, id, "%Y-%m-%d");

}

function calendar_add(){

    var cal = Calendar.setup({
        onSelect: function(cal) {
            cal.hide()
		 

        }
    });


    cal. manageFields("f_btn1", "date", "%Y-%m-%d");

}
function showEdits(){
    
    $(".click").editable("../../../controller/news_inline_controller.php", {
        indicator : "Saving..",
        tooltip   : "Click to edit...",
        style  : "inherit"
    });

    $(".editable_textarea").editable("../../../controller/news_inline_controller.php", {
        indicator : "Saving..",
        tooltip   : "Click to edit...",
        style  : "inherit",
        type: "textarea",
        select : true,
        onblur : 'submit'

    });

    $('.editable_textarea').tooltip({
        delay: 0,
        showURL: true,
        showBody: " - ",
        track: true,
        fade: 250,
        opacity: 0.85,
        bodyHandler: function() {

            if (this.innerHTML.substring(0,5)!='<form' && this.innerHTML != ''){
                return this.innerHTML.replace(/\n/g,"<br/>");
            }
        }
    });
}


 
function show_div(){
    document.getElementById('buttonContainer').style.display="inline";
}