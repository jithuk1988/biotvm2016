<?php

function itemname($Item,$db)     {
    
       $sql = "SELECT stockmaster.description
                FROM stockmaster
                WHERE stockmaster.stockid='$Item'";
        $result = DB_query($sql,$db);

        $myrow = DB_fetch_array($result);
        return $myrow[0];
}

if(isset($_SESSION['StockID1']))     {
$_POST['stockid']=$_SESSION['StockID1'];    
}
if((isset($_GET['p'])) AND (isset($_GET['q'])))      {
   
$BOM=$_GET['p'];
$FGItem=$_GET['q'];  

$sql="SELECT workcentreadded,     
             loccode,
             quantity
      FROM bom 
      WHERE parent='$FGItem'    AND     
            component='$BOM'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$_POST['Quantity']= $myrow[2];

$FGItem1=itemname($FGItem,$db);
$BOM1=itemname($BOM,$db);

echo "<input type=hidden name='Editbom' value='1'>"; 
echo "<tr><td>" . _('Finished Good') . ": </td><td><input type=hidden id='fgitem' name='Fgitem' value='$FGItem' >$FGItem1</td></tr>";
echo "<tr><td>" . _('BOM') . ": </td><td><input type=hidden name='Component' value='$BOM'>$BOM1</td></tr>"; 
                                                                       
}else{            
          
    
    echo "<tr><td>" . _('Finished Good') . ": </td><td><select id='main' style='width:180px;' onchange='show(this.value)'>";
    $sql="SELECT
  maincatid,
  maincatname
FROM bio_maincategorymaster where rowstatus=1";

$rst=DB_query($sql,$db);
echo '<option value=0></option>';
while($myrowc=DB_fetch_array($rst))
{
    

 echo '<option value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
 }
 
  echo '</select></td>';
  echo '<td><div id="newsub"><select style="width:180px" name="newsub"></select></div></td>';
    echo '<td><div id="showsub"><select name="sub" id="sub" style="width:180px" onchange=display(this.value) ></div>';
/*$sql="SELECT
 maincatid,
  subcatid,
  stockcategory.categorydescription
FROM bio_maincat_subcat,stockcategory
where bio_maincat_subcat.maincatid=".$myrowc[maincatid]." AND stockcategory.categoryid=bio_maincat_subcat.subcatid ";
$rst=DB_query($sql,$db);
echo '<option value=0></option>';//
while($myrowc=DB_fetch_array($rst))
{
      
    echo '<option value="';
         
    echo $myrowc[subcatid].'">'.$myrowc[categorydescription].'</option>';
 }*/
echo "</select></td><td><div id='display'><select tabindex='2' id='fgitem' name='Fgitem' style='width:180px;' onblur='datagridload(this.value)'></div>";


//    DB_free_result($result);
            /*    $sql = "SELECT stockmaster.stockid,stockmaster.description FROM stockmaster 
                ORDER BY stockmaster.stockid";
        $result = DB_query($sql,$db);
        echo "<option VALUE=0".'>';
        while ($myrow = DB_fetch_array($result)) {
            if (isset($_POST['stockid']) and $myrow['stockid']==$_POST['stockid']) {
                echo "<option selected VALUE='";
            } else {
                echo "<option VALUE='";
            }
            echo $myrow['stockid'] . "'>" . $myrow['description'];

        }*/ //end while loop

        echo'</select>';        
        echo'</td></tr>'; 
        
   echo "<input type=hidden name='SelectedParent' VALUE='$SelectedParent'>";        
            /* echo "Enter the details of a new component in the fields below. <br>Click on 'Enter Information' to add the new component, once all fields are completed.";
            */
            echo '<tr><td>' . _('Component code') . ':</td><td>';
            echo "<select name='MBFlag' id='com1' style='width:180px;' onchange='view(this.value)'>";
 echo "<option Selected value=0".'>';
 echo '<option value="M">' . _('Manufactured') . '</option>';
   
    echo '<option value="A">' . _('Assembly') . '</option>';


    echo '<option value="K">' . _('Kit') . '</option>';

    echo '<option value="G">' . _('Phantom') . '</option>';

    echo '<option value="B">' . _('Purchased') . '</option>';

    echo '<option value="D">' . _('Service/Labour') . '</option>';
            echo "</select></td>";
            echo "<td><div id='comp1'><select name='com2' id='com2' style='width:180px;' onchange='view2(this.value)'>";
            echo "</select></div></td>";
             echo "<td><div id='compmid'><select name='commid' id='commid' style='width:180px;' onchange='view3(this.value)'>";
            echo "</select></div></td>";
            echo "<td><div id='comp2'><select name='com3' id='com3' name='Component' style='width:180px;'>";

/*
            if ($ParentMBflag=='A'){ /*Its an assembly 
                $sql = "SELECT stockmaster.stockid,
                        stockmaster.description
                    FROM stockmaster INNER JOIN stockcategory
                        ON stockmaster.categoryid = stockcategory.categoryid
                    WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
                    OR stockmaster.mbflag !='D')
                    AND stockmaster.mbflag !='K'
                    AND stockmaster.mbflag !='A'
                    
                    AND stockmaster.controlled = 0
                    AND stockmaster.stockid != '$SelectedParent'
                    ORDER BY stockmaster.description asc";

            } else { /*Its either a normal manufac item, phantom, kitset - controlled items ok 
                $sql = "SELECT stockmaster.stockid,
                        stockmaster.description
                    FROM stockmaster INNER JOIN stockcategory
                        ON stockmaster.categoryid = stockcategory.categoryid
                    WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
                    OR stockmaster.mbflag !='D')
                    AND stockmaster.mbflag !='K'
                    AND stockmaster.mbflag !='A'
                    
                    AND stockmaster.stockid != '$SelectedParent'
                    ORDER BY stockmaster.description asc";
            }

            $ErrMsg = _('Could not retrieve the list of potential components because');
            $DbgMsg = _('The SQL used to retrieve the list of potential components part was');
            $result = DB_query($sql,$db,$ErrMsg, $DbgMsg);
*/
         /*   echo "<option VALUE=0".'>';
            while ($myrow = DB_fetch_array($result)) { 
                echo "<option VALUE=".$myrow['stockid'].'>' . $myrow['description'];
            } //end while loop
*/
            echo '</select></div></td></tr>';
     

}
            

   

        echo "<tr><td>" . _('Production Center') . ": </td><td><select tabindex='2' name='LocCode' style='width:180px;'>";

        DB_free_result($result);
        $sql = 'SELECT locationname, loccode FROM locations';
        $result = DB_query($sql,$db);
                      $_POST['LocCode']=7;
        while ($myrow = DB_fetch_array($result)) {
            if (isset($_POST['LocCode']) and $myrow['loccode']==$_POST['LocCode']) {
                echo "<option selected VALUE='";
            } else {
                echo "<option VALUE='";
            }
            echo $myrow['loccode'] . "'>" . $myrow['locationname'];

        } //end while loop

        DB_free_result($result);

        echo "</select></td></tr><tr><td>" . _('WareHouse') . ": </td><td>";
        echo "<select tabindex='3' name='WorkCentreAdded' style='width:180px;'>";

        $sql = 'SELECT code, description FROM workcentres';
        $result = DB_query($sql,$db);

        if (DB_num_rows($result)==0){
            prnMsg( _('There are no work centres set up yet') . '. ' . _('Please use the link below to set up work centres'),'warn');
            echo "<br><a href='$rootpath/WorkCentres.php?" . SID . "'>" . _('Work Centre Maintenance') . '</a>';
            include('includes/footer.inc');
            exit;
        }

        while ($myrow = DB_fetch_array($result)) {
            if (isset($_POST['WorkCentreAdded']) and $myrow['code']==$_POST['WorkCentreAdded']) {
                echo "<option selected VALUE='";
            } else {
                echo "<option VALUE='";
            }
            echo $myrow['code'] . "'>" . $myrow['description'];
        } //end while loop

        DB_free_result($result);

        echo "</select></td></tr><tr><td>" . _('Quantity') . ": </td><td>
            <input " . (in_array('Quantity',$Errors) ?  'class="inputerror"' : '' ) ."
             tabindex='4' type='Text' class=number id='BOMquantity' name='Quantity' class=number size=25 maxlength=8 value='".$_POST['Quantity']."'";
