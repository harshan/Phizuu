
$(document).ready(function() {
    $("#tourSortable").sortable({handle : '.dragHandle'});
    

    $('#tourSortable').bind('sortupdate', function(event, ui) {
        $("#tourSortable").sortable( 'disable' );
        $("#tourSortable").css('cursor', 'wait');
        $("#tourSortable .dragHandle").css('cursor', 'wait');
        var order = $('#tourSortable').sortable('serialize');
        $.post('../../../controller/tours_all_controller.php?action=order&'+order, function(data) {
            $("#tourSortable").sortable( 'enable' );
            $("#tourSortable .dragHandle").css('cursor', 'move');
            $("#tourSortable").css('cursor', '');
        });


    });

    $('.edit').editable('../../../controller/tours_all_controller.php?action=edit',{
         indicator : 'Saving...',
         tooltip   : 'Click to edit...'
     });

});


function calendar(btn,id,div_id){
      var cal = Calendar.setup({
          onSelect: function(cal) {cal.hide() 
		  var selectionObject = cal.selection;
		  var selectedDate = selectionObject.get();
		  if(selectedDate != ""){

 
		 var date=selectionObject.print("%Y-%m-%d");
		 $.post('../../../controller/tours_all_controller.php?action=edit', {id: div_id,value: date}, function(data) {

			   document.getElementById(id).innerHTML=selectionObject.print("%Y-%m-%d");

		 });
		  }

		  }
      });


	  cal. manageFields(btn, id, "%Y-%m-%d");

}

function calendar_add(){

      var cal = Calendar.setup({
          onSelect: function(cal) {cal.hide() 
		  

		  }
      });


	  cal.manageFields("f_btn1", "date", "%Y-%m-%d");

}
 
 function show_div(){
 //document.getElementById('buttonContainer').style.display="inline";
    $("#buttonContainer").show(500);
 }