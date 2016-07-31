<?php
  $PageSecurity=80;
  $pagetype=1;
include('includes/session.inc');
 $title = _('Stock Transfer'); 
 include('includes/header.inc');
 include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
 
 ////////////////////////////////////
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Stock Transfer') . '</p>';
    
 echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
    
      if($_POST['select'])
        {
          $floc=$_POST['loc1'];
          $tloc=$_POST['loc2'];
 
        }
      else
      {
       $floc=$_SESSION['UserStockLocation'];
       $tloc=$_SESSION['UserStockLocation'];
      }  
 
 echo"<table class=selection style='border:1px solid #F0F0F0;width:70%'; >";   
 echo"<tr>";
//location(this.value)

echo "<tr><td>Transfer From:</td><td><select name='locatA' id='locatA' onchange='show3(this.value)'>";
$locat=0;
$locat=$_POST['locatA'];
          if($locat==1)
          {
              echo   '<option value=1>Production center</option>
              <option value=0>Office</option>
              <option value=2>Dealer</option>';
          }
          else if($locat==2)
          {
              echo   '<option value=2>Dealer</option>
              <option value=1>Production center</option>
              <option value=0>Office</option>
              ';
          }
          else
          {
              echo '<option value=0>Office</option>
            <option value=1>Production center</option>
             <option value=2>Dealer</option>';
          }

echo '</select></td>
<td id=locat1><select name="loc1" id="loc1"  style="width:190px" tabindex=1  ">';
  $sql="SELECT loccode,locationname FROM  locations WHERE managed='".$locat."' ";

$rst=DB_query($sql,$db);

   while($myrowc=DB_fetch_array($rst))
       {
         if ($myrowc[loccode]==$floc)
        {  
         echo '<option selected value="';
        }
        else
         {
            echo '<option value="';
         } 
         echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
      }
  echo '</select></td>';
 echo'</td>'; 
 echo '<td>Transfer To:</td><td><select name=locatB id=locatB onchange="show4(this.value)">';
$locat1=0;
$locat1=$_POST['locatB'];
          if($locat1==1)
          {
              echo   '<option value=1>Production center</option>
              <option value=0>Office</option>
              <option value=2>Dealer</option>';
          }
          else if($locat1==2)
          {
              echo   '<option value=2>Dealer</option>
              <option value=1>Production center</option>
              <option value=0>Office</option>
              ';
          }
          else
          {
              echo '<option value=0>Office</option>
            <option value=1>Production center</option>
             <option value=2>Dealer</option>';
          }
echo '</select></td><td id=locat2><select name="loc2" id="loc2" style="width:190px" tabindex=1 ">';
  $sql="SELECT loccode,locationname FROM  locations WHERE managed='".$locat."'
  ";


