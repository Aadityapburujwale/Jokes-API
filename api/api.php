<?PHP

header('Content-Type:application/json');

include("db.php");
mysqli_set_charset($con, 'utf8'); // Set UTF8 Charser for Marathi/Hindi Text

// Verify Request Method
if($_SERVER["REQUEST_METHOD"] == "GET"){ 
    // Check API KEY is provided or not.
    if(isset($_GET['key'])){
        // API KEY
        $KEY = $_GET['key'];

        //SQL query to verify api key/token is valid.
        $query = "SELECT * FROM USERS WHERE TOKEN='$KEY'";

        //Execute Query
        $result = mysqli_query($con,$query);

        //Number Of Rows Fetched By Query
        $isKeyPresent = mysqli_num_rows($result);

        // If API KEY/Token is valid
        if($isKeyPresent){

            //Get current client details
            $current_Client = mysqli_fetch_assoc($result);
            $current_Client_Status = $current_Client['Status']; //API Key is active or not
            $current_Client_API_Hits = $current_Client['API_Hits'];
            
            //If API Key is active
            if($current_Client_Status){

                // If Client's Daily API Hits Limit Not Exceeded.
                if($current_Client_API_Hits<5){

                    //Query For Fetching Jokes
                    $query = "SELECT * FROM Jokes";
                    $result = mysqli_query($con,$query);
                    $numberOfJokes = mysqli_num_rows($result); // Number Of Jokes In Database

                    //Maximum Number Of jokes provide per request
                    if(isset($_GET['njokes']) && is_numeric($_GET['njokes'])){
                        $njokes = $_GET['njokes'];
                        if($njokes>25){
                            $njokes = 25;
                        }else if($njokes<=0){
                            $njokes = 1;
                        }
                    }else{
                        $njokes = 1;
                    }
                    
                    // If language tag not given show random jokes.
                    if(empty($_GET['lang'])){
                        showRandomJokes();
                    }else{
                        // For Recall : Write Code Here TO show Language Specified jokes
                        $jokeLangs = explode(" ",$_GET['lang']);
                        // print_r($jokeLangs);

                        
                        $query = "SELECT * FROM Jokes ORDER BY RAND() LIMIT $njokes";
                        $result = mysqli_query($con,$query);
                        
                        while($row = mysqli_fetch_assoc($result)){
                            $jokes[] = $row;
                        }

                        echo json_encode(['status'=>'true','response'=>'Jokes Fetched Successfully.','total_jokes_fetched'=>$njokes,'jokes'=>$jokes]);
                    }
                    
                    // Update Client API Hit By +1
                    mysqli_query($con,"Update users set API_Hits=API_Hits+1 Where TOKEN='$KEY'");

                }else{
                    echo json_encode(['status'=>'false','response'=>'API Hits Limit Exceeded!','total_jokes_fetched'=>0,'jokes'=>[]]);
                }


            }else{
                echo json_encode(['status'=>'false','response'=>'API KEY is deactive!','total_jokes_fetched'=>0,'jokes'=>[]]);
            }

        }else{
            echo json_encode(['status'=>'false','response'=>'Invalid API KEY!','total_jokes_fetched'=>0,'jokes'=>[]]);
        }
        

    }else{
        echo json_encode(['status'=>'false','response'=>'Unathorised Access Denied!','total_jokes_fetched'=>0,'jokes'=>[]]);
    }
}else{
    echo json_encode(['status'=>'false','response'=>'Invalid Request.','total_jokes_fetched'=>0,'jokes'=>[]]);
}

function showRandomJokes(){

    GLOBAL $njokes,$numberOfJokes,$con;

    // Logic to fetch random joke by generating random number at server and fetch joke of that ID. This repeates some jokes.
    // for($i=0;$i<$njokes;$i++){
    //     $random = rand(1,$numberOfJokes); // Get Random Number to fetch joke of that Id.
    //     $query = "SELECT * FROM Jokes Where Id='$random'";
    //     $result = mysqli_query($con,$query);
    //     $jokes[] = mysqli_fetch_assoc($result);
    // }


    // Logic to fetch random jokes from sql clause. This gives all unique.
    $query = "SELECT * FROM Jokes ORDER BY RAND() LIMIT $njokes";
    $result = mysqli_query($con,$query);
    
    while($row = mysqli_fetch_assoc($result)){
        $jokes[] = $row;
    }

    echo json_encode(['status'=>'true','response'=>'Jokes Fetched Successfully.','total_jokes_fetched'=>$njokes,'jokes'=>$jokes]);
}

?>

                    