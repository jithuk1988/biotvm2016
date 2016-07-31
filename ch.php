<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealer');  
include('includes/header.inc');
$sq="select max(id) as dt from yr";
    //   $rst=DB_query($sq,$db);  
      //             $dtf='2012-08-17';
        //         $mr=DB_fetch_array($rst);
                // $mx=$mr['dt'];
               
                $z=31    ;
                $x=31     ;
                $c=30      ;
                $v=29       ;
                $n=30 ;
                $q=29;
                $w=30;
                $t=31 ;
                $y=31;
                $o=31 ;
                $l=31 ;
                $s=32  ;
                
   $year=1210;             
 $as=8036;       
 $p=$z;
  $z=   $as+$z;
         for($i=$as,$d=1;$i<=$z,$d<=$p;$i++,$d++)
  {
      $sql="update yr set mal_mnth=1, mal_day=$d,mal_year=$year where id=$i";
       DB_query($sql,$db); 
       $m=$i; 
 }      
 echo $m=$m+1;                      //32                          
                          $p=$z+$x;
          for($i=$m,$d=1;$i<=$p,$d<=$x;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=2, mal_day=$d,mal_year=$year  where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
 
  echo $m=$m+1;                      //32                          
                          $p=$z+$x+$c;
          for($i=$m,$d=1;$i<=$p,$d<=$c;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=3, mal_day=$d,mal_year=$year  where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                          $p=$z+$x+$c+$v;
          for($i=$m,$d=1;$i<=$p,$d<=$v;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=4, mal_day=$d,mal_year=$year  where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                        $p=$z+$x+$c+$v+$n;     
          for($i=$m,$d=1;$i<=$p,$d<=$n;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=5, mal_day=$d,mal_year=$year  where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
$p=$z+$x+$c+$v+$n+$q;
          for($i=$m,$d=1;$i<=$p,$d<=$q;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=6, mal_day=$d,mal_year=$year  where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                          $p=$z+$x+$c+$v+$n+$q+$w;  
          for($i=$m,$d=1;$i<=$p,$d<=$w;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=7, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                                                $p=$z+$x+$c+$v+$n+$q+$w+$t;   
          for($i=$m,$d=1;$i<=$p,$d<=$t;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=8, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                                  $p=$z+$x+$c+$v+$n+$q+$w+$t+$y;     
          for($i=$m,$d=1;$i<=$p,$d<=$y;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=9, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                            $p=$z+$x+$c+$v+$n+$q+$w+$t+$y+$o;    
          for($i=$m,$d=1;$i<=$p,$d<=$o;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=10, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                            $p=$z+$x+$c+$v+$n+$q+$w+$t+$y+$o+$l;    
          for($i=$m,$d=1;$i<=$p,$d<=$l;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=11, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
  echo $m=$m+1;                      //32                          
                               $p=$z+$x+$c+$v+$n+$q+$w+$t+$y+$o+$l+$s;    
          for($i=$m,$d=1;$i<=$p,$d<=$s;$i++,$d++)
  {                         // $k=1;
      $sql="update yr set mal_mnth=12, mal_day=$d,mal_year=$year   where id=$i";
       DB_query($sql,$db); 
       $m=$i;
     //  $k++;
      // $m=$i;  
       
 }
 
 

 
 
  /*
     echo $i;  
           for($i=1;$i<=30;$i++)
  {
       DB_query($sql,$db);  
 }   
                    for($i=1;$i<=30;$i++)
  {
       DB_query($sql,$db);  
 }   
                for($i=1;$i<=29;$i++)
  {
       DB_query($sql,$db);  
 }   
                for($i=1;$i<=30;$i++)
  {
       DB_query($sql,$db);  
 }   
               for($i=1;$i<=30;$i++)
  {
       DB_query($sql,$db);  
 }     
          for($i=1;$i<=30;$i++)
  {
       DB_query($sql,$db);  
 }
               for($i=1;$i<=31;$i++)
  {
       DB_query($sql,$db);  
 }   
                    for($i=1;$i<=31;$i++)
  {
       DB_query($sql,$db);  
 }   
                for($i=1;$i<=32;$i++)
  {
       DB_query($sql,$db);  
 }   
                for($i=1;$i<=31;$i++)
  {
       DB_query($sql,$db);  
 }                            
        
/*			$dtf="2025-08-17";
		for($i=0;$i<9497;$i++)
{
$sql="insert into yr(date) values('".$dtf."')";
 $dtf=strftime("%Y-%m-%d", strtotime("$dtf +1 day"));
 DB_query($sql,$db);
 }
*/


?>
