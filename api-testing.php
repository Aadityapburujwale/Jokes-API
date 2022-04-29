<head> 
    <link rel="shortcut icon" type="image/x-icon" href="favicon.png">
</head> 

<?PHP 

    $url="127.0.0.2/api/?key=12&njokes=20";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result,true);
    
        if($result['status']=='true'){
                    
                    forEach($result['jokes'] as $list){

                        echo "<b>".$list['Joke']."</b><hr>";

                    }
        }else{
            echo $result['response'];
        }


        // for($i=0;$i<25;$i++)
        //     echo rand(0,100)." ";

    // echo "<pre>";
    // print_r($result);
?>