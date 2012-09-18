$(function() {
    reloadData();
});

function reloadData() {
    $('.moduleFloating').fadeOut(200);
    $('#viewRes').html("<div style='text-align:center'><br/><br/><br/><img src='../../../images/bigrotation2.gif'/><br/><br/>LOADING USERS</div>");
    $.post('admin_controller.php?action=query_data_package', {

    },
        function(data) {
            //alert(data);
            $('#viewRes').html("");
            $(data).appendTo('#viewRes');
            onLoading();
        }
    );
}

function onLoading() {
    $('.edit').editable('admin_controller.php?action=inline_edit_normal_package',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...'
    });

    return true;
}

function deletePackage(id) {
    if(!confirm('Are you sure, you want to delete this package?'))
        return;
    $('#parent_'+id).children().css('background-color', 'pink');
    //alert($('#parent_'+id).attr('id'));
    $.post("admin_controller.php?action=delete_package", {'id':id},
        function(data){
            //alert(data);
          $('#parent_'+id).fadeOut(300);
        }
    );
}

function createPackage() {

    $.post("admin_controller.php?action=add_new_package", {},
        function(data){
            //alert(data);
          reloadData();
        }
    );
}


function selectModules(id, btn, warning) {
    if($('#parent_'+id).find('.edit_status').html()=='App Wizard' && !confirm('This an app wizard user and adding module permisions may fail app wizard.\n\n Do you want to continue?'))
        return;

    var item = $(btn);
    var module = $('#module_list');
    var arrow = $('#pointerArrow');

    module.html("<div style='text-align:center'><img src='../../../images/bigrotation2.gif' height=20 width=20 align='top'/> Loading Module List</div>");
    $.post("admin_controller.php?action=get_module_list_ajax", {'id':id},
        function(data){
            module.html(data);
            $('.module_sel').click(function(){
       //alert(this.id);
               $(this).attr('disabled', true);

               var chkVal = 0;
               if (this.checked)
                   chkVal = 1;

               var item = this;
               $.post("admin_controller.php?action=edit_permisions", {'data':this.id, 'value':chkVal},
                    function(data){
                        //alert(data);
                        $(item).attr('disabled', false);
                    }
                );
            });
        }
    );

    module.css('top',item.offset().top+28);
    module.css('left',item.offset().left-570);

    arrow.css('top',item.offset().top+17);
    arrow.css('left',item.offset().left-30);
    module.show();
    arrow.show();
    module.css('opacity',0);
    arrow.css('opacity',0);
    module.fadeTo(200, 0.8);
    arrow.fadeTo(200, 0.8);
}

function toolBarAction(com,grid){
    if (com=='Delete')
    {
        if ($('.trSelected',grid).length == 0) {
            alert('Please select a user to delete');
            return;
        }
        var go = confirm('Delete selected ' + $('.trSelected',grid).length + ' user(s)?')
        if (go) {

            var ids = new Array();
            $('.trSelected',grid).each(function(){
                var id = this.id.replace('row','');
                ids.push(id);
            });
            $("#flex1").showBusy();
            $.post("admin_controller.php?action=delete_users", "delete="+ids,
                function(data){
                    //alert(data);
                  $("#flex1").hideBusy();
                  $("#flex1").flexReload();
                }
            );
        }
    }
    else if (com=='Add')
    {
        $("#flex1").hideBusy();
    }
}

function changePassword(id) {
    var text = prompt("Please enter the password and and press OK. If you change the password the old password can't be recovered. If you don't want to change the password please press Cancel",'')
    if (text) {
        $.post("admin_controller.php?action=change_password", {'password':text, 'id':id},
            function(data){
                alert("Password changed to " + data);
            }
        );
    }
}
