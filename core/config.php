<?php

if(is_dir($_SERVER['DOCUMENT_ROOT']."/portabilis")) {
    $config['AbsolutePath'] = $_SERVER['DOCUMENT_ROOT']."/melhor-plano"; //sem barra no final
    $config['RelativePath'] = "/melhor-plano"; //sem barra no final
}
else {
    $config['AbsolutePath'] = $_SERVER['DOCUMENT_ROOT']; //sem barra no final
    $config['RelativePath'] = "/"; //sem barra no final
}

?>