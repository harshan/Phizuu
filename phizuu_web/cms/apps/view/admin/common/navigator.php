<div class="tahoma_12_white2" id="adminNavigator">
    <a href="admin_controller.php?action=show_user_module" class="tahoma_12_white2">
        <span style="text-decoration: <?php echo ($module=='user')?'underline':'none'?>; font-weight: bold">User management</span>
    </a> |
    <a href="admin_controller.php?action=show_package_module" class="tahoma_12_white2">
        <span style="text-decoration: <?php echo ($module=='package')?'underline':'none'?>; font-weight: bold">Package Management</span>
    </a>
    <a href="admin_controller.php?action=show_account_managers_module" class="tahoma_12_white2">
        <span style="text-decoration: <?php echo ($module=='account_manager')?'underline':'none'?>; font-weight: bold">Account Managers</span>
    </a>
    <!--<a href="../box/box_mgt.php" class="tahoma_12_white2">
        <span>Box User account management</span>
    </a> |
    <a href="../module/module.php" class="tahoma_12_white2">
        <span>Module management</span>
    </a>-->
</div>