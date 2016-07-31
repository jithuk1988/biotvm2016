<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('ID Proof');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ID PROOF</font></center>';
    
    
    if($_POST['submit'])
    {                      
        if($_POST['leadid']!=""){
        $sql_update="UPDATE bio_leads SET id_type='".$_POST['idtype']."',id_no='".$_POST['idno']."' WHERE leadid='".$_POST['leadid']."'";
        DB_query($sql_update,$db);            

        }
        elseif($_POST['debtorno']!="")
        {
            $sql1="SELECT * FROM bio_oldidproof WHERE debtorno='".$_POST['debtorno']."'";
            $result1=DB_query($sql1,$db);
            $count1=DB_num_rows($result1);
            if($count1<=0) {
        $sql_insert="INSERT INTO bio_oldidproof (debtorno,id_type,id_no) VALUES ('".$_POST['debtorno']."','".$_POST['idtype']."','".$_POST['idno']."')" ;
        DB_query($sql_insert,$db);
            }                              

        }
        

       ?>
     <script>

      window.close();
     
     </script>
     <?php  
        
    }  


if($_GET['leadid']!='')
{       
    $leadid=$_GET['leadid'];
    $_SESSION['leadid']=$_GET['leadid'];         
    
    
    $sql="SELECT id_type,id_no FROM bio_leads WHERE leadid=".$leadid;
    $result=DB_query($sql,$db);
    $row=DB_fetch_array($result);
    
}elseif($_GET['debtorno']){
    
    $debtorno=$_GET['debtorno'];
    $_SESSION['debtorno']=$_GET['debtorno'];
    
    $sql="SELECT id_type,id_no FROM bio_oldidproof WHERE debtorno ='$debtorno'";
    $result=DB_query($sql,$db);
    $row=DB_fetch_array($result);
    
}else{
    $leadid=$_SESSION['leadid'];
    $debtorno=$_SESSION['debtorno'];
}
    

    
    
    
    
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  
echo '<fieldset style="height:250px;width:300px">';   
echo '<legend><b>ID Selection</b></legend>';  
echo "</legend>"; 
echo '<table style=width:80%><tr>'; 

    
    
        echo '<td>Id Type</td>';
        echo'<td><select name="idtype" id="idtype" style="width:140px">';
        echo '<option value=0></option>'; 
        $sql1="select * from bio_identity";
        $result1=DB_query($sql1,$db);
        while($row1=DB_fetch_array($result1))
        {
        //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
            if ($row1['ID_no']==$row['id_type'])
               {
                 echo '<option selected value="';
               } else 
               { 
                   echo '<option value="'; 
               }
               echo $row1['ID_no'] . '">'.$row1['ID_type'];
               echo '</option>';  
        }
        echo '</select></td>';
echo '</tr>';

echo '<tr></tr><tr></tr>';

echo '<br />';



echo '<tr><td>ID No:</td>';
echo'<td><input type=text name=idno id=idno value="'.$row['id_no'].'"></td>';
echo'</tr><tr></tr><tr></tr>';

echo'<input type=hidden name=leadid id=leadid value='.$leadid.'>';
echo'<input type=hidden name=debtorno id=debtorno value='.$debtorno.'>';

echo '<tr><td></td><td><input type=submit name=submit id=submit value=Submit></td></tr>';



echo '</table>';
echo'</fieldset>';       

    
?>
