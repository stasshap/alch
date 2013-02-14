#!/usr/bin/php5
<?php
// variables ---------------------
$page='';
$st=0;
$gpos=0;
$xml='';
$gold=0;
$href='';
$fileo='out.html';
$usag='Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17';
$maxerror=5;
date_default_timezone_set('Europe/Kiev');

require('bal1pars.php');
require('bal1ocr.php');
require('gd3.php');

// Functions ------------------------------------------------------------------------------------------------
function testip(){
global $prox;
$c=curl_init('http://icanhazip.com');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);// разрешаем редиректы
if (!empty($prox)) {
	curl_setopt($c, CURLOPT_PROXY, $prox);
	curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); 
}
$page=curl_exec($c);
return $page;
}

function login()
{
global $host, $user, $pass, $cookie, $prox, $usag;

$c=curl_init($host.'login.php');
curl_setopt($c, CURLOPT_HEADER, 1); 
curl_setopt($c, CURLOPT_USERAGENT, $usag);
curl_setopt($c, CURLOPT_ENCODING,'gzip,deflate');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
//curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, 'do_cmd=login&server=1&email='.$user.'&password='.$pass);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);// разрешаем редиректы
if (!empty($prox)) {
curl_setopt($c, CURLOPT_PROXY, $prox);
curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); 
}
$page=curl_exec($c);
// echo "$page\n";
return $page;
}
function open($a)
{
global $host, $user, $pass, $cookie, $gpos, $page, $prox, $usag;
$c=curl_init($host.$a);
curl_setopt($c, CURLOPT_HEADER, false); 
curl_setopt($c, CURLOPT_USERAGENT, $usag);
curl_setopt($c ,CURLOPT_ENCODING,'gzip,deflate');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
if (!empty($prox)) {
curl_setopt($c, CURLOPT_PROXY, $prox);
curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); 
}
$page=curl_exec($c);
$gpos=0;
return $page;
}

function openpost($a,$p)
{
global $host, $user, $pass, $cookie, $gpos, $page, $prox, $href, $usag;
$c=curl_init($host.$a);
curl_setopt($c, CURLOPT_HEADER, 1); 
curl_setopt($c, CURLOPT_USERAGENT, $usag);
curl_setopt($c ,CURLOPT_ENCODING,'gzip,deflate');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);

curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, $p);

if (!empty($prox)) {
curl_setopt($c, CURLOPT_PROXY, $prox);
curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); 
}
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
$page=curl_exec($c);
$gpos=0;
return $page;
}



// ----------------------------------------------------------------------------------------------------------


// Main -----------------------------------------------------------------------------------------------------
echo "begin\n";

$hfo=fopen($fileo,'w');
$ip=testip();
print("ip=$ip\n");



while(TRUE) //зацикливаемся
{
print("(($st)[$maxerror])\n");
switch($st) 
{
   case 0: //логинимся вначале или ошибка сервера и т.д.

	if ($maxerror-- < 0 ){
		print "\nmaxerror !!!\n";
		exit(1);
	}

	print(date('H:i:s',time())." login");
	$page=login();
	print("...\n");
//fwrite($hfo,$page."\n-+-+-+-\n");
	$st=1;
//exit();

   break;

   case 1: // Крепость > Братство Алхимиков > Простейшие зелья в castle.php?a=myguild&id=31&m=potion_1



	$page=open("castle.php?a=myguild&id=31&m=potion_1");
//fwrite($hfo,$page."\n-+-+-+-\n"); //exit();

	$st=pars($page);

   break;


   case 2: // Следующее помеш
	$maxerror=4;
	$s=$href;
	print(date('H:i:s',time())." ждем $s секунд");
	ssleep($s);

	$s=rand(1,3);
	print(date('H:i:s',time())." и еще на всякий случай, ждем $s секунд");
	ssleep($s);


	echo "...\n";
	$st=1;

   break;



   case 3: // Вы должны помешать

	$s=rand(1,3);
	print(date('H:i:s',time())." и еще на всякий случай, ждем $s секунд");
	ssleep($s);

	$page=open("castle.php?a=myguild&id=31&m=potion_1");
	$k=pars_k($page);



   $i1='castle.php?a=myguild&id=31&m=temperature';
   $i2='castle.php?a=myguild&id=31&m=temperature&ideal=1';
	$p1=open($i1);
	$p2=open($i2);
	file_put_contents($tempdir."/1.jpg",$p1);
	file_put_contents($tempdir."/2.jpg",$p2);
   $n1=ocr($tempdir."/1.jpg");
   $n2=ocr($tempdir."/2.jpg");

	$res = $n2 - $n1;

	print "\n 1) $n1 2) $n2 =$res\n";

	
	$dir="1";
	if ($res <0) { 
		$dir="2"; 
		$res=abs($res); 
	}
	


	$page=open("castle.php?a=myguild&id=31&m=potion_1&do_cmd=stir&action_type=$dir&change_temperature=$res&k=$k");

	print "action_type=$dir & change_temperature=$res & k=$k  ";


	print(date('H:i:s',time())." \n");
	

//fwrite($hfo,$page); exit();
	$st=1;

   break;


   case 4: // Завершение варки зелья через
	$maxerror=4;
	$s=$href;
	print(date('H:i:s',time())." ждем $s секунд");
	ssleep($s);

	$s=rand(1,3);
	print(date('H:i:s',time())." и еще на всякий случай, ждем $s секунд");
	ssleep($s);


	echo "...\n";
	$st=1;

   break;

   case 5: // жмем кнопу ВАРИТЬ
        $r=rand(5000,900000);
	echo "спим $r мксек";
	usleep($r);
	echo "...\n варить : ";
	$k=pars_k($page);
	$page=open("castle.php?a=myguild&id=31&m=potion_1&do_cmd=make_potion&k=$k&cleaner_count=2&use_assistent=0");
	print(date('H:i:s',time())." \n");
	

//fwrite($hfo,$page); exit();
	$st=1;

   break;

 case 6: // жмем кнопу ЗАВЕРШИТЬ

        $r=rand(5000,800000);
	echo "спим $r мксек";
	usleep($r);
	echo "...\n завершить : ";
	$k=pars_k($page);
	print($k);
	$page=open("castle.php?a=myguild&id=31&m=potion_1&do_cmd=end_work&k=$k");
	print(date('H:i:s',time())." \n");
	

//fwrite($hfo,$page); exit();
	$st=1;

   break;
  
case 7: // жмем кнопу ПОЧИСТИТЬ
        $r=rand(5000,800000);
	echo "спим $r мксек";
	usleep($r);
	echo "...\n почистить : ";
	$k=pars_k($page);

	$page=open("castle.php?a=myguild&id=31&m=potion_1&do_cmd=clean_boiler&ptype=1&k=$k");
	print("\n".date('H:i:s',time())." \n");
	

//fwrite($hfo,$page); exit();
	$st=1;

   break;

  
   default:		 
      echo $st;
} //end switch 
} //end while



?>
`