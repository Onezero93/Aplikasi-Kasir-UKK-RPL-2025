<?php
    $SERVER = "localhost";
    $USER = "root";
    $PASWORD = "";
    $nama_database ="kasir";

    $konek = mysqli_connect($SERVER, $USER, $PASWORD, $nama_database);

    if(!$konek){
        die ("Koneksi gagal:". mysqli_connect_error());
    }
?>