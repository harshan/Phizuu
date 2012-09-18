<?php
$to = "harshan@phizuu.com";
$subject = "Phizuu.com";
$message = "Hello! This is a simple email message.";
$from = "info@phizuu.com";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo "Mail Sent.";
?> 