$rst=DB_query($sql,$db);

   while($myrowc=DB_fetch_array($rst))
      {
       if ($myrowc[loccode]==$tloc)
        {  
         echo '<option selected value="';
        }
        else 
        {
        echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
     }
  echo '</select></td>';
 echo'</td>'; 
 /*echo "<td>Type :</td><td>";
 echo "<select name=type>";
 echo "<option value=1>plant</option>";
  echo "<option value=2>Purchased Items</option>";
  echo "<select>";*/

 echo "<td>Type</td><td><select name='type' id='type' onchange='show(this.value)'>";

     if($_POST['type']=='A')
       {
        echo "<option selected value='A'>plant</option>
        <option value='B'>Purchased</option>";
        } 
     else if($_POST['type']=='B')
        {
         echo "<option selected value='B'>Purchased</option>
         <option value='A'>plant</option>";
         }
     else
         {echo "<option value='A'>plant</option>
         <option value='B'>Purchased</option>";
         }
         echo '</select>';
  if($_POST['type']=='B')
  {
      
      echo '<tr id=combo><td>Main Category<td>';  
       
     $sql="SELECT
        maincatid,
        maincatname
        FROM bio_maincategorymaster WHERE maincatid!=1";
echo "<select name='main' id='main' style='width:190px;' onchange='show2(this.value)'>";
        $rst=DB_query($sql,$db);
           
      /* if ($myrowc[loccode]==$tloc)
        {  
         echo '<option selected value="';
        }
        else 
        {
        echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
     }*/

      //  echo "<option value=0> </opton>";
         while($myrow2=DB_fetch_array($rst))
          {
              if ($myrow2[maincatid]==$_POST['main'])
                  {  
                    echo '<option selected value="';
                  }
             else 
                 {
                  echo '<option value="';
                 } 
    echo $myrow2[maincatid].'">'.$myrow2[maincatname].'</option>';
      }   echo '</select></tr>';  //$myrow2[maincatname]//$myrow2[maincatid]
         
       echo '<tr id=combo2><td>Description<td>'; 
        echo "<select name='sub' id='sub' style='width:190px' >";
        $sub=$_POST['sub'];
        $sql="SELECT
      maincatid,
      subcatid,
       stockcategory.categorydescription
        FROM bio_maincat_subcat,stockcategory
         where bio_maincat_subcat.maincatid='".$_POST['main']."' AND stockcategory.categoryid=bio_maincat_subcat.subcatid ";
       $rst=DB_query($sql,$db);
         echo '<option value=0></option>';//
       while($myrowc=DB_fetch_array($rst))
        {
          if ($myrowc[subcatid]==$_POST['sub'])
           {  
              echo '<option selected value="';
           }
        else 
           {
            echo '<option value="';
           } 
      echo $myrowc[subcatid].'">'.$myrowc[categorydescription].'</option>';
       }
  
       echo '</select></td>'; 
    } 
        else
        {   
 echo  '</td></tr>';
 echo "<tr id=combo><td></td><td></td></tr>";
 echo "<tr id=combo2><td></td><td></td></tr>"; 
        }
 echo '</table>';

 echo "<center><input type=submit name=select value=select></center>";
  

 
 if($_POST['loc1']!=""  AND $_POST['loc2'] AND $_POST['type'])  
  { 
     if($_POST['loc1']==$_POST['loc2'])
     {
       echo "<div class=warn>Choose defferent locations to transfer</div>";
       //('".$_POST['stock'.$i]."',".$_POST['loc1'].",  ".$_POST['loc2'].",1)";
    
     }
     else
     {
     if($_POST['loc1']!=""  AND $_POST['loc2'] AND $_POST['type']=='A')
        {
    
         if($_POST['sel_item'])
           {
            
     
        for($i=0;$i<$_POST['totrow'];$i++)
             {
           // echo $_POST['sel'.$i];
              if($_POST['sel'.$i]=='on')
                {
                   $sql_insert="INSERT INTO  bio_stocktransfer(stockid,fromloc,toloc,qty,serialno,type) values ('".$_POST['stock'.$i]."',".$_POST['loc1'].",
                   ".$_POST['loc2'].",1,'".$_POST['serial'.$i]."',10)";
                  $res_insert=DB_query($sql_insert,$db);
                }
             }     

           }
            $SQL="SELECT
           `stockmaster`.`stockid`,
           `stockmaster`.`description`
           , `locstock`.`quantity`,
            stockserialitems.serialno
            FROM
             locations
             INNER JOIN `locstock` 
               ON (`locations`.`loccode` = `locstock`.`loccode`)
               INNER JOIN `stockmaster` 
               ON (`locstock`.`stockid` = `stockmaster`.`stockid`)
               INNER JOIN stockserialitems 
                ON (`locstock`.`stockid`=stockserialitems.stockid)
                 WHERE stockserialitems.loccode=".$_POST['loc1']."
             AND locstock.stockid IN (SELECT stockid FROM stockmaster WHERE categoryid IN (SELECT subsubcatid
FROM `bio_maincat_subcat`
WHERE `subcatid` LIKE '11')) 
             AND locstock.quantity>0
             AND stockserialitems.serialno not in (select serialno from bio_stocktransfer where serialno!='')
              GROUP BY  stockserialitems.serialno";  
              //echo "<table style='border:1px solid #F0F0F0;width:800px'>";
              $Result=DB_query($SQL,$db);
     
            echo"<table style='width:90%'><tr><td>";
 
            // $_SESSION['UserStockLocation'];
             echo "<fieldset style='float:center;width:70%;'>";     
             echo "<legend><h3>Select Plants To Transfer</h3>";
             echo "</legend>";
             echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
     
     

    
            echo"<tr>";
            echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Plant Serial No.</th><th>Select</th></tr>";
            $k=0;
            $i=1;
            while($Row=DB_fetch_array($Result))
            {
             if ($k==1)
             {
               echo '<tr class="EvenTableRows">';
                $k=0;
             }else 
              {
               echo '<tr class="OddTableRows">';
               $k=1;     
               }
             echo "<td>".$i."</td>";
             echo "<td>".$Row['description']."</td>";
             /*echo "<td>".$Row['quantity']."</td>";*/
             echo "<td>".$Row['serialno']."</td>";
             echo "<td><input type=checkbox name='sel".$i."' ></tr>";
             echo "<input type=hidden name='stock".$i."' value=".$Row['stockid'].">";
             echo "<input type=hidden name='serial".$i."' value=".$Row['serialno'].">";
     
     
            $i++;

            }
          echo "<input type=hidden name='totrow' value=".$i.">";
          /*echo "<tr align=center><input type=submit name=sel_item value='Select Items'></tr>";*/
          echo "</table>";
          echo "<center><br>
          <input type=submit name=sel_item value='Select Items To Transfer' ><br></center>";
     
 
    
    
          echo "</fieldset>";  
          echo "</table>";
          $SQL1="select sum(bio_stocktransfer.qty) as ee,stockmaster.description FROM  bio_stocktransfer,stockmaster
          where bio_stocktransfer.fromloc='".$_POST['loc1']."' AND bio_stocktransfer.toloc='".$_POST['loc2']."' 
          AND stockmaster.stockid=bio_stocktransfer.stockid
           AND bio_stocktransfer.recieved=0
           group by bio_stocktransfer.stockid"; //
           $Result1=DB_query($SQL1,$db);

           echo "<fieldset style='float:center;width:70%;'>";     
           echo "<legend><h3>Pending Transfer</h3>";
           echo "</legend>";
           echo"<table style='border:1px solid #F0F0F0;width:70%'; >"; 
           echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
           // echo "<fieldset style='float:center;width:97%;'>";     
     
     

    
        echo"<tr>";
        echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Quantity</th></tr>";
        $k=0;
        $i=1;
        while($Row1=DB_fetch_array($Result1))
           {
             if ($k==1)
             {
              echo '<tr class="EvenTableRows">';
              $k=0;
             }else 
             {
             echo '<tr class="OddTableRows">';
             $k=1;     
              }
           echo "<td>".$i."</td>";
           echo "<td>".$Row1['description']."</td>";
           echo "<td>".$Row1['ee']."</td>";
           $i++;
        }
  
 }
    if($_POST['sel_item1'])
    {   
        for($i=0;$i<$_POST['totrow'];$i++)
           {
            // echo $_POST['sel'.$i];
           if($_POST['sel'.$i])
             {
      $sql_insert="INSERT INTO  bio_stocktransfer(stockid,fromloc,toloc,qty,type) values ('".$_POST['stock'.$i]."',".$_POST['loc1'].",
             ".$_POST['loc2'].",".$_POST['sel'.$i].",5)";
             $res_insert=DB_query($sql_insert,$db);
             }
           }      

    }
 
 if($_POST['loc1']!=""  AND $_POST['loc2'] AND $_POST['type']=='B')
    { 
      
    if($_POST['main'] && $_POST['sub'])
       {
        $g=$_POST['main'];
        $h=$_POST['sub'];       
   /* $sql ="SELECT `stockmaster`.`stockid`, `stockmaster`.`description`,stockcategory.categoryid, 
`locstock`.`quantity` 
FROM locations 
INNER JOIN `stockcategory` ON (stockcategory.categoryid='".$h."')
inner join bio_maincat_subcat on( bio_maincat_subcat.maincatid=$g AND stockcategory.categoryid=bio_maincat_subcat.subcatid)
INNER JOIN stockmaster ON (stockmaster.categoryid= bio_maincat_subcat.subcatid and stockmaster.stockid=stockmaster.mbflag='B') 
INNER JOIN locstock ON (locstock.stockid = stockmaster.stockid)  

WHERE locstock.loccode=".$_POST['loc1']."
 AND `locstock`.`quantity` >0 
group by stockmaster.stockid  "; */
        $sql=" SELECT stockmaster.stockid,stockmaster.description,locstock.quantity,locstock.loccode,stockmaster.stockid,
        stockmaster.mbflag FROM stockmaster,locstock WHERE  locstock.loccode=".$_POST['loc1']."  AND  `locstock`.`quantity` >0 AND locstock.stockid = stockmaster.stockid              AND stockmaster.mbflag='B'AND stockmaster.categoryid='".$h."'   ";
        }
    else
        {
        $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description`, 
         `locstock`.`quantity`
         
         FROM 
        locations
         INNER JOIN `locstock` 
        ON (`locations`.`loccode` = `locstock`.`loccode`)
         INNER JOIN `stockmaster` 
        ON (`locstock`.`stockid` = `stockmaster`.`stockid`)
        WHERE `locstock`.loccode=".$_POST['loc1']."
        AND locstock.stockid IN (SELECT stockid FROM stockmaster WHERE mbflag='B') 
        AND locstock.quantity>0"; 
        }
  
     $Result=DB_query($sql,$db);
     
  //   echo"<table style='width:100%'><tr><td>";
 
      echo "<fieldset style='float:center;width:70%;'>";     
      echo "<legend><h3>Select Raw material To Transfer</h3>";
      echo "</legend>";
      echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
      echo"<tr>";
      echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Quantity<th>Transfer qty</th></tr>";
      $k=0;
      $i=1;
     while($Row=DB_fetch_array($Result))
        {  
         $sql_my="select sum(bio_stocktransfer.qty),stockmaster.description FROM  bio_stocktransfer,stockmaster
         where bio_stocktransfer.fromloc='".$_POST['loc1']."' AND bio_stocktransfer.toloc='".$_POST['loc2']."' 
          AND stockmaster.stockid=bio_stocktransfer.stockid
          AND bio_stocktransfer.recieved=0
          AND bio_stocktransfer.stockid='".$Row['stockid']."'";
          $result_my=DB_query($sql_my,$db);
          if($result_my)
            {
              while($Row_my=DB_fetch_array($result_my))
              {
               $a=$Row_my[0];
              }
            }
        $c=$Row['quantity']-$a;
         if($c>0)
           {
            if ($k==1)
              {
               echo '<tr class="EvenTableRows">';
                $k=0;
              }
           else 
             {
             echo '<tr class="OddTableRows">';
              $k=1;     
             }
           echo "<td>".$i."</td>";
           echo "<td>".$Row['description']."</td>";
           echo "<td>".$c."</td>";//$Row['quantity']
           echo "<td><input type=text name='sel".$i."' id='sel".$i."' ></tr>";
           // echo "<td><input type='txt'></tr>";//."$i.""
           echo "<input type=hidden name='stock".$i."' value=".$Row['stockid'].">";
           echo "<input type=hidden name='qty".$i."' id='qty".$i."' value=".$c.">";
           $i++;
         }
      }
    echo "<input type=hidden name='totrow' id='totrow' value=".$i.">";
   
     /*echo "<tr align=center><input type=submit name=sel_item value='Select Items'></tr>";*/
    echo "</table>";
    echo "<center><br>
    <input type=submit name=sel_item1 value='Select Items To Transfer' onclick='if(valid()==1)return false;'><br></center>";
     
 
    
    
    echo "</fieldset>";  
  //  echo "</table>";
    $sql1="select sum(bio_stocktransfer.qty),stockmaster.description FROM  bio_stocktransfer,stockmaster
    where bio_stocktransfer.fromloc='".$_POST['loc1']."' AND bio_stocktransfer.toloc='".$_POST['loc2']."' 
    AND stockmaster.stockid=bio_stocktransfer.stockid
    AND bio_stocktransfer.recieved=0
    AND bio_stocktransfer.type=5
    group by bio_stocktransfer.stockid"; //  AND bio_stocktransfer.serialno=NULL
    $Result1=DB_query($sql1,$db);

    echo "<fieldset style='float:center;width:70%;'>";     
    echo "<legend><h3>Pending Transfer</h3>";
    echo "</legend>";
    echo"<table style='border:1px solid #F0F0F0;width:70%'; >"; 
    echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
     
     

    
    echo"<tr>";
    echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Quantity</th></tr>";
    $k=0;
    $i=1;
    while($Row1=DB_fetch_array($Result1))
       {
        if ($k==1)
        {
         echo '<tr class="EvenTableRows">';
          $k=0;
         }
         else 
         {
          echo '<tr class="OddTableRows">';
          $k=1;     
         }
     echo "<td>".$i."</td>";
     echo "<td>".$Row1['description']."</td>";
     echo "<td>".$Row1[0]."</td>";
     $i++;
     }
  
  }
 }
 }
?>
<script>
function valid()
{
      var f=0;
    totrow=document.getElementById("totrow").value;
    for(i=1;i<totrow;i++)
    {
       
     var   qty=document.getElementById("qty"+i).value;
    var   sel=document.getElementById("sel"+i).value;
  var a=qty-sel;//alert(a);
        if(a<0)
        {   // alert(sel);   
            alert("Transfer quantity exeeded than available quantity");
            document.getElementById("sel"+i).focus();
            f=1;
     }
     if(sel<=0 && sel!="")
        {
            alert("Transfer quantity is too low");
              document.getElementById("sel"+i).focus();
            f=1;
        }
    }return f;
   // str2=document.getElementById("cmcapacity").value;
    
}
function show(str)
{str2=document.getElementById("combo2").value;
    if(str=="A")
    { 
       str2=document.getElementById("combo2").innerHTML=""; 
    }
    
     if (str=="")
  {
  document.getElementById("type").focus();
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("combo").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","stocktransfer_ajax.php?combo="+str,true);//alert(str);
xmlhttp.send();      
}




function show2(str1)
{
    

   if (str1=="")
  {
  document.getElementById("type").focus();
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("combo2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","stocktransfer_ajax.php?combo2="+str1,true);//alert(str4);
xmlhttp.send();     // alert(str1);
}
function show3(str3)
{
    
     if (str3=="")
  {
  document.getElementById("locatA").focus();
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("locat1").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","stocktransfer_ajax.php?str3="+str3,true);//alert(str4);
xmlhttp.send();     // alert(str3);
}// alert(str1);
    
function show4(str4)
{
    
     if (str4=="")
  {
  document.getElementById("locatB").focus();
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("locat2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","stocktransfer_ajax.php?str4="+str4,true);//alert(str4);
xmlhttp.send();     // alert(str3);
}// alert(str1);
</script>