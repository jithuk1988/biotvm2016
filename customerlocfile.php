<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
include('includes/getinstallationdate.php');
        // echo"jjj";
if($_GET['lsg']){  
     $lsg=$_GET['lsg'];
     $lsgname=$_GET['lsgname1'];
     $grama=$_GET['grama1'];
     $cid=$_GET['country1'];
     $stateid=$_GET['state1'];
     $did=$_GET['dist'];
     if($lsg==2){$lsgname1=$lsgname;}
     if($lsg==3){$lsgname1=$grama;}
     if($lsg==1){
         if($did==12){$lsgname1=1;}
         if($did==6){$lsgname1=2;}
         if($did==2){$lsgname1=3;}
         if($did==8){$lsgname1=4;}
        if($did==13){$lsgname1=5;}
     }
     
     echo '<table align=left ><tr><td width=200px>' . _('Select Location') . ':</td>'; 
            $sql_location="select loc from bio_lsg_fileno where LSG_type='$lsg' AND LSG_id='$lsgname1' ";
         $result_location=DB_query($sql_location,$db);
        echo '<td><select name="loc" id="loc" style="width:190px" tabindex=11 >';
         $f=0;
         while($myrow1=DB_fetch_array($result_location))
         {
         if ($myrow1['loc']==$_POST['loc'])
         {
         echo '<option selected value="';
         } else
         {
         if ($f==0)
         {
         echo '<option>';
         }
         echo '<option value="';
         }
         echo $myrow1['loc'] . '">'.$myrow1['loc'];
         echo '</option>';
         $f++;
         }
         echo '</select></td>';
      echo'</td></tr></table>';
      
    
    echo '<td width=200px> Financial year:</td><td><select name="year" id="year" style="width:190px" tabindex=11 onchange="showfilelocation(this.value)">';
      echo '<option value= ></option>';
      
      echo '<option value="2000-01">2000-01</option>';
      echo '<option value="2001-02">2001-02</option>';
      echo '<option value="2002-03">2002-03</option>';
      echo '<option value="2003-04">2003-04</option>';
      echo '<option value="2004-05">2004-05</option>';
      echo '<option value="2005-06">2005-06</option>';
      echo '<option value="2006-07">2006-07</option>';
      echo '<option value="2007-08">2007-08</option>';      
      echo '<option value="2008-09">2008-09</option>';
      echo '<option value="2009-10">2009-10</option>';
      echo '<option value="2010-11">2010-11</option>';
      echo '<option value="2011-12">2011-12</option>';
      echo '<option value="2012-13">2012-13</option>';
      echo '<option value="2013-14">2013-14</option>';
      echo '<option value="2014-15">2014-15</option>';
      echo '<option value="2015-16">2015-16</option>';
      echo '<option value="2016-17">2016-17</option>';
      echo '<option value="2017-18">2017-18</option>';
      echo '<option value="2018-19">2018-19</option>';
      echo '<option value="2019-20">2019-20</option>';
      echo '<option value="2020-21">2020-21</option>';
      echo '<option value="2021-22">2021-22</option>';
      echo '<option value="2022-23">2022-23</option>';
      echo '<option value="2023-24">2023-24</option>';
      echo '<option value="ALL">ALL</option>';
       echo '</select></td>';

}
if($_GET['year']){
    
    
    $year=$_GET['year'];
    $location=$_GET['loc1'];
    $lsg_name=$_GET['lsgname1'];
    $lsg_grama=$_GET['grama1'];
    $lsg_type=$_GET['lsg1'];
    if($lsg_type==2){$lsgname=$lsg_name;}
     if($lsg_type==3){$lsgname=$lsg_grama;}
     if($lsg_type==1){
         if($lsg_name==12){$lsgname=1;}
         if($lsg_name==6){$lsgname=2;}
         if($lsg_name==2){$lsgname=3;}
         if($lsg_name==8){$lsgname=4;}
        if($lsg_name==13){$lsgname=5;}
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
        AND ((bio_fileno.fileno LIKE '$new_file%')"; 
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
}



?>
