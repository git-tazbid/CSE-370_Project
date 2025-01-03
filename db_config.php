<?php

    $conn = new mysqli("localhost", "root", "", "calfitplus_db");

    if ($conn -> connect_error) {
        die("Sorry, we are experiencing technical difficulties. Please try again later.". $conn -> connect_error);
    }

?>