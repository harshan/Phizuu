<?php
$fp1 = fopen('Chats.txt', 'r');
$fp2 = fopen('Skype call history.txt', 'w');
fclose($fp2);
$fp2 = fopen('Skype call history.txt', 'a');
while (($buffer = fgets($fp1)) !== false) {
    if (preg_match('/\*\*\* Call to/', $buffer) || preg_match('/\*\*\* Call from/', $buffer) || preg_match('/\*\*\* Call ended/', $buffer)  || preg_match('/\*\*\* Missed call from/', $buffer))
        fwrite($fp2, $buffer);
}

fclose($fp1);
fclose($fp2);
?>
