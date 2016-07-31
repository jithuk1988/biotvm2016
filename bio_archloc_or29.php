<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['lsg']){  
     $lsg=$_GET['lsg'];
     $lsgname=$_GET['lsgname1'];
     $grama=$_GET['grama1'];
     $cid=$_GET['country1'];
     $stateid=$_GET['state1'];
     $did=$_GET['dist'];
     if($_GET['lsg']==3){$lsgname=$grama;}
     if($_GET['lsg']==1){
         
      
     $sql="select max(id) as maxid from bio_lsg_fileno where LSG_type='".$_GET['lsg']."' AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$did)";
     $result=DB_query($sql,$db);
         $row=DB_fetch_array($result,$db);
          $sql1="SELECT * FROM bio_lsg_fileno where LSG_type=$lsg AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$did )  ";//AND id='".$row['maxid']."'//AND (lastfileno is not NULL AND lastfileno!=0)
        echo"<legend>File Location</legend>";
        echo"<tr><th>Location</th><th>Room</th><th>Rack</th><th>Bin</th><th>No. of files</td></tr>";
        $result1=DB_query($sql1,$db);
        $k=0; //row colour counter
        $slno=-1;  
while($row1=DB_fetch_array($result1,$db)){
    
    $lf=$row1['lastfileno'];
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
   $slno++;
   echo"<td>L".$slno."</td>";
   
   
   if( $row1['lastfileno']==0)
        {
            echo"<td><input type='text' name='room1' id='room1' tabindex=4 value='".$row1['room']."' onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 value='".$row1['rack']."' onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 value='".$row1['bin']."' onkeyup='' ></td>";
   echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 value='".$row1['lastfileno']."' onkeyup='' ></td>";
   echo"<td><input type='submit' name='submit1' id='submit1' value='submit' onclick=' if(validate()==1)return false;'></td></tr>";
        }
        
        else{
            
        echo"<td>".$row1['room']."</td>";
        echo"<td>".$row1['rack']."</td>";
        echo"<td>".$row1['bin']."</td>";
        echo"<td>".$row1['lastfileno']."</td>";
        }
              

}
$sql2="SELECT count(*) as count FROM bio_lsg_fileno WHERE LSG_type=$lsg  AND ( lastfileno=0 OR  lastfileno is NULL) AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$did ) ";
        
         $result2=DB_query($sql2,$db);
         $row2=DB_fetch_array($result2);
       $count=$row2['count'];
if($count==0){
            
            echo"<tr><td><input type='text' name='room1' id='room1' tabindex=4 onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 onkeyup='' ></td>";
   echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 onkeyup='' ></td>";
   echo "<td><input type='submit' name='newloc' id='newloc' value='Add new' onclick=' if(validate()==1)return false;'></td></tr>";
        }

 //echo $lf;       

     }
else{
        $sql="select max(id) as maxid from bio_lsg_fileno where LSG_type='".$_GET['lsg']."' AND LSG_id=$lsgname";
          $result=DB_query($sql,$db);
         $row=DB_fetch_array($result,$db);
         //echo $row['maxid'];                                                                   //AND id=".$row['maxid']."
        $sql1="SELECT * FROM bio_lsg_fileno where LSG_type='".$_GET['lsg']."' AND LSG_id=$lsgname ";
         echo"<legend>File Location</legend>";
        echo"<tr><th>Location</th><th>Room</th><th>Rack</th><th>Bin</th><th>Last file no.</td></tr>";
        $result1=DB_query($sql1,$db);
        $k=0; //row colour counter
        $slno=-1;  
while($row1=DB_fetch_array($result1,$db)){
    
    $lf=$row1['lastfileno'];
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
   $slno++;
   echo"<td>L".$slno."</td>";
   
   
   if( $row1['lastfileno']==0)
        {
            echo"<td><input type='text' name='room1' id='room1' tabindex=4 value='".$row1['room']."' onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 value='".$row1['rack']."' onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 value='".$row1['bin']."' onkeyup='' ></td>";
   echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 value='".$row1['lastfileno']."' onkeyup='' ></td>";
   echo"<td><input type='submit' name='submit1' id='submit1' value='submit' onclick=' if(validate()==1)return false;'></td></tr>";
        }
        
        else{
            
        echo"<td>".$row1['room']."</td>";
        echo"<td>".$row1['rack']."</td>";
        echo"<td>".$row1['bin']."</td>";
        echo"<td>".$row1['lastfileno']."</td>";
        } 
}

$sql2="SELECT count(*) as count FROM bio_lsg_fileno WHERE LSG_type=$lsg  AND ( lastfileno=0 OR  lastfileno is NULL) AND LSG_id=$lsgname ";
        
         $result2=DB_query($sql2,$db);
         $row2=DB_fetch_array($result2);
       $count=$row2['count'];
if($count==0){
            
            echo"<tr><td><input type='text' name='room1' id='room1' tabindex=4  onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 onkeyup='' ></td>";
   echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 value=0 onkeyup='' ></td>";
   echo "<td><input type='submit' name='newloc' id='newloc' value='Add new'  onclick=' if(validate()==1)return false;'></td></tr>";
        }


}

}




?>
