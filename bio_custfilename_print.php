<?php 
$PageSecurity = 40;
include('includes/session.inc');

            $lsg=$_GET['lsgtype'];
            $lsgname=$_GET['lsgname1'];
            $district=$_GET['dist'];
            $year=$_GET['year1'];
            $location=$_GET['location'];
            $lsg_grama=$_GET['grama2'];
            
            /////////////////////////////
            
           if($lsg==2){$lsg_name=$lsgname;}
     if($lsg==3){$lsg_name=$lsg_grama;}
     if($lsg==1){
         if($lsgname==12){$lsg_name=1;}
         if($lsgname==6){$lsg_name=2;}
         if($lsgname==2){$lsg_name=3;}
         if($lsgname==8){$lsg_name=4;}
        if($lsgname==13){$lsg_name=5;}
     }
     
     if($lsg==1){$sql2="SELECT corporation as lsgname1 FROM bio_corporation where id='$lsg_name'";
     $type="Corporation";}
     if($lsg==2){ $sql2="SELECT municipality as lsgname1 FROM bio_municipality where id='$lsg_name'";
     $type="Municipality";}
     if($lsg==3){$sql2="SELECT name as lsgname1 FROM bio_panchayat where id='$lsg_name'";
     $type="Panchayat";}
     $result2=DB_query($sql2,$db);
     $myrow2=DB_fetch_array($result2);
     
     $sql_dis="SELECT district  FROM bio_district where did='$district' AND cid='1' AND stateid='14'";
     $result_dist=DB_query($sql_dis,$db);
     $myrow_dist=DB_fetch_array($result_dist);
     
     echo '<br/><br/><table width="800" border="1" align="center">
            <tr>

                <th colspan ="4">' . _('District') . ' :<b> ' . $myrow_dist['district']. '</b></th>
                
            </tr>
            <tr>
                <th><b>' . _('LSG Type:') . ' ' . $type . '</b></th>
                <th ><b>' . _('LSG Name:') . ' ' . $myrow2['lsgname1']. '</b></th>
                <th ><b>' . _('Financial Year:') . ' ' . $year. '</b></th>
                <th ><b>' . _('Location:') . ' ' . $location. '</b></th>
                     
            </tr>
            <tr >
                <th colspan ="4"><b><input type="button" id="print2" value="Print" onclick="hide_button();" align="right" ></b></th>
            </tr>
            </table>
            <br />';
            
            
     echo'<table width="800" border="1" align="center">';
     echo'<tr><th>Sl No.</th><th>Name</th><th>Contact No</th><th>File Number</th></tr>';
     $sql="SELECT max(id) as maxid, fileno from bio_lsg_fileno where LSG_type='$lsg' AND LSG_id='$lsg_name'";
     $result=DB_query($sql,$db);
     $myrow1=DB_fetch_array($result);
    $myrow1['maxid'];
    $file_ex=explode("/",$myrow1['fileno']);
    if(sizeof($file_ex)==5){$search_fn=$myrow1['fileno'];}
    else if(sizeof($file_ex)==6){$search_fn=$file_ex[0].$file_ex[1].$file_ex[2].$file_ex[3].$file_ex[4];}
    $new_file=$search_fn."/".$location;
    $sql1="SELECT bio_fileno.orderno,bio_fileno.debtorno,concat(custbranch.faxno,'<br>-',custbranch.phoneno) as 'phoneno',bio_fileno.fileno,debtorsmaster.name from bio_fileno,debtorsmaster,custbranch 
        where debtorsmaster.debtorno=bio_fileno.debtorno
        and   bio_fileno.debtorno=custbranch.debtorno
        AND ((bio_fileno.fileno LIKE '$new_file%')"; 
    if($year!=ALL){
        $sql1.=" AND (bio_fileno.fileno LIKE '%$year%')";
    }
        $sql1.=" ) ORDER BY bio_fileno.id asc";
    //echo$sql1;
    $result1=DB_query($sql1,$db);
            
            //////////////////////////////
            $k=0;
    $slno=1;
    
     while($row1=DB_fetch_array($result1)){
          echo'<tr><td>'.$slno.'</td>
                    <td>'.$row1['name'].'</td>        <td>'.$row1['phoneno'].'</td>
                    <td>'.$row1['fileno'].'</td>
                    </tr>';
         $slno++;
     }
echo'</table>';


/*echo'<tr><th width="100px">Name</th><th>File No.</th></tr>';
echo'<tr><td>1</td><td>ASSS</td></tr>';*/

?>


<script>
function hide_button(){
    
//   $('#print2').hide();
document.getElementById('print2').style.visibility='hidden';
//          alert("fffff");  
   window.print();
}
</script>