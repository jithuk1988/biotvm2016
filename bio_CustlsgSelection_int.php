<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
         
if($_GET['country']){
    
         echo "<td>State*</td><td>";
     $cid=$_GET['country'];
     $state=$_GET['state'];
     $sql="SELECT * FROM bio_state WHERE cid='$cid' ORDER BY stateid";
     $result=DB_query($sql,$db);
      

    
  echo '<select name="State" id="state" tabindex=5 style="width:222px" onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
         // $state=$myrow1['state'];
  if ($myrow1['stateid']==$_POST['state'])
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
}




if($_GET['state']){      
               
    $state=$_GET['state']; $country1=$_GET['country1'];
         echo"<td>District*</td><td>";
            $sql="SELECT * FROM bio_district WHERE bio_district.stateid=$state AND bio_district.cid=$country1 ORDER BY did";
    $result=DB_query($sql,$db);
  echo '<select name="District" id="Districts" style="width:222px" tabindex=6 ">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>';
}                                    

if($_GET['taluk']){  

    $district=$_GET['district']; $state=$_GET['state2']; $country1=$_GET['country1'];
    
    echo"<td>Taluk*</td>";
    $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$country1 AND bio_taluk.state=$state AND bio_taluk.district=$district ORDER BY bio_taluk.taluk ASC";
    $result=DB_query($sql,$db);
  echo '<select name="taluk" id="taluk" style="width:222px"  tabindex=11 onchange="showvillage(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$_POST['taluk'])
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
    echo $myrow1['id'] . '">'.$myrow1['taluk'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>'; 
    
}

if($_GET['village']){  
    
    

    $district=$_GET['district']; $state=$_GET['state2']; $country1=$_GET['country1']; $taluk=$_GET['village'];
    
    echo"<td>Village</td><td>";
    $sql="SELECT * FROM bio_village WHERE bio_village.country=$country1 AND bio_village.state=$state AND bio_village.district=$district AND bio_village.taluk=$taluk ORDER BY bio_village.village ASC";
    $result=DB_query($sql,$db);
  echo '<select name="village" id="village" tabindex=12 style="width:222px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$_POST['village'])
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
    echo $myrow1['id'] . '">'.$myrow1['village'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>'; 
    
}


if($_GET['block']){ 
    
    $country1=$_GET['country1'];
    $state=$_GET['state1'];      
    $district=$_GET['district'];
    
    
    if($_GET['block']==1)            //Corporation
    {
        
        $sql="SELECT * FROM bio_corporation WHERE country=$country1 AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];

          if($cid==1 && $sid==14)  
          {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td  width=159px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:222px" tabindex=8>';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td>';   
                    echo '</tr></table>';      
          }
        
    }       
    elseif($_GET['block']==2)        //Municipality
    {
        echo '<table align=left ><tr><td  width=159px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='$country1' AND state='$state' AND district='$district'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:222px" tabindex=8>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['lsgName'])
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
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</td></tr></table>'; 
        
        
    }   
    elseif($_GET['block']==3)          //block Panchayath
    {
         echo '<table align=left ><tr><td width=159px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country=$country1 AND state=$state AND district=$district ORDER BY bio_block.block ASC";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:222px" tabindex=8 onchange="showgramapanchayath(this.value)">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['lsgName'])
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
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>';
      
      echo '<tr id="showgramapanchayath"><td>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country1 AND state=$state AND district=$district ORDER BY bio_panchayat.name ASC";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:222px" tabindex=9>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['gramaPanchayath'])
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
       
      
      echo'</table>';                 
   }      
          
}   


if($_GET['grama']){                  //grama panchayath 
    
    
         $blockid=$_GET['grama'];
         $country1=$_GET['country1'];   $state=$_GET['state2'];  $district=$_GET['district'];

      echo '<tr><td >' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country1 AND state=$state AND district=$district AND block=$blockid ORDER BY bio_panchayat.name ASC";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:222px" tabindex=9>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['gramaPanchayath'])
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';              
}


?>
