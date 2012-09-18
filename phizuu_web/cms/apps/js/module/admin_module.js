stack = new Array();

$(function() {
    
    $("#add_new").click(function(){
        $("#edit_form").slideUp("slow");
        $("#add_new_form").slideDown("slow");
    });
    $("#cancel").click(function(){
        $("#add_new_form").slideUp("slow");
        $("#email").val('');
        $("#password").val('');
        $("#confirmPassword").val('');
        $("#username").val('');
        $("#userType").val(0);
        $("#userAccount").val('');
        $('#userList').html('');
        stack.length = 0;
        
    });
    $("#save").click(function(){
        error = 0;
        $("#error_email").hide();
        $("#error_username").hide();
        $("#error_password").hide();
        $("#error_confirmPassword").hide();
        $("#error_user_accounts").hide();
        var email = $("#email").val().trim();
        var username = $("#username").val().trim();
        var password = $("#password").val().trim();
        var confirmPassword = $("#confirmPassword").val().trim();
        var userType = $("#userType").val().trim();
      
        
        if(email==""){
            $("#error_email").text("Email can't be blank!");
            $("#error_email").slideDown("slow");
            
            
            error = error+1;


        //$("#error_email").fadeIn("slow");
        }else{
            if(!IsEmail(email)){
                $("#error_email").text("Invalid email format!");
                $("#error_email").slideDown("slow");
                
                error = error+1;
            }
        }
        if(username==""){
            $("#error_username").text("User name can't be blank!");
            $("#error_username").slideDown("slow");
            
            error = error+1;
            
        }
        if(password==""){
            $("#error_password").text("Password can't be blank!");
            $("#error_password").slideDown("slow");
            
            error = error+1;
            
        }else if(confirmPassword==""){
            $("#error_confirmPassword").text("Confirm password can't be blank!");
            $("#error_confirmPassword").slideDown("slow");
            
            error = error+1;
            
        }else if(password!=confirmPassword){
            $("#error_confirmPassword").text("Password and Confirm password not matched!");
            $("#error_confirmPassword").slideDown("slow");
            
            error = error+1;
            
        }
        if(userType=="0"){
            $("#error_userType").text("User type can't be blank!");
            $("#error_userType").slideDown("slow");
            
            error = error+1;
        }
        if(userType!="0"){
            if(stack.length==0){
            
                $("#error_user_accounts").text("You must add at least one user account!");
                $("#error_user_accounts").slideDown("slow");
                
                error = error+1;
            
            }
        }
        
        if(error==0){
            var emailVal = $("#email").val();
            var passwordVal = $("#password").val();
            var usernameVal = $("#username").val();
            var userTypeVal = $("#userType").val();
            
            $.post('admin_controller.php?action=add_account_manager', {
                'email':emailVal, 
                'password':passwordVal,
                'username':usernameVal,
                'userType':userTypeVal,
                'userList':stack
            },function(data){
                if(data.error==true){
                    $("#error_msg").slideDown('slow');
                    
                    $("#error_msg").html(data.msg);
                }else{
                    $("#email").val('');
                    $("#password").val('');
                    $("#confirmPassword").val('');
                    $("#username").val('');
                    $("#userType").val(0);
                    $("#userAccount").val('');
                    $('#userList').html('');
                    stack.length = 0;
                    //Add row to table
                    $("#manager_list_table").append(data.html).slideDown("slow");

                    
                    //display success message
                    $("#successMsg").html('Record saved successfully!');
                    $("#successMsg").slideDown(1000);
                    setTimeout( function(){
                        $("#successMsg").slideUp(1000);
                        $("#manager_list_table tr").css("border","0px");
                    }, 7000);
                    
                    
                }
                
             
            
            },'json');
        }
        
    //$.post(url, data, callback, type);
    });
    $("#userType").change(function(){
        var userAccountType = $("#userType").val();
        if(userAccountType==0){
            $("#single_user_account").hide();
            $('#userList').html('');
            $("#userAccount").val('');
            stack.length = 0;
           
            
        }
        if(userAccountType==1 || userAccountType==2){
            $("#single_user_account").show();
            $('#userList').html('');
            $("#userAccount").val('');
            stack.length = 0;
           
            
            
        }
    })
   
    
    $('.sel_search').keyup(function(e) {
        if(e.keyCode == 13) {
            searchData();
        }
    });

    $('#search_status').change( function(){
        searchData();
    });

    $(document).click(function(e) {
        $('#module_list').fadeOut(200);
        $('#pointerArrow').fadeOut(200);
    });

    $('#module_list').click(function(e) {
        e.stopPropagation();
    });

    $('#viewRes').css('height', rp*29);

    reloadData();
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    $('#addNewUser').click(function(){
        $("#error_user_accounts").hide();
        var userAccount = $("#userAccount").val();
        var userAccountType = $("#userType").val();
        //alert(userAccountType);
        if(stack.length >0){ 
            for(var i=0; i<stack.length; i++) {
                stack1= stack1+',';
                stack1= stack1+stack[i];
            
                
                    
            }
        }else{
            stack1=0; 
        }
        if(userAccountType==1){
            if(stack.length>0){
                $( "#dialog-message" ).dialog({
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
            }else{
                $.post('admin_controller.php?action=find_user_account', {
                    'userAccount':userAccount, 
                    'userids':stack1
                },function(data){
            
                    if(data!=false){
                
                        namesAry = new Array();
                        namesAry = data.split(","); 
                        stack.push(namesAry[0]);
                
                        $(data).appendTo('#userList').show();
                        $("#userAccount").val('');
                        reload();
                

                    }
            
                });
            }
        }else{
            $.post('admin_controller.php?action=find_user_account', {
                'userAccount':userAccount, 
                'userids':stack1
            },function(data){
            
                if(data!=false){
                
                    namesAry = new Array();
                    namesAry = data.split(","); 
                    stack.push(namesAry[0]);
                
                    $(data).appendTo('#userList').show();
                    $("#userAccount").val('');
                    reload();
                

                }
            
            });
        }
    });
    
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
});
function editManager(id){
    $("#edit_form").slideDown("slow");
    $("#add_new_form").slideUp("slow");
    
    $.post('admin_controller.php?action=edit_manager_account', {
        'id':id
    },function(data){
        
        $("#edit_form").html(data.html);
        
        
        
        
        stack1 = data.stack;
        stack1 = stack1.substring(0,stack1.length - 1);
        //alert(stack1);
        stack = (stack1).split(","); 
        
        

        $("#edit_userType").change(function(){
          
            var userAccountType = $("#edit_userType").val();
            
            if(userAccountType==1 || userAccountType==2){
               
                $( "#dialog-confirm-change-user-type" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Confirm": function() {
                            $( this ).dialog( "close" );
                            $("#edit_single_user_account").show();
                            $('#edit_userList').html('');
                            $("#edit_userAccount").val('');
                            stack.length = 0;
                            
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            if(userAccountType==1){
                                $("#edit_userType").val(2);
                            }else if(userAccountType==2){
                                $("#edit_userType").val(1);
                            }
                            
                        }
                    }
                });
            
            
            }
        })     
        reloadDataWhenEdit(id);  
     
    },'json');
    
}


