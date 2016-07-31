<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('CDM survey');
include ('includes/header.inc'); 
$lpg=1;
$j=1;
$value=10000;
       for($i=0;$i<$value;$i++)
           { 
               $valuem=$value-1;
               $k=$value/50;
             while($j<=$k)
              {
                $ave=$j*50;
              $avem=$ave-1;
              $s="";
              while($i<$ave)
              {       
         
            //    echo $s."'.$j.'";
                 $s[]="('".$lpg."')";
                 $lpg++;
                 if($i==$avem)
                 {
                     $z=join(",", $s);     
                 }
               
             $i++;
      
              } 
                
              $j++;
                 
             $sql_insert="INSERT INTO bio_test (`number`) VALUES $z";  
          $res_insert=DB_query($sql_insert,$db);
              }
              
                $h[]="('".$lpg."')"  ;
                $lpg++;
               if($i==$valuem) 
               {
                 $g=join(",", $h);  
                        $sql_insert="INSERT INTO bio_test (`number`) VALUES $g";  
                       $res_insert=DB_query($sql_insert,$db);       
               } 
                    
               

               
        } 
          /*  $valuem=$totrow-1;
               $k=$totrow/50;
             while($j<=$k)
              {
                $ave=$j*50;
              $avem=$ave-1;
              $s="";
              while($i<$ave)
              {
              echo "a";       
                // echo $i; 
            //    echo $s."'.$j.'";
              if($_POST['sel'.$i])
              {
            $s[]="('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";
              }
                 if($i==$avem)
                 {
                     $z=join(",", $s);     
                 }
               
             $i++;
      
              } 
                
              $j++;
                 
          echo   $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES  $z";  
          // $res_insert=DB_query($sql_insert,$db);
              }
                  if($_POST['sel'.$i])
              {
                $p[]="('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";
              }
               if($i==$valuem) 
               {
                 
               echo   $g=join(",", $p);  
                      echo  $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES  $g";  
                     //  $res_insert=DB_query($sql_insert,$db);       
               } 
                            */ 
?>
