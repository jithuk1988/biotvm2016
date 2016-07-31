<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
include('includes/getinstallationdate.php');
        // echo"jjj";

    
    
   $year=$_GET['year'];
    $dist=$_GET['dist'];
                                 if($dist=='0')
                                 {
                                     $dist="IN/";
           }
            else
            {
                  $dist="IN/KL/".$dist; 
            }
     $sql="SELECT max(id) as maxid, fileno from bio_lsg_fileno where LSG_type='$lsg_type' AND LSG_id='$lsgname'";
     $result=DB_query($sql,$db);
     $myrow1=DB_fetch_array($result);
    $myrow1['maxid'];
    $file_ex=explode("/",$myrow1['fileno']);
    if(sizeof($file_ex)==5){$search_fn=$myrow1['fileno'];}
    else if(sizeof($file_ex)==6){$search_fn=$file_ex[0].$file_ex[1].$file_ex[2].$file_ex[3].$file_ex[4];}
    $new_file=$search_fn."/".$location;
    $sql1="SELECT bio_fileno.orderno,bio_fileno.debtorno,bio_fileno.fileno,debtorsmaster.name from bio_fileno,debtorsmaster 
        where debtorsmaster.debtorno=bio_fileno.debtorno
        AND ((bio_fileno.fileno LIKE '$dist%')"; 
    if($year!=ALL){
        $sql1.=" AND (bio_fileno.fileno LIKE '%$year%')";
    }
        $sql1.=" ) ORDER BY id";
    //echo$sql1;
    $result1=DB_query($sql1,$db);
    echo'<table><tr><td></td><td></td><td></td><td><input type="button" value="Print" onclick="custfilename_print();"></td></tr>';
  //  echo'<table><td ><input type="button" value="Print"></td></table>';
    echo'<tr><th>Sl No.</th><th>Order No.</th><th>Name</th><th>File Number</th></tr>';
    $k=0;
    $slno=1;
     while($row1=DB_fetch_array($result1)){
         if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          echo'<td>'.$slno.'</td>
        <td>'.$row1['orderno'].'</td><td>'.$row1['name'].'</td><td>'.$row1['fileno'].'</td></tr></table>';
         $slno++;
     }
    
    
    //echo '<tr><td>'.$_GET['loc1'].'</td></tr>';




?>
