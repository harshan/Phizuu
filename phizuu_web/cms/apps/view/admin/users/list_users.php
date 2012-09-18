<?php
function getUsersHTML($users, $packages) {
    ob_start();
    foreach($users as $user) {
        $id = $user['id'];
        $packageId = $user['package_id'];
        $paid = $user['paid'];;
        $suspended = $user['is_suspended'];;
        $appId = $user['app_id'];;

        if ($packageId == 1 || ($paid == 0 && $suspended == 1)) {
            $packageName = $packages[0]['name'];
        } else {
            $packageName = $packages[$packageId-1]['name'];
        }

        //Suspended
        if ($suspended == 0) {
            $activeText = 'Yes';
        } else {
            $activeText = 'No';
        }

        //Paid
        if ($paid == 1) {
            $paidText = 'Yes';
        } else {
            $paidText = 'No';
        }

        //Status
        $status = $user['status'];
        if ($status == 0) {
            $statusText = 'App Wizard';
        } elseif ($status == 1) {
            $statusText = 'CMS';
        } elseif ($status == 3) {
            $statusText = 'Freezed';
        } elseif ($status == 4) {
            $statusText = 'Built';
        } else {
            $statusText = 'Invalid';
        }

        $path = "../../../application_dirs/$appId";
        $download = false;
        if (file_exists($path)) {
            $download = true;
        }
       
        ?>
      <div class="row_box_data" id="parent_<?php echo $id?>">
          <div class="data" style="width:41px" id='id|<?php echo $id?>'><?php echo $user['id']; ?></div>
          <div class="data edit" style="width:100px" id='username|<?php echo $id?>'><?php echo $user['username']; ?></div>
          <div class="data edit" style="width:60px" id='app_id|<?php echo $id?>'><?php echo $user['app_id']; ?></div>
          <div class="data edit" style="width:140px" id='app_name|<?php echo $id?>'><?php echo $user['app_name']; ?></div>
          <div class="data edit" style="width:180px" id='email|<?php echo $id?>'><?php echo $user['email']; ?></div>
          <div class="data edit_package" style="width:70px" id='package_id|<?php echo $id?>'><?php echo $packageName; ?></div>
          <div class="data edit_paid" style="width:40px" id='paid|<?php echo $id?>'><?php echo $paidText; ?></div>
          <div class="data edit_status" style="width:67px" id='status|<?php echo $id?>'><?php echo $statusText; ?></div>
          <div class="data edit_confirmed" style="width:60px" id='is_suspended|<?php echo $id?>'><?php echo $activeText; ?></div>
          <div class="data" style="width:110px" >
              <img class="button" src="../../../images/album_del_icon.png" title="Delete User" onclick="javascript: deleteUser(<?php echo $user['id']; ?>)"/>&nbsp;&nbsp;
              <img class="button" src="../../../images/album_add_icon.png" title="Set Module Permissions for the User" onclick="javascript: selectModules(<?php echo $user['id']; ?>, this, <?php echo $status==0?'true':'false' ?>)"/>&nbsp;&nbsp;
              <img class="button" src="../../../images/Padlock.png" title="Reset Password" onclick="javascript: changePassword(<?php echo $user['id']; ?>)"/> 
              <?php if ($download) { ?>
              <img class="button" style="margin-left: 5px;" src="../../../images/download-icon-grey.png" title="Download App Bundle" onclick="javascript: downloadAppBundle(<?php echo $user['id']; ?>)"/>
              <?php } ?>
          </div>
          
      </div>
<?php
    }
    $rtn = ob_get_contents();
    ob_end_clean();
    return $rtn;
}
?>
