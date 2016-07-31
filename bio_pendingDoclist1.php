<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Pending Document List');  
include('includes/header.inc');




  echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";  
  

  
echo"<fieldset style='width:90%;'>";
echo"<legend><h3>Search Document List</h3></legend>";



echo"<table style='border:1px solid #F0F0F0;width:100%'>";

//echo"<tr><td>Customer Type</td><td>Document Name</td><td>Document Type</td></tr>";



echo"<tr>";

    echo '<td>Customer Type<select name="enq" id="enq" style="width:150px" onchange=showdocs(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

echo '</select></td>';


    echo '<td id=showdocument>';
    
    echo'From Beneficiary <select name="docname_frm" id="docname_frm" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=1";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_frm'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select>';

    echo'&nbsp;&nbsp;To Beneficiary <select name="docname_to" id="docname_to" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=2";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_to'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select></td>';
echo'</td>';





echo '<td>Status<select name="docstatus" id="docstatus" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_docstatus";
$result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['id']==$_POST['docstatus'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['id'] . '">'.$row1['status'];
           echo '</option>';  
    }
echo '</select></td>';

  

echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";

        echo "<div ><br />";
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer') . '</th>  
                    <th>' . _ ('SaleOrder Date') . '</th>  
                    <th>' . _ ('Office') . '</th> 
                    <th>' . _('Team') . '</th>
              </tr>';
              
              
              
       $sql="SELECT bio_documentlist.id,
                    bio_cust.custname,
                    bio_documentlist.orderno,
                    salesorders.orddate,
                    bio_leadteams.teamname,
                    bio_office.office 
              FROM  bio_documentlist,bio_leads,bio_office,bio_cust,salesorders, bio_leadtask,bio_leadteams 
              WHERE bio_documentlist.leadid=bio_leads.leadid 
              AND   bio_leads.cust_id=bio_cust.cust_id 
              AND   bio_documentlist.orderno=salesorders.orderno 
              AND   bio_leadtask.leadid=bio_documentlist.leadid 
              AND   bio_leadtask.taskid=24 
              AND   bio_leadtask.viewstatus=1 
              AND   bio_leadteams.teamid=bio_leadtask.teamid 
              AND   bio_office.id=bio_leadteams.office_id
              AND   bio_leadtask.taskcompletedstatus=0";                                       
               
               if(isset($_POST['filterbut']))
               {  
                                     
                   if ( $_POST['enq']!=0)
                   {                                       
                       $sql .= " AND bio_leads.enqtypeid='".$_POST['enq']."'";                   
                   } 
                   
                   if ( $_POST['docname_frm']!=0)
                   {                                       
                       $sql .= " AND bio_documentlist.docno='".$_POST['docname_frm']."'";                   
                   }     
                   
                   if ( $_POST['docname_to']!=0)
                   {                                       
                       $sql .= " AND bio_documentlist.docno='".$_POST['docname_to']."'";                   
                   }     

                   if ($_POST['docstatus']!='' || $_POST['docstatus']!=0)
                   {        
                       $sql .= " AND bio_documentlist.status=".$_POST['docstatus']."";
                   }    
                   
                   
               }   
               else
               {
                   $sql .= " AND bio_documentlist.status=0";  
               }
               //echo $sql;

               $result=DB_query($sql,$db);
$slno=1;
 while($row=DB_fetch_array($result) )      {

            

 
 echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                     <td>".$row['orderno']."</td> 
                                     <td>".$row['custname']."</td>
                                     <td>".$row['orddate']."</td> 
                                     <td>".$row['office']."</td> 
                                     <td>".$row['teamname']."</td> 
                                     </tr>";
 $slno++;

 }                                    

 echo '</table></form></div></fieldset>';
 echo "</form>";   
 
?>

<script type="text/javascript"> 



function showdocs(str1){   

//alert(str1); 

if (str1=="")
  {
  document.getElementById("showdocument").innerHTML="";
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
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText; 
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 



</script>