<?php

$PageSecurity = 80;  
include('includes/session.inc');
   $id=$_GET['id'];

 $sql="SELECT closedate from bio_closingdate WHERE id='".$id."'";
                                     $result=DB_query($sql,$db);
                                     $myrow= DB_fetch_array($result);
  echo '<tr id="tbl" name="tbl"><td>Closing Date*</td><td><input type="text" name="editdate" id="editdate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" style="width:146px" value='.$myrow[closedate].'></td><td><input type="submit" id="edit" name="edit" value="Edit"><input type="hidden" id="id2" name="id2" value="'.$id.'"></td></tr>';
 ?>