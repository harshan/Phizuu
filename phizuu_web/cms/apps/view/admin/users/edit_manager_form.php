<div class="row_box" style="padding-top: 20px">
    <div id="lightBlueHeader2">

        <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" style="width: 940px">Edit Account Manager</div>

    </div>
    <div style="width: 400px;float: left">
        <div><div style="">Email  </div><div><strong><?php echo $row['email']; ?></strong></div></div><div id="edit_error_email" class="accountManagerErrorMsg"></div>
        <div><div>User Name  </div><div><strong><?php echo $row['username']; ?></strong></div></div><div id="edit_error_username" class="accountManagerErrorMsg"></div>

        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div><div style="padding-top: 10px;" ><img src="../../../images/save.png" style="cursor: pointer" id="edit_save"/><img src="../../../images/cancel.png" id="edit_cancel" style="cursor: pointer"/><img src="../../../images/changePassword.png" id="change_password" style="cursor: pointer" onclick="changePssword(<?php echo $row['id']?>)"/></div></div>

    </div>
    <div style="width: 400px;float: left">
        <div><div>User Status  </div>
            <div><select id="edit_userStatus">

                    <option value="0" <?php if ($row['status'] == 0) {
    echo 'selected=selected';
} ?>>Inactive</option>
                    <option value="1" <?php if ($row['status'] == 1) {
    echo 'selected=selected';
} ?>>Active</option>

                </select></div>
        </div>
        <div><div>User Type  </div><div>
                <select id="edit_userType">
                    <option value="1" <?php if ($row['user_type'] == 1) {
    echo 'selected=selected';
} ?>>Single account</option>
                    <option value="2" <?php if ($row['user_type'] == 2) {
    echo 'selected=selected';
} ?>>Multiple accounts</option>
                </select>

            </div></div>
        <div id="edit_error_userType" class="accountManagerErrorMsg"></div>
        <div id="edit_single_user_account" >
            <div>Please select user account </div>
            <div>
                <input type="text" id="edit_userAccount" onkeyup="autocompleteUserEdit(this.value); return false;"/><input type="button" value="Add User" id="edit_addNewUser"/>
            </div>
            <div id="edit_userList" style="padding: 10px 0 0 5px">
                <?php if(isset($users)){ 
                    foreach($users as $val){ 
                        ?>
                <div style="clear: both" id="row_<?php echo $val['id'] ?>">
                    <div style="float: left;width: 200px;height:20px"><input type="hidden" id="user_id" value="<?php ?>"/><?php echo $val['username']?></div>
                    <div style="float: left" id=delete_<?php echo $val['id'] ?>><img src="../../../images/delete_icon.png" style="cursor:pointer"/></div>
                </div>
                <?php } }?>
            </div>
            <div id="edit_error_user_accounts" class="accountManagerErrorMsg"></div>
        </div>
    </div>

</div>