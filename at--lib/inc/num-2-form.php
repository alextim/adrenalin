<?php
//declare(strict_types=1);
namespace AT_Lib;


/**
* https://wp-kama.ru/id_8475/poleznye-php-kody-dlya-opytnyh.html/comment-page-1#kak-razbit-predlozhenie-na-slova-v-php
*
* Использование:
*
at_num_form2(
  get_comments_number(),
  // варианты написания для количества 1, 2 и 5 
  array( 'опубликован', 'опубликовано', 'опубликовано' ),
  array( 'комментарий', 'комментария', 'комментариев' )
);
*
*/
function num2form_2( $number, $before, $after ) : string {
  $cases = [2,0,1,1,1,2];
  return $before[($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)]].' '.$number.' '.$after[($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)]];
}


/**
* Функция склонение после числительных
*
* Использование:
*
$array = array(1, 2, 5);
	foreach($array as $num){
	echo at_num_form($num, "день", "дня", "дней") . "<br>";
}

1 день
2 дня
5 дней

*/
function num2form( $number, $forma1, $forma2, $forma3 ) : string{
	if( $number == '0' )                         $forma = $forma3;
	elseif( ($number >= 5) && ($number <= 20) )  $forma = $forma3;
	elseif( preg_match('/[056789]$/', $number) ) $forma = $forma3;
	elseif( preg_match('/[1]$/', $number) )      $forma = $forma1;
	elseif( preg_match('/[234]$/', $number) )    $forma = $forma2;

	return "$number $forma";
}