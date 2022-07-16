<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
<title>Add Product</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@700&display=swap" rel="stylesheet">

<style>
.hover
{
  background:white;
  width:100%;
}
.hover:hover
{
  background: #74ebd5; 
  background: -webkit-linear-gradient(to right, #74ebd5, #acb6e5); 
  background: linear-gradient(to right, #74ebd5, #acb6e5);
  border-radius:20px;
  transition: width 2s;
  width:200px;
}
#div1
{
  transition-timing-function: ease;
}
</style>
</head>
        
<body>
    <?php
    include ('manufacturer sidenav.php');
    ?>
    <div id="content" class="p-4 p-md-5 pt-5">
<br>
    <div class="hover container-fluid" id="div1">
        <h2 class="mb-4" style="font-family: 'Big Shoulders Display', cursive;font-weight:400;letter-spacing:2px;text-align:center;">Add Products</h2>
    </div>
<br>
        <form action="dataaccess.php" method="post">
        <div class="input-field">
          <i class="fa fa-user"></i>
          <input type="text" placeholder="Name" name="Name" required />
        </div>
        <div class="input-field">
         <i class="fa fa-pencil"></i>
    <select placeholder="Product Name" name="ProductName" required>
    <option value="" disabled selected>Product Name</option>
            <?php
              include ('connect.php');
              $res = mysqli_query($con,"SELECT * FROM test_data");
              while($row = mysqli_fetch_array($res))
              {
            ?>
    <option value="<?=$row['prod_name']?>"><?=$row['prod_name']?></option>
            <?php
              }
            ?>
    </select>
        </div>
        <div class="input-field">
          <i class="fa fa-plus"></i>
          <input type="number" placeholder="Product Quantity" name="ProductQuantity"  maxlength="5" required/>
        </div>      
        <input class="btn btn-info" type="submit" name="btn_addproduct"  style=" background: #74ebd5;background: -webkit-linear-gradient(to bottom, #74ebd5, #acb6e5);  background: linear-gradient(to bottom, #74ebd5, #acb6e5);"/>
        </form>
      </div>
	
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>

