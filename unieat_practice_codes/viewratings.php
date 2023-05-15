<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]==2)){
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['logout'])){
        session_destroy();
        header("Location: Login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniEat</title>
</head>
<body>
    <header>
        <div id="logo">UniEat</div>
        <form id="searchbar" method="post">
            <input type="text" id="searchbar" name='searchbar'>
            <input type="submit" name="search" value="search"></input>
        </form>
        
        <div id="keranjang">
            <a href="Keranjang.php" id="keranjanglink">Keranjang</a>
            <?php
                $adaisi=false;
                foreach($_SESSION['cart'] as $x=>$val){
                    if($val['itemid']!=0){
                        $adaisi=true;
                        break;
                    }
                }

                if($adaisi){
                    echo'<div id="buletan"></div>';
                }
            ?>
        </div>
        
        <div id="profile">
            <?php
                echo $_SESSION['user']['UserUsername'];
            ?>
            <div id="dropdownmenu">
                <form method="post">
                    <a href="customer.php">Home</a>
                    <a href="cushistory.php">History</a>
                    <a href="customersetting.php">Settings</a>
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>
    <div id="content">

        <a href="customer.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->
        
        <h3>View Ratings</h3>
        <div id="history">
            <?php
                $d=mysqli_query($conn, "SELECT * FROM orderheader oh JOIN shop s ON oh.ShopID=s.ShopID WHERE oh.ShopID LIKE '".$_SESSION['ratingtosee']."'");

                $rating=mysqli_query($conn, "SELECT AVG(RatingScore) as averagescore FROM orderheader od JOIN shop s ON od.ShopID=s.ShopID WHERE s.ShopID LIKE '".$_SESSION['ratingtosee']."' AND OrderStatus=2")->fetch_assoc()['averagescore'];

                
                if(mysqli_num_rows($d)!=null){
                    $d=$d->fetch_assoc();
                    echo '<div id="shopshistory">';
                    
                    echo '<div id="shoppiccontainerhis"><img id="shoppichis" src="shoppictures/'.$d['ShopID'].'.png" alt="LOGO">';
    
                    echo '</div>';
                    echo '<div id="historydesc">';
                    echo '<h2>'.$d['ShopName'].'</h2>';
                    echo '<span>Rated: '.$rating.'</span>';
                    
                    echo '</div>';
                    echo '</div>';
    
                    $query=mysqli_query($conn, "SELECT * FROM orderheader od JOIN user u ON od.CustomerID=u.UserID WHERE ShopID LIKE '".$_SESSION['ratingtosee']."' AND OrderStatus=2 ORDER BY OrderID DESC");
                    
                    while($d=$query->fetch_assoc()){
    
                        echo '<div id="orderdetailcontainer">';
                        echo '<div id="odcontainertitle">';
                        echo '<h3>'.$d['UserUsername'].'</h3>';
                        echo '<span>Rated: '.$d['RatingScore'].'</span>';
                        echo '</div>';
                        echo 'Comment:';
                        echo '<div id="notes">'.$d['RatingComment'].'</div>';
                        echo '</div>';
                    }
                }else{
                    echo 'This shop does not have any ratings yet :(';
                }
            ?>
        </div>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>