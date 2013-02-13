<?php
// 2 Следующее помешивание котла через
// 3 Вы должны помешать зелье в котле в течение
// 4 Завершение варки зелья через
// 5 ВАРИТЬ
// 6 ЗАВЕРШИТЬ
// 7 ПОЧИСИТЬ
// 8 
// 0 ничего service
// -----------------------


function pars($a) {
global $href;
$a = preg_replace ('/\r\n|\n|\r/', '', $a); 
if (preg_match('/504 Gateway Time-out/',$a,$b)) {
	print "504\n";
	return 0;
}

if (preg_match('/Следующее помешивание котла через(.+?)<\/span/',$a,$b)){
//		print_r($b);
	if (preg_match('/(\d\d:\d\d:\d\d)/',$b[1],$c)) {  //"
//		print_r($c);
		echo "ЖДЕМ\n";
		$ts=($c[1]);
		$s=substr($ts,0,2)*3600+substr($ts,3,2)*60+substr($ts,6,2);
		$href=$s;
		print("осталось '$ts' = $s сек\n");
		return 2;

	}
}

if (preg_match('/Вы должны помешать зелье в котле в течение/',$a,$b)){
	return 3;
}

if (preg_match('/Завершение варки зелья через(.+?)<\/span/',$a,$b)){
//		print_r($b);
	if (preg_match('/(\d\d:\d\d:\d\d)/',$b[1],$c)) {  //"
//		print_r($c);
		echo "ЖДЕМ\n";
		$ts=($c[1]);
		$s=substr($ts,0,2)*3600+substr($ts,3,2)*60+substr($ts,6,2);
		$href=$s;
		print("осталось '$ts' = $s сек\n");
		return 4;

	}
}

if (preg_match('/ЗАВЕРШИТЬ/',$a,$b)){
	return 6;
}

if (preg_match('/ПОЧИСТИТЬ/',$a,$b)){
	$k=get_kri($a);
	if ($k<30){
		print " $k - мало крист.";
	}

	return 7;
}

if (preg_match('/ВАРИТЬ/',$a,$b)){
	if (time_rent($a)<14400){
		print "Аренда истекает < 4 ч";
		exit(0);
	}

	return 5;
}

//if (preg_match('/Вы успешно сварили зелье/',$a,$b)){
//	return 5;
//Увы, в процессе зельеварения ваш котел взорвался
//}





sleep(1);
exit;
return 0;
} // end pars function


function time_rent($a){
$t='Аренда Котла Шубидубу';
//$t='Аренда Заморского Котла';

if (preg_match("/$t(.+?)<\/span/",$a,$b)){
//		print_r($b);
	if (preg_match('/(\d\d:\d\d:\d\d)/',$b[1],$c)) {  //"
		$ts=($c[1]);
	print " аренда $ts \n";
		$s=substr($ts,0,2)*3600+substr($ts,3,2)*60+substr($ts,6,2);
		return $s;

	}
}

} //end func 


function pars_k($a){
 if (preg_match('/name="k" value="(\d+?)"/',$a,$b)){
	return $b[1];
  }
  if (preg_match("/name='k' value='(\d+?)'/",$a,$b)){
	return $b[1];
  }
}

//name='room' value='4'
function get_room($a){
if (preg_match('/name="room" value="(\d+?)"/',$a,$b)){
	return $b[1];
}
if (preg_match("/name='room' value='(\d+?)'/",$a,$b)){
	return $b[1];
}

}

function pars_old($a) {
global $href;
$a = preg_replace ('/\r\n|\n|\r/', '', $a); 

if (preg_match('/Команда вышла в море за добычей уже 15 раз/',$a)){
	echo "Команда вышла в море за добычей уже 15 раз\n";
	return 5;
}


if (preg_match('/b_help_37(.+?)bottom/',$a,$b)) {
//  print_r($b);
	if (preg_match("/value='(\d+?)'/",$b[1],$c)){
//		print_r($c);
		$href=$c[1]; return 2;
	}

	if (preg_match('/Ваше судно добывает пирашки/',$b[1],$c)) {
		echo "добывает\n";
//		print_r($c);
		if (preg_match('/Осталось: <(.+?)>(.+?)</',$b[1],$d)) {
//			print_r($d);
			$ts=($d[2]);
			$s=substr($ts,0,2)*3600+substr($ts,3,2)*60+substr($ts,6,2);
			$href=$s;
			print("осталось '$ts' = $s сек\n");
		}
		return 3;

	}
	if (preg_match('/Ваше судно плывёт в гавань с добытыми пирашками/',$b[1],$c)) {
		echo "плывет\n";
		return 4;
	}

}
return 0;
} // end pars function

function ssleep($s){
$k=1;
print("\n");
for($i=$s;$i>=0;$i-=$k){
  print(chr(13).$i.chr(32));
  sleep($k);
}
print(chr(32).chr(13));
}



function get_kri($a){
//кристаллы:
if (preg_match('/<p>кристаллы:<\/p>\s+<b>(.+?)<\/b/',$a,$zol)) {
         $gold=preg_replace('/\./','',$zol[1]);
         return $gold;
}

}

function dddzzzxxx() {

if (preg_match('/<p>золото:<\/p>\s+<b>(.+?)<\/b/',$a,$zol)) {
         $gold=preg_replace('/\./','',$zol[1]);

}




                         
if (preg_match('/trade center(.+?)\/table/',$a,$b)) {
//   print_r($b);
   if (preg_match('/row_1\'>(.+?)\/tr/',$b[0],$c)) {  
//      print_r($c);
      $d=preg_split('/<td[>\s]/',$c[1]);
//         print_r($d);
      if (count($d)>=5) {
	 preg_match('/[\d.]+/',$d[5],$e);
//         print_r($e);
         $s=preg_replace('/\./','',$e[0]);
//         print "$s\n";
	 preg_match('/alt=\'(.+?)\'/',$d[1],$f);
	 $lot=$f[1];
//         print_r($f);
	 if (preg_match('/href="(.+?)"/',$d[5],$g)) {
	    $href=$g[1];
	 } else { 
            $href=''; 
         }

//         print_r($g);
         print ("gold=$gold,'$lot',$href,за $s\n");

        if ($lot===ITEM){
            return $s;
        }
      }
   }
}
}//end function

?>