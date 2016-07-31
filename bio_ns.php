<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Customer View');  
include('includes/header.inc');
?>

<style type="text/css">
#hd
{
background: rgb(219,219,219); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(219,219,219,1) 6%, rgba(226,226,226,1) 9%, rgba(219,219,219,1) 42%, rgba(209,209,209,1) 80%, rgba(254,254,254,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(6%,rgba(219,219,219,1)), color-stop(9%,rgba(226,226,226,1)), color-stop(42%,rgba(219,219,219,1)), color-stop(80%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#fefefe',GradientType=0 ); /* IE6-9 */

}
 
.sel{
font-size: large;
font-weight: 600;
border-color: red;
height:30px;
border: red;
border-width: thin;
color:blue;

}
.sl1{

height:30px;
background: rgb(252,255,244); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(252,255,244,1) 0%, rgba(223,229,215,1) 40%, rgba(179,190,173,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,255,244,1)), color-stop(40%,rgba(223,229,215,1)), color-stop(100%,rgba(179,190,173,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#b3bead',GradientType=0 ); /* IE6-9 */
}
.sl2{
height:30px;
background: rgb(247,251,252); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(247,251,252,1) 0%, rgba(217,237,242,1) 40%, rgba(173,217,228,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(247,251,252,1)), color-stop(40%,rgba(217,237,242,1)), color-stop(100%,rgba(173,217,228,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7fbfc', endColorstr='#add9e4',GradientType=0 ); /* IE6-9 */

}

.tbl
{
    font-family: verdana,arial,sans-serif;
    font-size:11px;
    color:#333333;
    border-width: 1px;
    border-color: #999999;
    border-collapse: collapse;
}
.thl
{
    padding: 0px;
    background: #d5e3e4;
	font-weight:500;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2Q1ZTNlNCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2NjZGVlMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiM2M4Y2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #d5e3e4 0%, #ccdee0 40%, #b3c8cc 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d5e3e4), color-stop(40%,#ccdee0), color-stop(100%,#b3c8cc));
    background: -webkit-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: -o-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: -ms-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: linear-gradient(to bottom,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    border: 1px solid #999999;
}
.tdl
{
    padding: 0px;
    background: #ebecda;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #ebecda 0%, #e0e0c6 40%, #ceceb7 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ebecda), color-stop(40%,#e0e0c6), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: -o-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: -ms-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: linear-gradient(to bottom,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    border: 1px solid #999999;
}
 .tdl1
{
    padding: 0px;
    background: #3ADF00;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #3ADF00 0%, #e0e0c6 40%, #9AFE2E 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#3ADF00), color-stop(40%,#58FAD0), color-stop(100%,#9AFE2E));
    background: -webkit-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: -o-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: -ms-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: linear-gradient(to bottom,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    border: 1px solid #999999;
}
.tdl3
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #D358F7 0%, #e0e0c6 40%, #F6CEF5 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#F6D8CE), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #999999;
}
.tdl2
{
    padding: 0px;
    background: #FAAC58;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #FAAC58 0%, #e0e0c6 40%, #F6D8CE 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#F6D8CE), color-stop(40%,#F6D8CE), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: -o-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: -ms-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: linear-gradient(to bottom,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    border: 1px solid #999999;
}
</style>
<?php
$off=$_SESSION['officeid'];
if ($off==1)
{
    
}         
$ord=$_GET['ordno'];
    
if($ord!=null)
{
    $sql_sel="SELECT *  FROM bio_installation_status WHERE orderno=".$ord;                                                   $result_all=DB_query($sql_sel,$db);
    $main=DB_fetch_array($result_all); 
 $sql="SELECT debtorno FROM salesorders WHERE orderno=".$ord;
     $resulte=DB_query($sql,$db);
      $delivrdate=DB_fetch_array($resulte);
$drno=$delivrdate['debtorno'];

 $main['due_date1'];
}

/*if(isset($_POST['submit'])){
    
    if($_POST['status']==1)
    {
    
    
    $sql_sel="SELECT * FROM bio_installation_status WHERE orderno=".$_POST['orderno'];                                      $result_all=DB_query($sql_sel,$db);
    $main_tb=DB_num_rows($result_all); 
    $main_tbl=DB_fetch_array($result_all);       
     $installeddate=$_POST['installed_date'];
   $duedate=$_POST['duedate1'];
     
  if($main_tb>0){ 
                $actualdate=$_POST['actualdate1'];
            $actualdate2=$_POST['actualdate2'];
             $actualdate3=$_POST['actualdate3'];
  
//echo $_POST['fbcall'];
       if($_POST['actualdate1']=='0000-00-00')
       {
         $actualdate=FormatDateForSQL($_POST['caldate']);          
       }
       else 
       if($main_tbl['actual_date1']==$_POST['actualdate1'])
       {
           $actualdate=$_POST['actualdate1'];                  
       }
        
        
          if($_POST['actualdate2']=='0000-00-00' AND $_POST['actualdate1']!='0000-00-00' )
       {
         $actualdate2=FormatDateForSQL($_POST['caldate']);        
       }
       else if($main_tbl['actual_date2']==$_POST['actualdate2'])
      {
        $actualdate2=$_POST['actualdate2'];   
      }
      
      if($_POST['actualdate3']=='0000-00-00' AND $_POST['actualdate2']!='0000-00-00' AND $_POST['actualdate1']!='0000-00-00')
       {
           
         $actualdate3=FormatDateForSQL($_POST['caldate']);
       
         
       }
       else
      if($main_tbl['actual_date3']==$_POST['actualdate3'])
      {
        $actualdate3=$_POST['actualdate3']; 
            
       
   
      }
            
      if($actualdate!='0000-00-00'){
        //echo $actualdate=FormatDateForSQL($actualdate);
           $date = strtotime(date("Y-m-d", strtotime($actualdate)) . " +7 day");

         $_POST['duedate2']=date('Y-m-d',$date);
      }
      if($actualdate2!='0000-00-00'){
           $date = strtotime(date("Y-m-d", strtotime($actualdate2)) . " +11 day");
           $_POST['duedate3']=date('Y-m-d',$date);
      }
      
       
 echo $sql4="UPDATE  bio_installation_status 
                                          SET actual_date1='".$actualdate."',
                                          actual_date2='".$actualdate2."',
                                          due_date2='".$_POST['duedate2']."',
                                          actual_date3='".$actualdate3."',
                                          due_date3='".$_POST['duedate3']."',
                                          plant_status='".$_POST['plantstatus']."',
                                          close_status='".$x."',
                                          remarks='".$_POST['remarks1']."' 
                                          WHERE orderno='".$_POST['orderno']."'";                     
//$result4=DB_query($sql4,$db); 
                                          prnMsg(_('Updated') ,'success'); 


  }
//  }else{
//      
//       $actualdate=FormatDateForSQL($_POST['actualdate1']); 
//       $due=$duedate;
//       $date = strtotime(date("Y-m-d", strtotime($due)) . " +7 day");
//       $date1=date('Y-m-d',$date);      
//       $sql="INSERT INTO bio_installation_status (orderno,installed_date,actual_date1,due_date1,due_date2,plant_status,remarks) 
//                            VALUES (".$_POST['orderno'].",
//                            '".$installeddate."',
//                            '".$actualdate."',
//                            '".$duedate."',
//                            '".$date1."',
//                            ".$_POST['plantstatus'].",
//                            '".$_POST['remarks']."')";
//                            $result=DB_query($sql,$db);   
//                            prnMsg(_('inserted') ,'success');
//                                   
//  } 
  }
  
}          

*/

echo"<div id=fullbody>";
echo "<div id=cll ></div>";
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" style="background:#EBEBEB;">';
echo '<table class="selection" style="width:70%;">';
echo '<th colspan="2"><b><font size="+1" color="#000000">Customer List</font></b></th>' ;
echo '<tr><td align="left" style="width:800px">';
echo"<center><div id=insatal style='width:800px'>";
echo"<fieldset style='width:800px;height:330px '>"; 
echo"<legend>Customer Details</legend>";
echo"<table width=100%>";

/*echo '<tr id="ordno"><td style="width:14%" >OrderNo</td>
<td style="width:14%" ><input type="text" readonly name="orderno" id="orderno" value="'.$ord.'"></td></tr>';
*/
if($ord!=NULL){
           $sql="SELECT deliverydate FROM salesorders 
           WHERE orderno=".$ord;
           $result=DB_query($sql,$db);
           $delivrdate=DB_fetch_array($result);
           $sql1="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$ord." AND status=1";                                  
           $result1=DB_query($sql1,$db);
           $calltime=DB_fetch_array($result1);                                                       
}

/*
echo'<tr><td style="width:25%">delivery date*</td>
<td style="width:14%"><input type="text" readonly value="'.$delivrdate['deliverydate'].'"></tr>';
echo'<tr><td style="width:25%">Installation date*</td>
<td style="width:14%">';
if($main['installed_date']!=NULL){
   echo '<input type="text" readonly name="installed_date" id="installed_date" value="'.$main['installed_date'].'">'; 
}else{
echo '<input type="text" name="installed_date" id="installed_date"  onblur="duedate();return false" class=date alt="'.$_SESSION['DefaultDateFormat']. '">';
}
echo '</tr>';
echo '<tr><td style="width:4%">Due Date1*</td>
<td  id="duedate">';
if($main['due_date1']!=NULL){
echo '<input type="text" readonly name="duedate1" id="duedate1" value="'.$main['due_date1'].'">';
}else{
 echo '<input type="text" name="duedate1" id="duedate1" value="">';   
     }
echo '</td></tr>';



//$sql="SELECT count(*),call_date FROM bio_calllog WHERE orderno=$ord AND callno=1";
//$result=DB_query($sql,$db);
//$myrow=DB_fetch_array($result);echo$myrow[0];
//if($myrow[0]!=0)
//{
echo "<div>"    ;
echo '<tr><td style="width:4%" >Actual date1*</td>
<td id=actualdate style="width:4%">';
echo '<input readonly type="text" name="actualdate1" id="actualdate1" value="'.$main['actual_date1'].'">'; 
echo '<td></tr>'; 
//if($main['actual_date1']!=NULL){
//   
//}else{
//echo '<input hidden type="text" name="actualdate1" id="actualdate1" >';
//     }

if($main['actual_date1']!=0){ 
        $act1=$main['actual_date1'];
        $date = strtotime(date("Y-m-d", strtotime($act1)) . " +7 day");
        $date1=date('Y-m-d',$date);
echo '<tr ><td style="width:4%">Due Date2*</td>
<td style="width:4%"><input readonly type="text" name="duedate2" id="duedate2" value="'.$date1.'"></td></tr>';
}else{
echo '<tr ><td style="width:4%">Due Date2*</td>
<td style="width:4%"><input readonly type="text" name="duedate2" id="duedate2" value="'.$main['due_date2'].'"></td></tr>';    
}


if($main['installed_date']!='0000-00-00'){
echo '<tr><td style="width:4%">Actual date2*</td>';
echo '<td style="width:4%" id=actualdatetd2><input readonly type="text" name="actualdate2" id="actualdate2" value="'.$main['actual_date2'].'"><td></tr>';
}       
//}     
echo "</div>";


if($main['actual_date2']!=NULL && $main['actual_date2']!='0000-00-00'){
        $due=$main['actual_date2'];
        $date = strtotime(date("Y-m-d", strtotime($due)) . " +11 day");
        $date1=date('Y-m-d',$date);
echo '<tr><td style="width:4%">Due Date3*</td>
<td style="width:4%"><input readonly type="text" name="duedate3" id="duedate3" value="'.$date1.'"></td></tr>';
}
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<tr><td style="width:4%">Actual date3*</td>
<td style="width:4%" id=actualdatetd3><input readonly type="text" name="actualdate3" id="actualdate3" value="'.$main['actual_date3'].'"></td></tr>';
}



echo '<tr><td style="width:4%">Plant Status</td>
<td style="width:4%"><select name="plantstatus" id="plantstatus" style="width:75%">
<option value=0></option>';
if($main['plant_status']==1){
echo '<option value=1 selected="selected">Working</option>';
}else{echo '<option value=1 selected="selected">Working</option>';}
if($main['plant_status']==2){
echo '<option value=2 selected="selected">Problem</option>';
}else{echo '<option value=2 >Problem</option>';}
echo '</select></td></tr>';
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<tr><td style="width:4%">Close Status*</td>
<td style="width:4%"><select name="closestatus" id="closestatus" style="width:75%">
<option value=0 selected="selected">No</option>
<option value=1>Yes</option>
</select></td></tr>';
}



echo '<tr><td style="width:4%">Remarks*</td>
<td style="width:4%"><textarea name="remarks" id="remarks"></textarea></td></tr>';

echo '<td colspan="2" align="right"><input type="submit" name="submit" id="submit" value="submit" onclick="if(check_remark()==1)return false;"></td>';
//echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(0)>' . _('Back') . '</a></td></tr>';
echo '</fieldset>';
*/
//echo '<input type="hidden" readonly name="installed_date" id="installed_date" value="'.$main['installed_date'].'">'; 


if($main['installed_date']!=NULL){
   echo '<input type="hidden" readonly name="installed_date" id="installed_date" value="'.$main['installed_date'].'">'; 
}else{
echo '<input type="hidden" name="installed_date" id="installed_date"  onblur="duedate();return false" class=date alt="'.$_SESSION['DefaultDateFormat']. '">';
}

if($main['due_date1']!=NULL){
echo '<input type="hidden" readonly name="duedate1" id="duedate1" value="'.$main['due_date1'].'">';
}else{
 echo '<input type="hidden" name="duedate1" id="duedate1" value="">';   
     }
   //echo $main['actual_date1'];
   if($main['actual_date1']!='0000-00-00' || $main['actual_date1']!=null)
   {
 echo '<input readonly type="hidden" name="actualdate1" id="actualdate1" value="'.$main['actual_date1'].'">'; 
    } 
        
   /* else
    {
        
        echo '<input readonly type="text" name="actualdate1" id="actualdate1" value="'.$_POST['caldate'].'">'; 
    }*/
     if($main['actual_date1']==NULL || $main['actual_date1']=='0000-00-00' )
     { 
       echo '<input readonly type="hidden" name="duedate2" id="duedate2" value="'.$main['due_date2'].'">'; 
}else{
    
  $act1=$main['actual_date1'];
      // echo $act1;
                $date = strtotime(date("Y-m-d", strtotime($act1)) . " +7 day");
        $date1=date('Y-m-d',$date);echo '<input readonly type="hidden" name="duedate2" id="duedate2" value="'.$date1.'">';  
}

//echo $main['actual_date2'];
if($main['installed_date']!='0000-00-00'){

echo '<input readonly type="hidden" name="actualdate2" id="actualdate2" value="'.$main['actual_date2'].'">';
}  
if($main['actual_date2']!=NULL && $main['actual_date2']!='0000-00-00'){
        $due=$main['actual_date2'];
        $date = strtotime(date("Y-m-d", strtotime($due)) . " +11 day");
        $date1=date('Y-m-d',$date);
echo '<input readonly type="hidden" name="duedate3" id="duedate3" value="'.$date1.'">';
}
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<input readonly type="hidden" name="actualdate3" id="actualdate3" value="'.$main['actual_date3'].'">';
}

   if($ord!=NULL){
$sql_ph="SELECT brname,concat(braddress1,'</BR>',braddress2) AS 'braddress2',bio_district.district,concat(faxno,'</br>',phoneno) as 'phoneno' 
                  FROM  custbranch,bio_district 
                  WHERE (custbranch.cid=bio_district.cid AND custbranch.stateid=bio_district.stateid AND custbranch.did=bio_district.did)
                  AND   debtorno=(SELECT debtorno FROM salesorders WHERE orderno='".$_GET["ordno"]."')";
                  $result_ph=DB_query($sql_ph,$db); 
                  $row_ph=DB_fetch_array($result_ph);
                           $add=$row_ph['braddress2'];
                           $nadd=str_replace(',',' ',$add);
                   $contactno=$row_ph['phoneno'];  
              $sqlpnt="select stockmaster.description,orderplant.branchcode,date_format(bio_installation_status.installed_date,'%d %b %Y') as 'ins_date' from bio_installation_status,stockmaster,orderplant where stockmaster.stockid=orderplant.stkcode and bio_installation_status.orderno=orderplant.orderno and orderplant.orderno='".$_GET["ordno"]."'";    
                   $result_pt=DB_query($sqlpnt,$db); 
                         $row_pt=DB_fetch_array($result_pt);
						 $sqlcallno="SELECT date_format(salesorders.orddate,'%d %b %Y') as 'ordate' from salesorders where orderno='".$_GET["ordno"]."'";  
						          $payef=DB_query($sqlcallno,$db); 
                          $row_cl=DB_fetch_array($payef);
                          
                          $pay="SELECT abs( sum( `ovamount` ) )   as 'paid'
FROM `debtortrans`
WHERE `debtorno` =(SELECT debtorno FROM salesorders WHERE orderno='".$_GET["ordno"]."')
AND ovamount <0";
      $paye=DB_query($pay,$db); 
                          $paycl=DB_fetch_array($paye);
                          
                          
                          $file="select fileno from bio_fileno where debtorno=(SELECT debtorno FROM salesorders WHERE orderno='".$_GET["ordno"]."')";
                            $filenos=DB_query($file,$db); 
                          $filess=DB_fetch_array($filenos);  
               
               
                  echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Name:</td>
<td style="width:14%">'.$row_ph['brname'].'</td></tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Address:</td>
<td style="width:14%">'.$nadd.'</td></tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">District:</td>
<td style="width:14%">'.$row_ph['district'].'</tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Contact No:</td>
<td style="width:14%">'.$contactno.'</td></tr>';
echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Biogas Plant:</td>
<td style="width:14%">'.$row_pt[0].'</td></tr>';  
echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Amount Paid:</td>
<td style="width:14%">'.$paycl[0].'</td></tr>';
echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Customer Code:</td>
<td style="width:14%">'.$row_pt[1].'</td></tr>';     
   echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Order Date:</td>
<td style="width:14%">'.$row_cl[0].'</td></tr>';
       echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Installed Date:</td>
<td style="width:14%">'.$row_pt[2].'</td></tr>';    
     echo'<tr style="height:25px; font-weight:600"><td style="width:15%">File No:</td>
<td style="width:14%">'.$filess[0].'</td></tr>';    
 echo "</tr><tr></tr><tr></tr>";
 echo '<input hidden type="text" name="deno" id="deno" value="'.$drno.'" >'; 
  echo '<input hidden type="text" name="ord" id="ord" value="'.$_GET["ordno"].'" >'; 
//echo '<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>';
//echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(1)>' . _('Instatallation status view') . '</a></td></tr>';
//echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(0)>' . _('Calls Due Today') . '</a></td></tr>';
  echo '<tr ><td ><a style=cursor:pointer; onclick=Customer_Maintenance(1)>' . _('Modify Customer Details') . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style=cursor:pointer; onclick=reference()>' . _('Add Referene Lead') . '</a></td><td ><a style=cursor:pointer; onclick=payment(1)>' . _('Payment Details') . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style=cursor:pointer; onclick=ord(1)>' . _('Order Details') . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style=cursor:pointer; onclick=editorder(1)>' . _('Edit Order Details') . '</a></td></tr>'; 
   
   
   
   }  echo '</fieldset>'; 
  echo '</table>';             
 echo '</div></center></td></tr><tr><td colspan=4 align="center"><a href="bio_ns.php" style="font-weight:bold;text-decoration:none">View Customer List</a></td></tr>';                 
  // echo '<td style="width:370px">';
   /*
if($_GET[ordno]!=null){
    
 
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$row_ph['braddress2'].'</font><br />';
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$row_ph['district'].'</font><br />';   
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$contactno.'</font><br />';  
}

echo '<div id="call_log"></div>'; */
//echo"<div id=calllog style='width:370px;float:left'>";
/*echo"<fieldset style='width:370px;height:280px'>"; 

echo"<br />";
echo"<table width=100% id='cal_lgtb'>";

/*echo '<tr><td style="width:4%">OrderNo*</td>
<td style="width:14%"><input type="text" name="ordernum" id="ordernum" value="'.$ord.'" ></td></tr>';
*/   /*
echo '<tr id="ordno"><td style="width:14%" >OrderNo</td>
<td style="width:14%" ><input  type="text" style="width:170px" readonly name="orderno" id="ordernum" value="'.$ord.'"></td></tr>';
   $caltime=$calltime['orderno'];                                                   
   
echo '<tr><td style="width:4%">Call NO*</td>
<td style="width:10%"><select name="fbcall" id="fbcall"  style="width:174px">
';

$a1=$main['actual_date1'];
$a2=$main['actual_date2'];
$a3=$main['actual_date3'];
if($row_cl[0]==0)
{

echo '<option value=1 selected="selected">First Call</option>';
}
else if($row_cl[0]==1)
{echo '<option value=2 selected="selected">Second Call</option>';
}
else if($row_cl[0]>1)
{
    echo '<option value=3 selected="selected">Third Call</option>';
}

echo '</select></td></tr>';

//onchange="changestatus(this.value)
echo '<tr><td style="width:30%">Plant Status*</td>
<td style="width:4%"><select name="plantstatus" id="plantstatus"  style="width:174px" onchange="changestatus(this.value)">
<option value=0></option>';
if($main['plant_status']==1){
echo '<option value=1 selected="selected">Working</option>';
}else{echo '<option value=1 selected="selected">Working</option>';}
if($main['plant_status']==2){
echo '<option value=2 selected="selected">Not working</option>';
}else{echo '<option value=2>Not working</option>';}

echo '</select></td><td id=cl>&nbsp;</td></tr>';  */

/*if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<tr><td style="width:4%">Close Status*</td>
<td style="width:4%"><select name="closestatus" id="closestatus" style="width:75%">
<option value=0 selected="selected">No</option>';

echo '
<option value=1>Yes</option>
</select></td></tr>';
}*/
    /*
echo '<tr><td style="width:4%">Call Date*</td>
<td style="width:4%"><input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'><td><td>&nbsp;</td></tr>';
echo '<tr><td style="width:4%">Remarks*</td>
<td style="width:4%"><textarea  style="width:169px"  onKeyPress="return charLimit(this)" onKeyUp="return characterCount(this)" name="remarks1" id="remarks1" tabindex=0></textarea></td><td id="charCount" ><b>100</b></td></tr>';
//onchange="changestatus(this.value)
echo '<tr><td style="width:4%">Status*</td>
<td style="width:4%" ><select name="status" id="status"  style="width:174px" >
<option value=0></option>
<option value=1>Success</option>       
<option value=2>Customer Busy</option>
<option value=3>Customer Not picking</option>
<option value=4>Line Busy</option>
<option value=5>Switch off</option>
</select></td><td>&nbsp;</td></tr>';//

echo '<td colspan="2" align="center"><br><br><input type="button" name="submit" id="submit" value="submit" onclick="closecall();"></td>';
echo '</table>';
echo '</tr>'; 
echo '</tr>';

echo '</table>';

echo '</div>';    */
//for show installation view
 /*
if($_GET['ordno']!=null){
echo "<div id='pcall'>";
echo "<fieldset style='width:90%;'>";     
     echo "<legend><h3>PREVIOUS CALL STATUS</h3>";
     echo "</legend>";         
     echo "<table style='border:1px solid ;width:90%;'>";
     echo '<tr>
    <th class="thl">SL No</th>   
                <th class="thl" >Call No</th>
                <th class="thl" >Call Date</th>
				<th class="thl">Remarks</th>
                <th class="thl" >Status</th>
                </tr>';
     if($_GET['ordno']!=NULL) {          
  $result="SELECT * FROM bio_calllog WHERE orderno=".$_GET['ordno'];
     }else{ $result="SELECT * FROM bio_calllog";}
                                                                                                                                
   $result_callog=DB_query($result,$db); 
   $sl=0;
 while($row=DB_fetch_array($result_callog)){
 $sl++;
     if($row['status']==1){$status="Success";}
     if($row['status']==2){$status="Customer busy";}
     if($row['status']==3){$status="Customer Not picking";}
     if($row['status']==4){$status="Line Busy";}
     if($row['status']==5){$status="Switch off";}
                printf("<tr style='background:#A8A4DB'>                
            <td class='tdl' width='70px'>%s<a></td>
            <td  class='tdl' width='100px'>%s</td>
            <td  class='tdl' width='100px'>%s</td>
            <td  class='tdl' width='150px'>%s</td>
            <td  class='tdl' width='130px'>%s</td>
                                   </tr>",
        $sl,
        $row['callno'],        
        $row['call_date'],
         $row['remark'],
        $status       
        ); 

           }
   echo "</table>";            




echo "</div>"; }      */


              

// }
//for show installation view end
//for Calls Due Today

if($_GET['ordno']==null){
         // $heading="Customer List";
 echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
                   echo"<table style='width:90%;'>
                   <tr><td>&nbsp</td></tr>
                   <tr><td>Name:</td><td><input type='text' name='custname'></td>";
    echo"<td>Phone No:</td><td><input type='text' name='pho'></td>"; 
    echo"<td>District:</td><td><input type='text' name='dist'></td>";  
    echo"<td><input type='submit' name='submit' id='submit' value=search></td></tr></table>";

                                       
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$_GET['status'].' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr id='hd' class='thl'><th id='hd' class='thl'><b>SL No</b></th><th id='hd' class='thl'><b>CODE</b></th><th id='hd' class='thl'><b>ORDER No</b></th>
   <th id='hd' class='thl'><b>ORDER DATE</b></th>  <th id='hd' class='thl'><b>Name</b></th>
<th id='hd' class='thl'><b>DISTRICT</b></th> 
<th id='hd' class='thl'><b>CONTACT NO</b></th>    
</tr>";

//$sql_selall="SELECT *  FROM bio_installation_status" ;  

$sql_selall="SELECT salesorders.orderno,salesorders.debtorno,debtorsmaster.name,CONCAT(custbranch.phoneno,'<br>',custbranch.faxno) as cn, bio_district.district,salesorders.orddate FROM salesorders
INNER JOIN debtorsmaster on  salesorders.debtorno=debtorsmaster.debtorno
INNER JOIN custbranch on salesorders.debtorno=custbranch.debtorno
LEFT JOIN bio_district on debtorsmaster.did=bio_district.did and bio_district.cid=debtorsmaster.cid AND bio_district.stateid=debtorsmaster.stateid where salesorders.debtorno like 'D%' and salesorders.debtorno not like 'DL%'
";
            // AND debtorsmaster.did IN($dist)";
           
             //|| ($_SESSION['officeid']==4)){
             
             
// $dist='6,11,12'; 

if($_POST['custname'])
{
    $sql_selall.=" AND debtorsmaster.name LIKE '".$_POST['custname']."%' ";
}
if($_POST['pho'])
{
    $sql_selall.=" AND (custbranch.phoneno LIKE '".$_POST['pho']."%' OR  custbranch.faxno LIKE '".$_POST['pho']."%')";
}
if($_POST['dist'])
{
    $sql_selall.=" AND bio_district.district LIKE '".$_POST['dist']."%' ";
}



  
                             if($_SESSION['officeid']==1) 
             {
                 $sql_selall=$sql_selall;
             }
             //|| ($_SESSION['officeid']==4)){
// $dist='6,11,12';   
elseif($_SESSION['officeid']==4){
    $dist='6,11,12';   
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
    elseif($_SESSION['officeid']==2){
         $dist='1,2,3,7,13'; 
         $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
  elseif($_SESSION['officeid']==3){
 $dist='4,5,8,9,10,14';  
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";} 
elseif($_SESSION['officeid']==5){
    $sql_selall.=" and debtorsmaster.cid=1 and debtorsmaster.stateid!=14";} 

elseif ($_SESSION['officeid']==6){
$sql_selall.=" and debtorsmaster.cid!=1";
}
 $sql_selall.=" order by orderno desc";
 $crrdate=date('Y-m-d');
  // $sql_selall.=" LIMIT 50"; 
$result_allsel=DB_query($sql_selall,$db);

$slno=0;
 $ordno= $_GET['ordno'];    
 while($result_tb=DB_fetch_array($result_allsel)) 
 {
 
 
 $slno++;     
  if($slno%2==0)
	 {
         
               echo '<tr class="sel" id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
   
         }
         else
         {
	 echo '<tr class="sel" id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
         }
        
//if(($result_tb["due_date1"] <= $crrdate && $result_tb["actual_date1"]== '0000-00-00')  || ($result_tb["due_date2"] <= $crrdate && $result_tb["actual_date2"]== '0000-00-00') || ($result_tb["actual_date3"]=='0000-00-00' && $result_tb["due_date3"]<=$crrdate && $result_tb["close_status"]==0)) {
  echo '<td class="tdl">'.$slno.'</td>';
  echo '<td  class="tdl">'.$result_tb["debtorno"].'</td>';
                                         echo '<td class="tdl">'.$result_tb["orderno"].'</td>';   
   echo '<td class="tdl">'.ConvertSQLDate($result_tb["orddate"]).'</td>';  
       echo '<td class="tdl">'.$result_tb["name"].'</td>';  
               echo '<td class="tdl">'.$result_tb["district"].'</td>';   
  echo '<td class="tdl">'.$result_tb["cn"].'</td>';
   echo "</tr>" ;
 
 }    
echo "</table>";
echo "</div>";
//echo "</form>";
echo "<td></tr></table>";
echo "</div>";
}
//for calls Due today end
//if($_GET['status']==0){
//echo "<table style='width:60%'><tr><td>";  
//echo "<div style='height:400px; overflow:auto;'>"; 
//echo "<fieldset style='width:90%;'>";     
//     echo "<legend><h3>Sale order Registered</h3>";
//     echo "</legend>";         
//     echo "<table style='border:1 solid #F0F0F0;width:100%'>";
//     echo '<tr>
//     <td style="border-bottom:1px solid #000000">OrderNo</td> 
//                <td style="border-bottom:1px solid #000000">Customer Name</td>  
//                <td style="border-bottom:1px solid #000000">Contact No</td>
//                <td style="border-bottom:1px solid #000000">Delivery Date</td>
//                <td style="border-bottom:1px solid #000000">Customer Code</td>
//                </tr>';
//      $result="SELECT `custbranch`.`brname` , `custbranch`.`phoneno` , `custbranch`.`faxno` , `salesorders`.`orderno` , salesorders.branchcode, `salesorders`.`deliverydate`
//FROM `salesorders`
//INNER JOIN `custbranch` ON ( `salesorders`.`branchcode` = `custbranch`.`branchcode` )
//LEFT JOIN `bio_installation_status` ON ( `bio_installation_status`.`orderno` = `salesorders`.`orderno` )
//WHERE COALESCE( bio_installation_status.close_status, 0 ) =0
//AND COALESCE( bio_installation_status.installed_date, 0 ) =0
//ORDER BY salesorders.deliverydate ASC";
//                                                                                                                                
//   $result_saleorder=DB_query($result,$db); 
// while($row=DB_fetch_array($result_saleorder)){
//     $ph=$row[phoneno]."-<br>".$row[faxno];
//                printf("<tr style='background:#A8A4DB'>                
//            <td width='150px'><a id=".$row['orderno']."  href='' onclick='ordernumber(this.id);return false'>%s<a></td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//                                   </tr>",
//        $row['orderno'],
//        $row['brname'],
//         $ph,
//        $row['deliverydate'],
//         $row['branchcode']      
//        ); 

//           }
//   echo "</table>";            

// 
// echo "</fieldset>";
//  echo "</div>";   
// echo "</td></tr></table>";
//}        
?>
<script type="text/javascript">
var maxLength=100;

function charLimit(el) {
    if (el.value.length > maxLength) return false;
    return true;
}
function characterCount(el) {
//var aa=0;
//var aa=el.value.length;
    var charCount = document.getElementById('charCount');
    if (el.value.length > maxLength) el.value = el.value.substring(0,maxLength);
    if (charCount) charCount.innerHTML = maxLength - el.value.length;
	
    return true;
}

function ordernumber(str)
{   //alert(str1);

window.location="bio_ns.php?ordno=" + str ;

}
 
function closecall()
{ 
if(document.getElementById('remarks1').value.length==0)
{
alert ("Please Enter a remarks!");

document.getElementById('remarks1').focus();
return;
}
if(document.getElementById('status').value==0)
{
alert ("Please select a status!");

document.getElementById('status').focus();
return;
}
 var statu=document.getElementById('status').value;
 var feedback=document.getElementById('fbcall').value;
 var x=0;
 if(feedback==3 && statu==1){
     var r=confirm("Do you want to close");
     if (r==true)
  {
  x=1;
  }
else
  {
  x=0;
  }
 }
 var nowdate=document.getElementById('caldate').value;   
 var ordno=document.getElementById('ordernum').value;
 var pl_ststus=document.getElementById('plantstatus').value;
 var remark=document.getElementById('remarks1').value;
  

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
        
    document.getElementById("cll").innerHTML=xmlhttp.responseText;
	window.location.href="bio_nwinstallationstatus.php";
	self.location="bio_nwinstallationstatus.php";
//      $('#dinhide').show(); 
    }
  } 
  
  
xmlhttp.open("GET","bio_installation_close.php?calno=" + feedback+"&cdate="+nowdate+"&ord="+ordno+"&p_ststus="+pl_ststus+"&remarkk="+remark+"&statuss="+statu+"&close="+x,true); 
xmlhttp.send();
}

function duedate()
{ 
 var str1=document.getElementById('installed_date').value;  

if (str1=="")
  {
  document.getElementById("duedate").innerHTML="";
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
    document.getElementById("duedate").innerHTML=xmlhttp.responseText;
    document.getElementById("duedate1").focus();
    }
  }
xmlhttp.open("GET","installationstatus.php?date=" + str1,true); 
xmlhttp.send();
}

function changestatus(str)
{ 

 var ordno=document.getElementById('ordernum').value;

if (str=="")
  {
  document.getElementById("call_log").innerHTML="";
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
   document.getElementById("cl").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","nwcall_log.php?status=" +str + "&order=" +ordno ,true);
xmlhttp.send();
}
function showcalstatus(str)
{
    
   var ordno=document.getElementById('ordernum').value; 
   // window.open("call_log.php");
  controlWindow=window.open("vwcall_log.php?type="+str+"&ordno="+ordno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function showcalstatus1(str)
{
    
   var ordno=document.getElementById('ordernum').value; 
   // window.open("call_log.php");
  controlWindow=window.open("bio_incidentRegister.php?selectOrder="+ordno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function installationview(str)
{
    window.location="bio_nwinstallationstatus.php?status=" + str;
}
function Customer_Maintenance()
{
   var derno=document.getElementById('deno').value; 
   // window.open("call_log.php");
  controlWindow=window.open("Customers.php?DebtorNo="+derno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}   

function reference()
{
   var debno=document.getElementById('deno').value; 
   // window.open("call_log.php");
  // http://www.biotechin.org/bio_createleads_ref.php?DebtorNo=D104
  controlWindow=window.open("bio_createleads_ref.php?DebtorNo="+debno,"Reference Lead", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
    function payment()
{
   var derno=document.getElementById('deno').value; 
   // window.open("call_log.php");
  controlWindow=window.open("CustomerInquiry.php?CustomerID="+derno,"pay", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
   function ord()
{
   var derno=document.getElementById('ord').value; 
   // window.open("call_log.php");
  controlWindow=window.open("OrderDetails.php?OrderNumber="+derno,"ord", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}

   function editorder()
{
   var order=document.getElementById('ord').value; 
   // window.open("call_log.php");
  controlWindow=window.open("SelectOrderItems.php?ModifyOrderNumber="+order,"ord", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}

</script>