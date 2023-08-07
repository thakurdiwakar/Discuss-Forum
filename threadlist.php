<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>DiscussForum</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>


    <?php
// Other code above...

$id = isset($_GET['catid']) ? $_GET['catid'] : 0;
$sql = "SELECT * FROM `categories` WHERE category_id=$id"; 
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }
} else {
    // Handle the case where the query didn't return any rows or there was an error
    echo "No category found.";
}
?>


    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        // Insert into thread db
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];


        $th_title = str_replace("<", "&lt;", $th_title);
        $th_title = str_replace(">", "&gt;", $th_title); 

        $th_desc = str_replace("<", "&lt;", $th_desc);
        $th_desc = str_replace(">", "&gt;", $th_desc); 
            

        $sno = $_POST['sno']; 
        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ( '$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your thread has been added! Please wait for community to respond
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                  </div>';
        } 
    }
    ?>



    <div class="conatiner my-4">

        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Welcome to
                    <?php echo $catname;?> Forum
                </h1>
                <p class=" col-md-8 fs-4 ">
                    <?php echo $catdesc;?>
                </p>
                <hr class="my-4">
                <p class="col-md-8 fs-4">This is a peer to peer forum. No Spam / Advertising / Self-promote in the
                    forums is not allowed. Do not post copyright-infringing material. Do not post “offensive” posts,
                    links or images. Do not cross post questions. Remain respectful of other members at all times.</p>

                <button class="btn btn-success btn-lg" type="button">Learn More</button>
            </div>
        </div>
    </div>


    <?php

         if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        
echo'
    <div class="container">

        <h1 class="py-2">Start a Discussion</h1>
        <form action=" ' . $_SERVER["REQUEST_URI"] .' " method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Problem Title</label>
        <input type="text" class="form-control" name="title" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">Please keep your title as short as possible</div>
    </div>
    <input type="hidden" name="sno" value=" '. $_SESSION["sno"]. '">

    <div class="mb-3">
        <label for="exampleFormControlTextarea1"  class="form-label">Ellaborate your concern</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="desc" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Submit</button>
    </form>
    </div>';
         }

         else{

            echo '
            <div class="container">
            <h1 class="py-2">Start a Discussion</h1> 
               <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
            </div>
            ';
         }
    ?>

    <div class="container">
        <h1 class="py-2">Browse Question</h1>

        <?php


$id = $_GET['catid'];
$sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id"; 
$result = mysqli_query($conn, $sql);

$noResult=true;
while ($row = mysqli_fetch_assoc($result)) {
    $noResult = false;
    // $result2 = mysqli_query($conn, $sql2);
    $id= $row['thread_id'];
    $title = $row['thread_title'];
    $desc = $row['thread_desc'];
    $thread_time=$row['timestamp'];
    $thread_user_id=$row['thread_user_id'];
    $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $result2 = mysqli_query($conn, $sql2);

    // Check if $row2 is not null before accessing its elements
    if ($row2 = mysqli_fetch_assoc($result2)) {
        $user_email = $row2['user_email'];
    } else {
        // Handle the case when $row2 is null or no user_email found
        $user_email = 'Unknown User';
    }

    echo '<div class="media my-3">
    <img src="img/userdefault.png" width="54px" class="mr-3" alt="...">
    <div class="media-body">
        <h5 class="mt-0"> <a class="text-dark" href="thread.php?threadid=' . $id . '"> ' . $title . ' </a></h5>
        ' . $desc . '
    </div>
    <div class="font-weight-bold my-0"> <strong>Asked by </strong>: ' . $user_email . ' at ' . $thread_time . '</div>
</div>';

}



        if($noResult){
                echo '<div class="h-100 p-4 bg-light border rounded-3">
                <h2>No Threads Found</h2>
                <p>Be the first person to ask a question</p>
               
              </div>';
                  }

?>
    </div>









    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>
<?php include 'partials/_footer.php'; ?>


</html>