<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
      $str1=$_GET['combo'];
     $str2=   $_GET['combo2'];
     $type=$_GET['str3'];
     $type2=$_GET['str4'];
     
       if($type2!="")
        { 
            echo '<select name="loc2" id="loc2" style="width:190px" tabindex=1>';
     echo      $sql="SELECT loccode,locationname,managed FROM  locations  WHERE managed=".$type2."";

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
       }
     if($type!="")
        { 
        $sql="SELECT loccode,locationname,managed FROM  locations  WHERE managed=".$type."";

            $rst=DB_query($sql,$db);
 echo '<select name="loc1" id="loc1" style="width:190px" tabindex=1 >';
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
       }
  
     
   if($str1==B)
     {
         
           echo "<td>Main Category</td><td><select name='main' id='main' style='width:190px;' onchange='show2(this.value)'>";
        $sql="SELECT
        maincatid,
        maincatname
        FROM bio_maincategorymaster WHERE maincatid!=1";

        $rst=DB_query($sql,$db);
         echo '<option value=0></option>';
         while($myrowc=DB_fetch_array($rst))
          {
            echo '<option value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
           }
     }
              if($str2)
 
              { 
                echo '<td>Description<td>';  
                 echo "<select name='sub' id='sub' style='width:190px' >";
                 $sql="SELECT
                 maincatid,
                  subcatid,
                   stockcategory.categorydescription
                   FROM bio_maincat_subcat,stockcategory
                     where bio_maincat_subcat.maincatid=$str2  AND stockcategory.categoryid=bio_maincat_subcat.subcatid and stockcategory.stocktype='F' ";
                     $rst=DB_query($sql,$db);
                       echo '<option value=0></option>';//
                  while($myrowc=DB_fetch_array($rst))
        {
          if ($myrowc[subcatid]==$sub)
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
     
 
 
?>