//        if (isset($_POST['Quantity'])){
//            echo $_POST['Quantity'];
//        } else {
//            echo 1;
//        }

        echo "></td></tr>";



        if ($ParentMBflag=='M' OR $ParentMBflag=='G'){
            echo '<tr><td>' . _('Auto Issue this Component to Work Orders') . ':</td>
                <td>
                <select tabindex="7" name="AutoIssue" style="width:180px;">';

            if (!isset($_POST['AutoIssue'])){
                $_POST['AutoIssue'] = $_SESSION['AutoIssue'];
            }
            if ($_POST['AutoIssue']==0) {
                echo '<option selected VALUE=0>' . _('No');
                echo '<option VALUE=1>' . _('Yes');
            } else {
                echo '<option selected VALUE=1>' . _('Yes');
                echo '<option VALUE=0>' . _('No');
            }


            echo '</select></td></tr>';
        } else {
            echo '<input type=hidden name="AutoIssue" VALUE=0>';
        }


 
?>
<script>
function show(str1)
{
    
 
  // var  main=document.getElementById('main').value;            

    if (str1=="")
  {
  document.getElementById("main").focus();
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
     document.getElementById("newsub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","BOM_ajax.php?sub="+str1,true);//alert(str1);
xmlhttp.send();    
    
  
}
function display(str2)
{                           
var  main=document.getElementById('main').value;
var  sub=document.getElementById('newsub').value;
//alert(main)  ;
    if (str2=="")
  {
      
  document.getElementById("newsub").focus();
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showsub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 

xmlhttp.open("GET","BOM_ajax.php?sub2="+str2,true);
xmlhttp.send();    
    
  
}
function display1(str3)
{
  if (str3=="")
  {
      
  document.getElementById("showsub").focus();
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("display").innerHTML=xmlhttp.responseText; 
     
    }            
  } 

xmlhttp.open("GET","BOM_ajax.php?item="+str3,true);
xmlhttp.send();    
        
}
function view(str3)
{

  if (str3=="")
  {
  document.getElementById("com1").focus();
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
     document.getElementById("comp1").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","BOM2_ajax.php?first="+str3,true);//alert(str1);
xmlhttp.send();        
}
function view2(str4)
{
    var  str5=document.getElementById('com1').value;
  if (str4=="")
  {
  document.getElementById("com3").focus();
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
     document.getElementById("compmid").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","BOM2_ajax.php?second="+str4,true);//alert(str4);
xmlhttp.send();        
}
function view3(str5)
{
  if (str5=="")
  {
  document.getElementById("commid").focus();
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
     document.getElementById("comp2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","BOM2_ajax.php?third="+str5,true);//alert(str4);
xmlhttp.send();        
}
  </script>