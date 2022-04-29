<?PHP 

    $con = mysqli_connect("localhost","root","","jokes_api");

    if($con === false){
        die("Not Connected");
    }
    // else{
    //     echo "Connection Successed!";
    // }

?>