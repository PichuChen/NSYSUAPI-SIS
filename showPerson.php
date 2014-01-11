<?php
/*
   Copyright 2013 Pichu, NSYSU-CDPA

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

//$queryUri = 'http://140.117.147.234/SMS/student_list/show_person.php?ID=B983040003&GPID=07';
$queryUri .= '&ID=' . $SID;
//Set Option
$opts = array(
  'http'=>array(
    'method'=>"GET",
//    'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
//		'Cookie: ASPSESSIONIDCCRCQQDD=ECMFONIBGBMAMKKHJFOGEEFE' ,
//    'content'=> "SID=$SID&PASSWD=$PASSWD&ACTION=0&INTYPE=1",
    'ignore_errors' => true,
  )
);
$ctx = stream_context_create($opts);
//Read File
$handle = fopen($queryUri, "rb",false,$ctx);
$str = stream_get_contents($handle);
//$str=iconv("big5","UTF-8",$str);

$str = preg_replace('(<!DOCTYPE html(.*)<!DOCTYPE html)s','<!DOCTYPE html',$str);
$str = preg_replace('(</html>(.*)</html>)s','</html>',$str);


$doc = new DOMDocument();
$doc->loadHTML($str);
$tableList = $doc->getElementsByTagName('table');
$rowList   = $tableList->item(0)->getElementsByTagName('tr');
$ans = array();
foreach($rowList as $thisRow){
	//$val = $thisRow->nodeValue;
	$pair = $thisRow->getElementsByTagName('td');
	$key = str_replace(array(' ',"\t","\0xc2a0"),'',$pair->item(0)->nodeValue);
	 $key = bin2hex($key);
	$key = str_replace('c2a0','',$key);
	$key = hex2bin($key);
	$val = str_replace(array(' ',"\t","\r","\n"),'',$pair->item(1)->nodeValue);
	$ans[$key] = $val;

//	var_dump($val);

}
return $ans;

