    <?php
    /* Attempt MySQL server connection. Assuming you are running MySQL
    server with default setting (user 'root' with no password) */
    $link = mysqli_connect("localhost", "eni", "eni", "appointment");
    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    // Escape user inputs for security
    $new_page = mysqli_real_escape_string($link, $_REQUEST['page']);
    $visits = mysqli_real_escape_string($link, $_REQUEST['visits']);
    $new_visits = mysqli_real_escape_string($link, $_REQUEST['new_visits']);
    $revenue = mysqli_real_escape_string($link, $_REQUEST['revenue']);
    // attempt insert query execution
    $sql = "INSERT INTO tbl_bordered (page, visits, new_visits, revenue) VALUES ('$new_page', '$visits', '$new_visits', '$revenue')";
    if(mysqli_query($link, $sql)){
        echo "Records added successfully.";
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    // close connection
    mysqli_close($link);
    ?>

