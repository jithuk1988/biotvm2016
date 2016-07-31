<?php
  
  $PageSecurity = 80;
include('includes/session.inc');
 $a=$_GET['str1'];
 $b=$_GET['str2'];
 $c=$_GET['str3'];
 $d=$_GET['str4'];
 $e=$_GET['str5'];
  $f=$_GET['str6'];
if($a!="" && $b!="" && $c!="" && $d!="" && $e!="" && $f!="")  
{
    $g=$_GET['Pancha'];
 $sql = "SELECT  `address1` FROM `debtorsmaster`
     WHERE `address1` = '".$a."'
      AND cid =".$b."
       AND stateid =".$c." 
       AND did =".$d." 
       AND LSG_type =".$e."
       AND LSG_name =".$f.""; //WHERE`address1`='".$a."' where debtorno='c71'
       if($e==3 and $g!=null )
        {
         $sql .=" AND `block_name` =$g "; 
        }
        $result=DB_query($sql,$db);
 
  // $count=DB_fetch_array($result);
  $k=0;
   while ($myrow = DB_fetch_array($result)) {
      $count=$myrow['address1'];
  
                        if($count!=null)
             {
                $k=1;
                           echo"<input type='hidden' name='stop' id='stop' value='2'>";   
             } 
        
   }       if($k==1)
              { echo "<div class=warn>This House/ Building No, House/Org Name already entered</div>"  ;  }
}
// $g=$_GET['str7'];

/*if($result= DB_query("SELECT * FROM debtorsmaster WHERE debtorsmaster.address1='$a' AND debtorsmaster.cid=$b AND debtorsmaster.stateid=$c AND debtorsmaster.did=$d AND debtorsmaster.LSG_type=$e AND debtorsmaster.LSG_name=$f",$db))
{
   echo "duplicate entry";
}*/
//echo" dsfgdsfgds";
/*if($_GET['id']!="")                              
{
        
 $name=$_GET['Address'];
$phone=$_GET['cou']; 
$mob=$_GET['stat']; 
$flag=$_GET['dist'];
}*/

?>