<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>


    <?php


    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_user_id = $row['thread_user_id'];
        // Query the users table to find out the name of OP
        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by = $row2['user_email'];
    }
    
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        // Insert into thread db
        $comment= $_POST['comment'];
        // $th_desc = $_POST['desc'];
        $sno = $_POST['sno']; 


        $sno = $_POST['sno']; 
        $sql = "INSERT INTO `comments` ( `comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has been added! Please wait for community to respond
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
                <h1 class="display-5 fw-bold"> <?php echo $title;?> Forum</h1>
                <p class=" col-md-8 fs-4 "><?php echo $desc;?></p>
                <hr class="my-4">
                <p class="col-md-8 fs-4">This is a peer to peer forum. No Spam / Advertising / Self-promote in the
                    forums is not allowed. Do not post copyright-infringing material. Do not post “offensive” posts,
                    links or images. Do not cross post questions. Remain respectful of other members at all times.</p>

                <p><strong>Posted by:<em><?php echo $posted_by; ?></em></strong></p>
            </div>
        </div>
    </div>
   
    <?php

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){

echo'
<div class="container">

<h1 class="py-2">Post a Comment</h1>
<form action=" '. $_SERVER["REQUEST_URI"] . ' " method="post">

<div class="mb-3">
<label for="exampleFormControlTextarea1" class="form-label">Type your comment</label>
<textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
<input type="hidden" name="sno" value=" '. $_SESSION["sno"]. '">

</div>

<button type="submit" class="btn btn-success">Post Comment</button>
</form>
</div>';
}

else{

   echo '
   <div class="container">
   <h1 class="py-2">Post a Comment</h1> 
      <p class="lead">You are not logged in. Please login to be able to post a comment</p>
   </div>
   ';
}
?>




    <div class="container">
        <h1 class="py-2">Discussion</h1>



        <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `comments` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);
    $noResult = true;
    while($row = mysqli_fetch_assoc($result)){
        $noResult = false;
        $id = $row['comment_id'];
        $content = $row['comment_content']; 
        $comment_time = $row['comment_time']; 
        $thread_user_id = $row['comment_by']; 

        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
    
   






    echo '<div class="media my-3">
    <img src="img/userdefault.png" width="54px" class="mr-3" alt="...">
    <div class="media-body">
       <p class="font-weight-bold my-0">'. $row2['user_email'] .' at '. $comment_time. '</p> '. $content . '
    </div>
</div>';
}



        if($noResult){
                echo '<div class="h-100 p-4 bg-light border rounded-3">
                <h2>No Threads Found</h2>
                <p>Be the first person to ask a question</p>
               
              </div>';
                  }

?>







        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
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