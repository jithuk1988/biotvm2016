<?php
$PageSecurity = 80;
include('includes/session.inc');
  echo "<fieldset style='width:95%;'>";
  echo "<legend><h3>All Mails</h3>";
  echo "</legend>";

  echo "<div style='height:350px; overflow:auto;'>";
  echo "<table style='width:100%;' id='mail'>";
  echo "<thead>
         <tr BGCOLOR =#800000>
         <th>" . _('Sl no') . "</th>
         <th>" . _('From') . "</th>
         <th>" . _('Subject') . "</th>
         <th>" . _('Date') . "</th>
         <th></th><th></th><th></th>
         </tr></thead>";

         $select_email="SELECT * FROM bio_email where status=0";
         $result_email=DB_query($select_email,$db);
         $no=1;

         while($row=DB_fetch_array($result_email))
         {

               $title1=$row['subject'];
             //  echo'<input type="hidden" id="sub" name="sub" value="'.$title1.'">';
            echo"<tr style='background:#D0D0D0'>
                     <td>$no</td>
                     <td><b>".$row['from_name']."</b></td>
                     <td><b>".$title1."</b></td>
                     <td><b>".$row['date']."</b></td>
                     <td><a href='#' id='".$row['id']."' onclick='showDetails(this.id)'>Select</a></td>
                     <td><a href='#' id='".$row['id']."' onclick='showMail(this.id)'>View</a></td>
                     <td><a href='#' id='".$row['id']."' onclick='delMail(this.id)'>Delete</a></td>

                 </tr>";

                 $no++;

        }

      echo "</div></table>";
      echo "</fieldset>";


?>