function deleteManager(id){
    $( "#dialog-confirm" ).dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Delete": function() {
                $( this ).dialog( "close" );
                //delete manager account 
                
                $.post('admin_controller.php?action=delete_manager_account', {
                    'id':id

                },function(data){
                    $("#tr_"+id).slideUp('slow');
                    $("#tr_"+id).remove();
                    $("#successMsg").html('Record deleted successfully!');
                    $("#successMsg").slideDown("slow");
                    setTimeout( function(){
                        $("#successMsg").slideUp("slow");
                    }, 7000);
                    
            
                });
                                                      
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
function reloadDataWhenEdit(id){
    reload2();
    $('#edit_addNewUser').click(function(){
        $("#edit_error_user_accounts").hide();
        var userAccount = $("#edit_userAccount").val();
        var userAccountType = $("#edit_userType").val();
        //alert(userAccountType);
        if(stack.length >0){ 
            for(var i=0; i<stack.length; i++) {
                stack1= stack1+',';
                stack1= stack1+stack[i];
            
                
                    
            }
        }else{
            stack1=0; 
        }
        if(userAccountType==1){
            if(stack.length>0){
                $( "#dialog-message" ).dialog({
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
            }else{
                $.post('admin_controller.php?action=find_user_account', {
                    'userAccount':userAccount, 
                    'userids':stack1
                },function(data){
            
                    if(data!=false){
                
                        namesAry = new Array();
                        namesAry = data.split(","); 
                        stack.push(namesAry[0]);
                
                        $(data).appendTo('#edit_userList').show();
                        $("#edit_userAccount").val('');
                        reload2();                

                    }
            
                });
            }
        }else{
            $.post('admin_controller.php?action=find_user_account', {
                'userAccount':userAccount, 
                'userids':stack1
            },function(data){
            
                if(data!=false){
                
                    namesAry = new Array();
                    namesAry = data.split(","); 
                    stack.push(namesAry[0]);
                
                    $(data).appendTo('#edit_userList').show();
                    $("#edit_userAccount").val('');
                    reload2();
                

                }
            
            });
        }
    });
    $("#edit_cancel").click(function(){
        $("#edit_form").slideUp("slow");
        
    })
    $("#edit_save").click(function(){
        
        error = 0;


        $("#edit_error_user_accounts").hide();
        var userStatus = $("#edit_userStatus").val().trim();
        var userType = $("#edit_userType").val().trim();
       
        
        
        
 
        if(userType!="0"){
            if(stack.length==0){
            
                $("#edit_error_user_accounts").text("You must add at least one user account!");
                $("#edit_error_user_accounts").slideDown("slow");
                
                error = error+1;
            
            }
        }
        
        if(error==0){

            var userStatusVal = $("#edit_userStatus").val();
            var userTypeVal = $("#edit_userType").val();
            
            $.post('admin_controller.php?action=edit_save_account_manager', {       
                'id':id,
                'userStatus':userStatusVal,
                'userType':userTypeVal,
                'userList':stack
            },function(data){
                
                if(data.error==true){
                    $("#error_msg").slideDown('slow');
                    $("#error_msg").html(data.msg);
                }else{
                    
                    $("#tr_"+id).replaceWith(data.html);
                    $("#edit_form").slideUp(1000);
                    //display success message
                    $("#successMsg").html('Record saved successfully!');
                    $("#successMsg").slideDown(1000);
                    setTimeout( function(){
                        $("#successMsg").slideUp(1000);
                        $("#manager_list_table tr").css("border","0px");
                        
                    }, 7000);
                    

                    
                }
                
             
            
            },'json');
        }
        
    //$.post(url, data, callback, type);
    });
}
function changePssword(id){
    
    $( "#dialog-changePassword" ).dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Change Password": function() {
                
                var error=false;
                var newPssword = $.trim($("#newPswword").val());
                var confirmPswword = $.trim($("#confirmPswword").val());
                
                if(newPssword == '' || confirmPswword == ''){
                    $("#password_error_list").text("New password or Confirm password can't be blank!");
                    $("#password_error_list").slideDown(1000);
                    error = true;
                }else if(newPssword !=confirmPswword){
                    $("#password_error_list").text("New password and Confirm password not matched!");
                    $("#password_error_list").slideDown(1000);
                    error = true;
                }
                
                if(error==false){
                    $.post('admin_controller.php?action=changePssword', {
                        'id': id,
                        'password':newPssword
                    }, function(data){
                       
                        $( "#dialog-changePassword" ).dialog( "close" );
                        $("#successMsg").html('Password changed successfully!');
                        $("#successMsg").slideDown(1000);
                        setTimeout( function(){
                            $("#successMsg").slideUp(1000);
                            $("#manager_list_table tr").css("border","0px");
                        
                        }, 7000);
                    });
                }
                            
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
function reload(){
    $("#userList div").mouseover(function(){
       
        var arr = $(this).attr('id').split("_");
        var id = arr[1];
        $("#row_"+id+" div").click(function(){
            var arr1 = $(this).attr('id').split("_");
            var id1 = arr1[1];
                
            if('delete'==arr1[0]){
                
                function removeItem(array, item){
                    for(var i in array){
                        if(array[i]==item){
                            array.splice(i,1);
                            break;
                        }
                    }
                }
                
	
                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Delete": function() {
                            $( this ).dialog( "close" );
                            removeItem(stack, id1);
                            $("#row_"+id).remove();
                                       
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
                

                
                
            }
            
        });
        

        
    })
    
}
function reload2(){
    $("#edit_userList div").mouseover(function(){
       
        var arr = $(this).attr('id').split("_");
        var id = arr[1];
        $("#row_"+id+" div").click(function(){
            var arr1 = $(this).attr('id').split("_");
            var id1 = arr1[1];
                
            if('delete'==arr1[0]){
                
                function removeItem(array, item){
                    for(var i in array){
                        if(array[i]==item){
                            array.splice(i,1);
                            break;
                        }
                    }
                }

                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Delete": function() {
                            $( this ).dialog( "close" );
                            removeItem(stack, id1);
                            $("#row_"+id).remove();
                                       
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });

            }
            
        });
        
    })
    
}
function autocompleteUser(userName){
    
    
    if(stack.length >0){ 
        for(var i=0; i<stack.length; i++) {
            
            stack1= stack1+',';
            
            stack1= stack1+stack[i];
               
                
                    
        }
    }else{
        stack1=0; 
    }
    
    
    $.post('admin_controller.php?action=find_users', {
        'searchkeyword': ""+userName+"",
        'userids':stack1
    }, function(data){
        
        if(data.length >0) 
        {
            namesAry = new Array();
            namesAry = data.split(",");
            namesAry = namesAry.join('","');
            namesAry = data.split(",");
					  
				
            $( "#userAccount" ).autocomplete({
                source: namesAry
            });
        }
       
        $('#userAccount').focus(function(){            
            $(this).trigger('keydown.autocomplete');
        });
    });
}
function autocompleteUserEdit(userName){
    
    
    if(stack.length >0){ 
        for(var i=0; i<stack.length; i++) {
            
            stack1= stack1+',';
            
            stack1= stack1+stack[i];

                    
        }
    }else{
        stack1=0; 
    }
    
    
    $.post('admin_controller.php?action=find_users', {
        'searchkeyword': ""+userName+"",
        'userids':stack1
    }, function(data){
        
        if(data.length >0) 
        {
            namesAry = new Array();
            namesAry = data.split(",");
            namesAry = namesAry.join('","');
            namesAry = data.split(",");
					  
				
            $( "#edit_userAccount" ).autocomplete({
                source: namesAry
            });
        }
       
        $('#edit_userAccount').focus(function(){            
            $(this).trigger('keydown.autocomplete');
        });
    });
}
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function searchData() {
    page = 1;
    reloadData();
}

function resetSearch() {
    $(".sel_search").val('');
    reloadData();
}

function reloadData() {
    $('.moduleFloating').fadeOut(200);
    $('#viewRes').html("<div style='text-align:center'><br/><br/><br/><img src='../../../images/bigrotation2.gif'/><br/><br/>LOADING USERS</div>");
    $.post('admin_controller.php?action=query_data', {
        'page':page,
        'rp':rp,
        'username':$('#search_username').val(),
        'app_id':$('#search_app_id').val(),
        'user_id':$('#search_user_id').val(),
        'app_name':$('#search_app_name').val(),
        'email':$('#search_email').val(),
        'status':$('#search_status').val()
    },
    function(data) {
        //if(window.console) {
        //    window.console.log(data);
        //}
        $('#viewRes').html("");
        var total = data.total;
        total_pages = Math.floor(total/rp)+1;
        $(data.html).appendTo('#viewRes');
        $('#totalDiv').html('Page: '+page + '&nbsp;&nbsp;&nbsp;&nbsp;Total Pages: '+ total_pages +  '&nbsp;&nbsp;&nbsp;&nbsp;Users: '+data.total);
        onLoading();
        updateButtons();
    }
    ,'json');
        
    

}

function onLoading() {
    $('.edit').editable('admin_controller.php?action=inline_edit_normal',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...'
    });

    $('.edit_package').editable('admin_controller.php?action=inline_edit_normal',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        data      : packageArr,
        type      : 'select',
        onblur    : 'submit'
    });

    $('.edit_paid').editable('admin_controller.php?action=inline_edit_normal',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        data      : "{'1':'Yes','0':'No'}",
        type      : 'select',
        onblur    : 'submit'
    });

    $('.edit_status').editable('admin_controller.php?action=inline_edit_normal',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        data      : "{'0':'App Wizard','1':'CMS','3':'Freezed','4':'Built'}",
        type      : 'select',
        onblur    : 'submit'
    });

    $('.edit_confirmed').editable('admin_controller.php?action=inline_edit_normal',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        data      : "{'1':'No','0':'Yes'}",
        type      : 'select',
        onblur    : 'submit'
    });

    $('.button').click(function(e) {
        e.stopPropagation();
    });

    
    return true;
}

