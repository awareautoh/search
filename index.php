<?php

// NOTE: Be sure to uncomment the following line in your php.ini file.
// ;extension=php_openssl.dll

// **********************************************
// *** Update or verify the following values. ***
// **********************************************

// Replace the accessKey string value with your valid access key.
$accessKey = '';

// Verify the endpoint URI.  At this writing, only one endpoint is used for Bing
// search APIs.  In the future, regional endpoints may be available.  If you
// encounter unexpected authorization errors, double-check this value against
// the endpoint for your Bing Web search instance in your Azure dashboard.
$endpoint = 'https://api.cognitive.microsoft.com/bing/v7.0/search';
// add your search term here
$term = 'laos';

function BingWebSearch ($url, $key, $query) {
    // Prepare HTTP request
    // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
    // http://php.net/manual/en/function.stream-context-create.php
    $headers = "Ocp-Apim-Subscription-Key: $key\r\n";
    $options = array ('http' => array (
                          'header' => $headers,
                           'method' => 'GET'));

    // Perform the Web request and get the JSON response
    $context = stream_context_create($options);
    $result = file_get_contents($url . "?q=" . urlencode($query), false, $context);

    // Extract Bing HTTP headers
    $headers = array();
    foreach ($http_response_header as $k => $v) {
        $h = explode(":", $v, 2);
        if (isset($h[1]))
            if (preg_match("/^BingAPIs-/", $h[0]) || preg_match("/^X-MSEdge-/", $h[0]))
                $headers[trim($h[0])] = trim($h[1]);
    }

    return array($headers, $result);
}
//check key
if (strlen($accessKey) == 32) {

    print "Searching the Web for: " . $term . "\n";

    list($headers, $json) = BingWebSearch($endpoint, $accessKey, $term);

    
    print "\nRelevant Headers:\n\n";
    foreach ($headers as $k => $v) {
        print $k . ": " . $v . "\n";
    }

    //print "\nJSON Response:\n\n";
    
    //echo json_encode(json_decode($json."\n"));

    

    $str = json_decode($json,true);

   // echo $str[1]['webPages'];

   
    //printout the value

    //echo "Total search result:"; 
    
    $str = $str['webPages']['value'];
    foreach($str as $key => $value)
{
   echo $value['name'] . '<br>';
   echo $value['snippet'].'<br>';
   //echo "<a href='$value['displayUrl']'".$value['displayUrl'] . '</a><br>';
   //echo "<a href='$value['displayUrl']'>Link</a>";
   echo "<a href='".$value['displayUrl']."'>".$value['displayUrl'].'</a><br/>';
   // etc
}
echo "image for ".$term.'<br/>';
    $str = $str['value']['images'];
    foreach($str as $key => $value)
{
   echo $value['name'] . '<br>';
   echo $value['thumbnailUrl'].'<br>';
   echo $value['thumbnailUrl'].'<br>';
   echo $value['contentUrl'].'<br>';
   // result
   //remove this
   //echo "<a href='$value['displayUrl']'".$value['displayUrl'] . '</a><br>';
   //echo "<a href='$value['displayUrl']'>Link</a>";
   
}
  

    
   


// if there is any error
} else {

    print("Invalid Bing Search API subscription key!\n");
    print("Please paste yours into the source code.\n");

}
?>
