<?php
 /**
     * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
     * array containing the HTTP server response header fields and content.
     */
    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
       // $proxy = '167.71.248.252:8080';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
$page_n = $_GET["page"];
$word = $_GET["word"];
$car_name = $_GET["name"];
$name = $car_name;

$total=0;
for ($i = 0; $i < 6; $i++){
    $total += getDataFromServer($i,$car_name);
}
$avg = $total / 5;

$price = $avg;

$price_text = (string)$price; // convert into a string
$arr = str_split($price_text, "3"); // break string in 3 character sets

$price_new_text = implode(",", $arr);  // implode array with comma

echo $price_new_text;

error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);


function getDataFromServer($word,$car_name){
    if($word==null){
$url = "https://bama.ir/car/".$car_name;

}
else{
    $url = "https://bama.ir/car/".$car_name."/all-models/all-trims?page=".$word;
}
    //Read a web page and check for errors:
$result = get_web_page( $url );

if ( $result['errno'] != 0 )
    echo "... error: bad url, timeout, redirect loop ...";

if ( $result['http_code'] != 200 )
    echo "... error: no page, no permissions, no service ...";

$page = $result['content'];

   $DOM = new DOMDocument;
   libxml_use_internal_errors(true);

   $DOM->loadHTML('<?xml encoding="utf-8" ?>'.$page);
//JSONObject json = new JSONObject();
    

   //get all H1
   $items = $DOM->getElementsByTagName('p');
   $myObj = new \stdClass();
   //$items = $DOM->getElementsByTagName('p');
    $output['data'] = array();
    $isStart = false;
    $conti = false;
    $myObj = new \stdClass();
   //display all H1 text
   
   $total_cost = 0;
   $count = 0;
   for ($i = 0; $i < $items->length; $i++){
       $tmp = $items->item($i)->nodeValue. "<br/><br/><br/>";
        if(strpos($tmp, 'دفتر')=== false
        && strpos($tmp,'محدودیت')=== false
        && strpos($tmp,'چپ گرایی')=== false
        && strpos($tmp,'کاربران')=== false
        && strpos($tmp,'سرور')=== false
        && strpos($tmp,'کاربران')=== false
        && strpos($tmp,'قیمت به میلیون تومان')=== false
        
        ){
            if(strpos($tmp, 'تومان') !== false
            /*&& strlen($tmp)>=13*/){
                $name = $items->item($i)->nodeValue;
                $count ++;
                $cost = substr($name,0,12);
                $total_cost = $total_cost + ($cost);
               // echo $cost;
            }
        


                
        }
        
      
       }
       
       

                $final_result = $total_cost*1000000;
                $average_cost = $final_result/$count;
                return $average_cost;

}

?>