function deleteUser(id) {
    if(!confirm('Are you sure, you want to delete this user?'))
        return;
    $('#parent_'+id).children().css('background-color', 'pink');
    //alert($('#parent_'+id).attr('id'));
    $.post("admin_controller.php?action=delete_user", {
        'id':id
    },
    function(data){
        //alert(data);
        $('#parent_'+id).fadeOut(300);
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
    $.post("admin_controller.php?action=get_module_list_ajax", {
        'id':id
    },
    function(data){
        module.html(data);
        $('.module_sel').click(function(){
            //alert(this.id);
            $(this).attr('disabled', true);

            var chkVal = 0;
            if (this.checked)
                chkVal = 1;

            var item = this;
            $.post("admin_controller.php?action=edit_permisions", {
                'data':this.id, 
                'value':chkVal
            },
            function(data){
                //alert(data);
                $(item).attr('disabled', false);
            }
            );
        });
    }
    );

    module.css('top',item.offset().top+28);
    module.css('left',item.offset().left-750);

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
        $.post("admin_controller.php?action=change_password", {
            'password':text, 
            'id':id
        },
        function(data){
            alert("Password changed to " + data);
        }
        );
    }
}

function goNext() {
    if(page<total_pages) {
        page = page + 1;
        reloadData();
        updateButtons();
    }
}

function goBack() {
    if(page>1) {
        page = page - 1;
        reloadData();
    }
}

function updateButtons() {
    if (page == 1) {
        $('#imgPrev').attr('src', '../../../images/btn_prev_disabled.png');
    } else {
        $('#imgPrev').attr('src', '../../../images/btn_prev.png');
    }

    if (page == total_pages) {
        $('#imgNext').attr('src', '../../../images/btn_next_disabled.png');
    } else {
        $('#imgNext').attr('src', '../../../images/btn_next.png');
    }
}

function downloadAppBundle(userId) {
    window.location = "../../../controller/modules/app_wizard/AppWizardControllerNew.php?action=download_bundle&user_id="+userId;
}

var page = 1;
var rp = 13;
var total_pages = 4;