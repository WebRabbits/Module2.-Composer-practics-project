<?php 
use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "Verifications"]);

if(!$anException) {
    echo flash()->display("success");
}
?>



