<?php
//sanskrit/mwquery/mwquery_monierMulti.php
//ejf 2010-01-28
//ejf Oct 1, 2012  Adapated for sanskrit1d
//June 16, 2014. 'accent' not used with AP, but vestiges left from PWG remain
include("../utilities/transcoder.php");
include("../webtc/disp.php");
$data = $_POST['data'];
$utilchoice = $_POST['utilchoice'];
$filter0 = $_POST['filter'];
$accent = $_POST['accent']; 
$dbg=False;
dbgprint($dbg,"accent = $accent\n");
$dbg=False;
if (!$accent) {$accent='no';}

$filter = transcoder_standardize_filter($filter0);

//****************
$meta = "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">";
echo "$meta\n";
//****************

$nxmlnew=0;
$key;
$prevkey="";
$n = -1;
$matches = array();
$noLit="off";
$noParen="off";
$ntab = 0;
// remove escaped \".  For some reason, these are being inserted by
//  the 'print' statement in mwquery_gatherMW.php
$data = preg_replace('/\\\\"/','"',$data);
// the '/s' is to skip over \n.
preg_match_all('/<H.*?>.*?<key1>(.*?)<\/key1>.*?<\/H.*?>/s',$data,$matchall);
$matches0=$matchall[0];
$matches1=$matchall[1];
$nmatches0=count($matches0);
for ($imatch=0;$imatch<$nmatches0;$imatch++) {
 $xmlnew = $matches0[$imatch];
 $xmlnew = trim($xmlnew);
 $key = $matches1[$imatch];
 if ($prevkey == '') {
  // first record
  $prevkey = $key;
  $n=-1;
 }
 if ($key == $prevkey) {
  $n++;
  $matches[$n]=$xmlnew;
 }else {
  $ntab++;
  print_table($filter,$prevkey,$ntab,$n+1,$matches,$accent);
  $prevkey = $key;
  $n=0;
  $matches=array();
  $matches[$n]=$xmlnew;
 }
}
if ($n != -1) {
 $ntab++;
 print_table($filter,$prevkey,$ntab,$n+1,$matches,$accent);
}
dbgprint($dbg,"query_multi is finished\n");
exit;
function print_table($filter,$key,$ntab,$nmatchesin,$matchesin,$accent) {
 $dbg=False;
 $table0 = "<span class='key' id='record_$ntab' /></span>\n";
 $matches=array();
 for($i=0;$i<$nmatchesin;$i++) {
  $matches[$i]=$matchesin[$i];
 }
 //basicDisplaySetAccent($accent);
 $table = basicDisplay($key,$matches,$filter);
 dbgprint($dbg,"table=$table\n");
 $table1 = transcoder_processElements($table,"slp1",$filter,"SA");
 echo $table0;
 echo $table1;
}
function dbgprint($dbg,$text) {
 if (!$dbg) {return;}
 $filename = "dbg_query_multi.txt";
 $fp1 = fopen($filename,"a");
 fwrite($fp1,"$text");
 fclose($fp1);
}

?>
