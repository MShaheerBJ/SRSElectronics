<?php
session_start();
include ('connect.php');

// if (isset($_REQUEST['sign']))
// {
  
//     $name = $_POST['username'];
//     $pass =  md5( $_POST['password']);
//     $role = $_POST['role'];
//     if (  mysqli_query($con, "INSERT INTO  users ( user_name ,  user_pass , user_role ) VALUES
//           ('$name','$pass','$role')") )
//         {
//             header("Location: Login.php");
//         }
//         else
//         {
//             echo $con->error;
//         }
//     }


//For Admin Login
if (isset($_REQUEST['btnLoginAdmin']))
{
    $userName = "Admin";
    $userPassword = mysqli_real_escape_string($con,md5($_POST ['AdminPassword'])); 
    $result = mysqli_query($con,"SELECT user_id FROM users WHERE user_name = '$userName' and user_pass = '$userPassword'");
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);   
    $count = mysqli_num_rows($result);
    
    if($count == 1) 
    {
        $_SESSION['username'] = $userName;
        $_SESSION['password'] = $userPassword;
       header("location: admin.php");
    }
    else 
    {
       echo "Your Login Name or Password is invalid";
    }
}

//For Lab Login
if (isset($_REQUEST['btnLoginLab']))
{
    $userName = "Lab";
    $userPassword = mysqli_real_escape_string($con,md5($_POST ['LabPassword'])); 
    $result = mysqli_query($con,"SELECT user_id FROM users WHERE user_name = '$userName' and user_pass = '$userPassword'");
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    if($count == 1) 
    {
       $_SESSION['username'] = $userName;
       $_SESSION['password'] = $userPassword;
       header("location: Lab home.php");
    }
    else 
    {
      echo "Your Login Name or Password is invalid";
    }

}

//For Manufacturer Login
if (isset($_REQUEST['btnLoginManufacturer']))
{
    $userName = "Manufacturer";
    $userPassword = mysqli_real_escape_string($con,md5($_POST ['ManufacturerPassword'])); 
    $result = mysqli_query($con,"SELECT user_id FROM users WHERE user_name = '$userName' and user_pass = '$userPassword'");
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
  
    $count = mysqli_num_rows($result);
    
    if($count == 1) 
    {
        $_SESSION['username'] = $userName;
        $_SESSION['password'] = $userPassword;
       header("location:manufacturer home.php");
    }
    else 
    {
      echo "Your Login Name or Password is invalid";
    }

}
//For Adding Products
if(isset($_REQUEST['btn_addproduct']))
{
   $Name = mysqli_real_escape_string($con,$_POST['Name']);
   $ProductName = mysqli_real_escape_string($con,$_POST['ProductName']);
   $ProductQuantity = mysqli_real_escape_string($con,$_POST['ProductQuantity']);
   $ProductId = rand(111111111,999999999);

   if($query = mysqli_query($con,"INSERT INTO  products (`prod_id`,`name`,`prod_name`,`prod_quantity`,`prod_status`) VALUES ('$ProductId','$Name','$ProductName','$ProductQuantity','Untested') "))
   {
      header("location:manufacturer add product.php");
   }
   else
   {
      $con->error;
   }
}
//For Sending Products To The Lab
if(isset($_REQUEST['btn_sendProduct']))
{
   $ProductId = $_GET['id'];
   $TestId = rand(111111111,999999999);

   $qstp = mysqli_query($con,"SELECT td.test_type_id FROM products p INNER JOIN test_data td ON p.prod_name = td.prod_name WHERE p.prod_id='$ProductId'")->fetch_object()->test_type_id;
   
   if($que = mysqli_query($con,"INSERT INTO testing (`testing_id`,`prod_id`,`testing_performed`,`result`,`testing_remarks`,`testing_revised`) VALUES ('$TestId','$ProductId','$qstp','None None None','None','None')"))
   {
      if($query = mysqli_query($con,"UPDATE products SET prod_status='SENT' WHERE prod_id='$ProductId'"))
      {
      header("location:manufacturer test.php");
      }
   }
   else
   {
      $con->error;
   }
}
//For Sending All Untested Products
if(isset($_REQUEST['btn_sendAll']))
{
   if($query = mysqli_query($con,"UPDATE products SET prod_status='SENT' WHERE prod_status='Untested'"))
   {
      header("location:manufacturer test.php");
   }
   else
   {
      $con->error;
   }

}
//For Testing a Product 
if(isset($_REQUEST['btn_TestProduct']))
{
   $TestId = $_GET['id'];

   if(!NUll == $TestId)
   {
      header("location:lab testing.php?id=$TestId");
   }
   else
   {
      $con->error;
   }
}


