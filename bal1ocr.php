<?php

//date_default_timezone_set('Europe/Kiev');

//$file=$argv[1];
//print $file ." - ";
//print ocr($file)."\n";

//exit;

function ocr($file){
global $tempdir;

$pt=$tempdir.'/'; //"/tmp/"; // path temp
$ret="";
$sa=array(
-1 => '0000080affffffffffffffffffff',
 0 => '0000080ae7c3993c3c3c3c99c3e7',
 1 => '0000080ae7c787e7e7e7e7e7e781',
 2 => '0000080ac3993cfcf9f3e7cf9f00',
 3 => '0000080a8339fcf9e3f9fcfc3983',
 4 => '0000080af9f1e1c9993900f9f9f9',
 5 => '0000080a013f3f2319fcfc3c99c3',
 6 => '0000080ac3993d3f23193c3c99c3',
 7 => '0000080a00fcfcf9f3e7cf9f3f3f',
 8 => '0000080ac3993c99c3993c3c99c3',
 9 => '0000080ac3993c3c98c4fcbc99c3'
);

$image = @imagecreatefromjpeg($file);

imagefilter2($image, IMAGE_FILTER_GRAYSCALE);
imagefilter2($image, IMAGE_FILTER_CONTRAST,-100);

$ox=32; $oy=8;
$w=8; $h=10;
$cc=4; //count char

$ia=array();
//imagerectangle($image,32,8,38,18,1234);

$i00=imagecreatetruecolor($w,$h);
imagecopy($i00, $image, 0, 0, 27, $oy, $w, $h);
imagewbmp($i00,"$pt"."00.wbmp");
$b00=bin2hex(file_get_contents("$pt"."00.wbmp"));

if (array_search($b00,$sa)== -1){
    $cc--;
    $ox=36;
//    print "3 digits\n";
}

for ($i=0;$i<$cc;$i++){
    $x=$i*($w+1) + $ox;
    $ia[$i]=imagecreatetruecolor($w,$h);
    imagecopy($ia[$i], $image, 0, 0, $x, $oy, $w, $h);
    imagewbmp($ia[$i],"$pt$i.wbmp");
//    imagerectangle($image, $x, $oy, $x+$w, $oy+$h, 654);
//    print (substr($file,$i,1));
    $symb = array_search(bin2hex(file_get_contents("$pt$i.wbmp")),$sa);
    $ret .=$symb;
}
$fo=microtime(1).'.png';
print " $fo \n ";
imagepng($image,$fo);
return $ret;
} //



?>
