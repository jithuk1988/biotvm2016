<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
         
if($_GET['country']){
    
         echo "<td width=45%>State*</td><td width=55%>";
     $cid=$_GET['country'];
     $state=$_GET['state'];
     $sql="SELECT * FROM bio_state WHERE cid='$cid' ORDER BY stateid";
     $result=DB_query($sql,$db);
      

    
  echo '<select name="State" id="state" tabindex=7 style="width:164px" onchange="showdistrict(this.value)">';
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
         echo"<td width=45%>District*</td><td width=55%>";
            $sql="SELECT * FROM bio_district WHERE bio_district.stateid=$state AND bio_district.cid=$country1";
    $result=DB_query($sql,$db);
  echo '<select name="District" id="Districts" style="width:164px" tabindex=11 ">';
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
    
    echo"<td width=45%>Taluk</td><td width=55%>";
    $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$country1 AND bio_taluk.state=$state AND bio_taluk.district=$district";
    $result=DB_query($sql,$db);
  echo '<select name="taluk" id="taluk" style="width:164px" tabindex=16 onchange="showvillage(this.value)">';
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
    
    echo"<td width=45%>Village</td><td width=55%>";
    $sql="SELECT * FROM bio_village WHERE bio_village.country=$country1 AND bio_village.state=$state AND bio_village.district=$district AND bio_village.taluk=$taluk";
    $result=DB_query($sql,$db);
  echo '<select name="village" id="village" style="width:164px" tabindex=17>';
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
                    echo '<table  width=100% align=left ><tr><td width=45%>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:164px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td>';   
                    echo '</tr></table>';      
          }
        
    }       
    elseif($_GET['block']==2)        //Municipality
    {
        echo '<table  width=100% align=left><tr><td width=45%>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='$country1' AND state='$state' AND district='$district'";
        $result=DB_query($sql,$db);
        
        echo '<td width=55%><select name="lsgName" id="lsgName" style="width:164px" tabindex=13>';
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
         echo '<table align=left  width=100% ><tr><td width=45%>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country=$country1 AND state=$state AND district=$district";
         $result=DB_query($sql,$db);
         
         echo '<td id="showgramapanchayath" width=55%><select name="lsgName" id="lsgName" style="width:164px" tabindex=13 onchange="showgramapanchayath(this.value)">';
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
      
      echo '<tr id="showpanchayath"><td width=45%>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country1 AND state=$state AND district=$district";
         $result=DB_query($sql,$db);
         
         echo '<td width=55%><select name="gramaPanchayath" id="gramaPanchayath" style="width:164px" onchange="showblocks(this.value)" tabindex=14>';
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

   echo '<tr><td width=45%>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country1 AND state=$state AND district=$district AND block=$blockid ";
         $result=DB_query($sql,$db);
         
         echo '<td width=55%><select name="gramaPanchayath" id="gramaPanchayath" style="width:164px" onchange="showblocks(this.value)"  tabindex=14>';
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
