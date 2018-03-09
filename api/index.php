<?php 
    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");
    header("Content-Type: application/json; charset=UTF-8");

    require_once("../core/config.php");

    require_once("../vendor/autoload.php");

    // $result["server"] = print_r($_SERVER, true);
    // $result["request"] = print_r($_REQUEST, true);
    // $result["get"] = print_r($_GET, true);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    // $result["ch"] = print_r($ch, true);
    
    // // get the HTTP method, path and body of the request
    // $result["test"]["method"] = $_SERVER['REQUEST_METHOD'];
    // $test_request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
    // $result["test"]["request"] = print_r($test_request, true);
    // $result["test"]["input"] = json_decode(file_get_contents('php://input'),true);
    
    // // retrieve the table and key from the path
    // $result["test"]["table"] = preg_replace('/[^a-z0-9_]+/i','',array_shift($test_request));
    // $result["test"]["key"] = array_shift($test_request)+0;
    
    
    // if (($stream = fopen('php://input', "r")) !== FALSE) {

    //     $result["test"]["stream"] = print_r(stream_get_contents($stream), true);
    // }
    
    
    // if (($file = file_get_contents("php://input")) !== FALSE) {

    //     $result["test"]["file"] = $file;
    // }
    

    function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = _cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }




    // Fetch content and determine boundary
$raw_data = file_get_contents('php://input');
$boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

// Fetch each part
$parts = array_slice(explode($boundary, $raw_data), 1);
$data = array();

foreach ($parts as $part) {
    // If this is the last part, break
    if ($part == "--\r\n") break; 

    // Separate content from headers
    $part = ltrim($part, "\r\n");
    list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

    // Parse the headers list
    $raw_headers = explode("\r\n", $raw_headers);
    $headers = array();
    foreach ($raw_headers as $header) {
        list($name, $value) = explode(':', $header);
        $headers[strtolower($name)] = ltrim($value, ' '); 
    } 

    // Parse the Content-Disposition to get the field name, etc.
    if (isset($headers['content-disposition'])) {
        $filename = null;
        preg_match(
            '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', 
            $headers['content-disposition'], 
            $matches
        );
        list(, $type, $name) = $matches;
        isset($matches[4]) and $filename = $matches[4]; 

        // handle your fields here
        switch ($name) {
            // this is a file upload
            case 'userfile':
                 file_put_contents($filename, $body);
                 break;

            // default for all other files is to populate $data
            default: 
                 $data[$name] = substr($body, 0, strlen($body) - 2);
                 break;
        } 
    }

}
// $result["test"]["data"] = $data;





function _parsePut()
{
    global $_PUT;

    /* PUT data comes in on the stdin stream */
    $putdata = fopen("php://input", "r");

    /* Open a file for writing */
    // $fp = fopen("myputfile.ext", "w");

    $raw_data = '';

    /* Read the data 1 KB at a time
       and write to the file */
    while ($chunk = fread($putdata, 1024))
        $raw_data .= $chunk;

    /* Close the streams */
    fclose($putdata);

    // Fetch content and determine boundary
    $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

    if(empty($boundary)){
        parse_str($raw_data,$data);
        $GLOBALS[ '_PUT' ] = $data;
        return;
    }

    // Fetch each part
    $parts = array_slice(explode($boundary, $raw_data), 1);
    $data = array();

    foreach ($parts as $part) {
        // If this is the last part, break
        if ($part == "--\r\n") break;

        // Separate content from headers
        $part = ltrim($part, "\r\n");
        list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

        // Parse the headers list
        $raw_headers = explode("\r\n", $raw_headers);
        $headers = array();
        foreach ($raw_headers as $header) {
            list($name, $value) = explode(':', $header);
            $headers[strtolower($name)] = ltrim($value, ' ');
        }

        // Parse the Content-Disposition to get the field name, etc.
        if (isset($headers['content-disposition'])) {
            $filename = null;
            $tmp_name = null;
            preg_match(
                '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                $headers['content-disposition'],
                $matches
            );
            list(, $type, $name) = $matches;

            //Parse File
            if( isset($matches[4]) )
            {
                //if labeled the same as previous, skip
                if( isset( $_FILES[ $matches[ 2 ] ] ) )
                {
                    continue;
                }

                //get filename
                $filename = $matches[4];

                //get tmp name
                $filename_parts = pathinfo( $filename );
                $tmp_name = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

                //populate $_FILES with information, size may be off in multibyte situation
                $_FILES[ $matches[ 2 ] ] = array(
                    'error'=>0,
                    'name'=>$filename,
                    'tmp_name'=>$tmp_name,
                    'size'=>strlen( $body ),
                    'type'=>$value
                );

                //place in temporary directory
                file_put_contents($tmp_name, $body);
            }
            //Parse Field
            else
            {
                $data[$name] = substr($body, 0, strlen($body) - 2);
            }
        }

    }
    $GLOBALS[ '_PUT' ] = $data;
    return;
}


    _parsePut();

    $result["global"]["put"] = $GLOBALS['_PUT'];
    

    echo json_encode($result);