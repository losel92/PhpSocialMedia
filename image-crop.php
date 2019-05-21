<?php

  $img_r = imagecreatefromjpeg($_GET['img']);
  $dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );

  imagecopyresampled($dst_r, $img_r, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
  
  header('Content-type: image/jpeg');
  imagejpeg($dst_r);
 
  exit;
/*
$targ_w = $targ_h = 600;
$jpeg_quality = 90;

$src = $_SESSION['profilePic'];
$img_r = imagecreatefromjpeg($src);
$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
    $targ_w,$targ_h,$_POST['w'],$_POST['h']);

header('Content-type: image/jpeg');
imagejpeg($dst_r, null, $jpeg_quality);
*/

?>