//For Passing a Product In a Test
if(isset($_REQUEST['btn_PassProduct']))
{
   $ProductId = $_GET['id'];
   $TestId = $_GET['testid'];

   $quesel = mysqli_query($con,"SELECT result FROM testing WHERE prod_id='$ProductId' ");
   $res = mysqli_fetch_array($quesel);
   $i = explode(' ',$res[0]);

   if($TestId == 0 )
   {
      $a1=array("Pass",$i[1],$i[2]);
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Fail' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
   if($TestId == 1 )
   {
      $a1=array($i[0],"Pass",$i[2]);
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Fail' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
   if($TestId == 2)
   {
      $a1=array($i[0],$i[1],"Pass");
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Fail' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
}
//For Failing a Product In a Test
if(isset($_REQUEST['btn_FailProduct']))
{
   $ProductId = $_GET['id'];
   $TestId = $_GET['testid'];

   $quesel = mysqli_query($con,"SELECT result FROM testing WHERE prod_id='$ProductId' ");
   $res = mysqli_fetch_array($quesel);
   $i = explode(' ',$res[0]);

   if($TestId == 0 )
   {
      $a1=array("Fail",$i[1],$i[2]);
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Pass' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
   if($TestId == 1 )
   {
      $a1=array($i[0],"Fail",$i[2]);
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Pass' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
   if($TestId == 2)
   {
      $a1=array($i[0],$i[1],"Fail");
      $a2=array_replace($i,$a1);
      $r = implode(' ',$a2);
      if($i[0] == 'None'||'Pass' )
      {
      if($que = mysqli_query($con,"UPDATE testing SET result='$r',testing_revised='No' WHERE prod_id='$ProductId'"))
      {
        header("location:lab testing.php?id=$ProductId");
      }
      }
      else
      {
         header("location:lab testing.php?id=$ProductId");
      }
   }
}

//For finish testing
if(isset($_REQUEST['btn_done']))
{
   $ProductId = $_GET['id'];
   $TestId = rand(111111111,999999999);
   $queryr = mysqli_query($con,"SELECT result FROM testing WHERE prod_id='$ProductId' ");
   $res = mysqli_fetch_array($queryr);
   $index = explode(' ', $res[0]);


   if($index[0]=='Pass'&&$index[1]=='Pass'&&$index[2]=='Pass')
   {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Pass' WHERE prod_id='$ProductId'"))
      {
         header("location:lab test.php");
      }
   
   }
   if($index[0]=='Pass'&&$index[1]=='None'&&$index[2]=='None')
   {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Pass' WHERE prod_id='$ProductId'"))
      {
         header("location:lab test.php");
      }
   }
   if($index[0]=='Pass'&&$index[1]=='Pass'&&$index[2]=='None')
   {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Pass' WHERE prod_id='$ProductId'"))
      {
         header("location:lab test.php");
      }
   }
   if($index[0]=='Fail'&&$index[1]=='Fail'&&$index[2]=='Fail')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0] $ir[1] $ir[2]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }

   }
   if($index[0]=='Fail'&&$index[1]=='None'&&$index[2]=='None')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Fail'&&$index[1]=='Fail'&&$index[2]=='None')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0] $ir[1]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Pass'&&$index[1]=='Fail'&&$index[2]=='None')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[1]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Fail'&&$index[1]=='Pass'&&$index[2]=='None')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Pass'&&$index[1]=='Pass'&&$index[2]=='Fail')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[2]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Fail'&&$index[1]=='Fail'&&$index[2]=='Pass')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0] $ir[1]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Pass'&&$index[1]=='Fail'&&$index[2]=='Fail')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Pass'&&$index[1]=='Fail'&&$index[2]=='Pass')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[1]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Fail'&&$index[1]=='Pass'&&$index[2]=='Fail')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0] $ir[2]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='Fail'&&$index[1]=='Pass'&&$index[2]=='Pass')
   {
      $querytt = mysqli_query($con,"SELECT testing_performed FROM testing WHERE prod_id='$ProductId' ");
      $tt = mysqli_fetch_array($querytt);
      $ir = explode(' ',$tt[0]);
     if($que = mysqli_query($con,"UPDATE testing SET testing_revised='$ir[0]' WHERE prod_id='$ProductId' "))
     {
      if($que = mysqli_query($con,"UPDATE products SET prod_status='Fail' WHERE prod_id='$ProductId'"))
      {
      header("location:lab test.php");
      }
     }
   }
   if($index[0]=='None'&&$index[1]=='None'&&$index[2]=='None')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Pass'&&$index[2]=='None')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Fail'&&$index[2]=='None')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Pass'&&$index[2]=='Pass')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Fail'&&$index[2]=='Fail')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Pass'&&$index[2]=='Fail')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   if($index[0]=='None'&&$index[1]=='Pass'&&$index[2]=='Fail')
   {
      header("location:lab testing.php?id=$ProductId");
   }
   

}
//For Resending Products
if(isset($_REQUEST['btn_ResendProduct']))
{
   $ProdId = $_GET['id'];
   $queryrr = mysqli_query($con,"SELECT testing_revised FROM testing WHERE prod_id='$ProdId' ");
   $rr = mysqli_fetch_array($queryrr);

   if($que = mysqli_query($con,"UPDATE testing SET testing_performed ='$rr[0]',result='None None None' WHERE prod_id='$ProdId' "))
   {
      if($qued = mysqli_query($con,"UPDATE products SET prod_status ='RESENT' WHERE prod_id='$ProdId' "))
      {
      header("location:manufacturer revised.php");
      }
   }
}




?>