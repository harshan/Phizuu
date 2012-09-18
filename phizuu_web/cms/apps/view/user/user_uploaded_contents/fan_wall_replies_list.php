<?php
$posts = $commentObj->replies;

foreach ($posts as $post) {
    if($post->user_type == 'FB') {
        $user_type = 'FaceBook User';
    } elseif ($post->user_type == 'TW') {
        $user_type = 'Twitter User';
    } else {
        $user_type = 'Unknow User Type';
    }

    ?>
<div id="post_<?php echo $post->comment_id ?>" class="row" >
  <div class="reply_row" onclick="javascript: toggle_comments(<?php echo $post->comment_id ?>);">
      <div class="post_info">By <?php echo $post->user_name ?> - <?php echo $user_type ?> at <?php echo $post->timestamp ?> UTC
        - <a href="#" onclick="javascript: return deleteComment(<?php echo $post->comment_id ?>,this, <?php echo $post->reply_to ?>);">Delete</a>
      </div>
      <div class="comment"><?php echo str_replace("\n", "<br/>", $post->comment); ?></div>
  </div>
</div>
<?php } ?>
