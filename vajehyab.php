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
if($page_n==null){
$url = "https://www.vajehyab.com/?q=".$word;

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


   //get all H1
   $items = $DOM->getElementsByTagName('p');
   $myObj = new \stdClass();
   //$items = $DOM->getElementsByTagName('p');
    $output['data'] = array();
    $isStart = false;
    $conti = false;
    $myObj = new \stdClass();
   //display all H1 text
   for ($i = 0; $i < $items->length; $i++){
       $tmp = $items->item($i)->nodeValue. "<br/><br/><br/>";
        if(strpos($tmp, 'دفتر')=== false
        && strpos($tmp,'محدودیت')=== false
        && strpos($tmp,'چپ گرایی')=== false
        && strpos($tmp,'کاربران')=== false
        && strpos($tmp,'سرور')=== false
        && strpos($tmp,'کاربران')=== false
        && strpos($tmp,'کاربران')=== false
        
        ){
            if(strpos($tmp, 'واژه') !== false){
                $name = $items->item($i)->nodeValue;
                $myObj->vajeh = $name;
            }
            else if(strpos($tmp, 'نقش دستوری') !== false){
                    $myObj->naghshe_dastori = $items->item($i)->nodeValue;

                 }
            else if(strpos($tmp, 'آواشناسی') !== false){
                    $myObj->avashenasi = $items->item($i)->nodeValue;
                 }
            else if(strpos($tmp, 'تکیه') !== false){
                    $myObj->olgoye_tekye = $items->item($i)->nodeValue;

                 }
            else if(strpos($tmp, 'شمارگان هجا') !== false){
                    $myObj->shomare_heja = $items->item($i)->nodeValue;
                 }
            else if(strpos($tmp, 'برابر ابجد') !== false){
                    $myObj->barabar_abjad = $items->item($i)->nodeValue;
                 }
            else{
                    $myObj->bishtar = $myObj->bishtar.$items->item($i)->nodeValue;

                
            }
           
            


                
        }
        
       }
       
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

echo '<h1>'.$myObj->vajeh.'</h1>';
echo $myObj->naghshe_dastori .'</br>'.$myObj->avashenasi
.'</br>'.$myObj->olgoye_tekye.'</br>';
echo '<h2>برابر ابجد</h2
>'.$myObj->barabar_abjad;
echo '<h2>اطلاعات بیشتر</h2
>'.$myObj->bishtar;
//echo $myJSON;
//echo "</html>";
echo '<h1>JSON Format</h1>'.'</br>'.json_encode($myObj);

class Word
                        {
                                public $Word;
                            
                                public function __construct(array $data) 
                                {
                                    $this->Word = $data['word'];
                                }
                        }
?>