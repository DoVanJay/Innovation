<?php
echo date('H:i');
$zero1=date('H:i');
$zero2 = '15:00';
if (strtotime($zero1) < strtotime($zero2)) {
    echo 'yes';
}