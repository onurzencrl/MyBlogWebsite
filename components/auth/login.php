<?php

include '../connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
};

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email = @filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = @filter_var($pass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        $_SESSION['user_id'] = $row['id'];
        header('location:../blog/index.php');
    }else{
        $message[] = 'incorrect username or password!';
    }

}



if(isset($_POST['register'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        $message[] = 'email already exists!';
    }else{
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
        }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if($select_user->rowCount() > 0){
                $_SESSION['user_id'] = $row['id'];
                header('location:../blog/index.php');
            }
        }
    }

}



?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <script src="https://kit.fontawesome.com/1c71e95d0d.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


    <!--=============== SWIPER CSS ===============-->
    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">

    <!--=============== CSS ===============-->
    <!-- <link rel="stylesheet" href="../assets/css/style2.scss"> -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <title>Giriş Yap</title>
    <style>
        body
        {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #77bef8;
            transition: 0.5s;
        }

        body.active
        {
            background:#0b111e;
        }

        .container3
        {
            position: relative;
            width: 800px;
            height: 500px;
            margin: 20px;
        }




        .blueBg
        {
            position: absolute;
            top: 40px;
            width: 100%;
            height: 420px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255 , 0.2 );
            box-shadow: 0 5px 45px rgba(0, 0, 0 ,15 );
        }

        .blueBg .box
        {
            position: relative;
            width: 50%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .blueBg .box h2
        {
            color: #fff;
            font-size: 1.2em;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .blueBg .box button
        {
            cursor: pointer;
            padding: 10px 20px;
            background: #fff;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            border: none;
        }

        .formBx
        {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: #fff;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 45px rgba(0, 0, 0, 0.25);
            transition: 0.5s ease-in-out;
            overflow: hidden;
        }

        .formBx.active
        {
            left: 50%;
        }

        .formBx .form
        {
            position: absolute;
            left: 0;
            width: 100%;
            padding: 50px;
            transition: 0.5s;
        }

        .formBx .signinForm
        {
            transition-delay:0.25s;
        }

        .formBx.active .signinForm
        {
            left: -100%;
            transition-delay:0s;

        }

        .formBx .signupForm
        {
            left: 100%;
            transition-delay:0s;

        }

        .formBx.active .signupForm
        {
            left: 0;
            transition-delay:0.25s;

        }



        .formBx .form form
        {
            width: 100%;
            display: flex;
            flex-direction: column;

        }

        .formBx .form form h3
        {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: 500;

        }

        .formBx .form form input
        {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            outline: none;
            font-size: 16px;
            border: 1px solid #333;
        }

        .formBx .form form input[type="submit"]
        {
            background: #03a9f4;
            border: none;
            color: #fff;
            max-width: 100px;
            cursor: pointer;
        }

        .formBx.active .signupForm input[type="submit"]
        {
            background: #f43648;
        }


        .formBx .form form .forgot
        {
            color: #333;
        }

        @media(max-width:991px)
        {
            .container3
            {
                max-width: 400px;
                height: 650px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .container3 .blueBg
            {
                top: 0;

                height: 100%;

            }
            .formBx
            {
                width: 100%;
                height: 500px;
                top: 0;
                box-shadow: none;
            }

            .blueBg .box
            {
                position: absolute;
                width: 100%;
                height: 150px;
                bottom: 0;
            }
            .box.signin
            {
                top: 0;
            }

            .formBx.active
            {
                left: 0;
                top: 150px;
            }

        }




    </style>
</head>
<body>
<div class="container3">
    <div class="blueBg">
        <div class="box signin">
            <p>Already Have an Account ?</p>
            <button class="signinBtn">Giriş Yap</button>
        </div>
        <div class="box signup">
            <p>Don't Have an Account ?</p>
            <button class="signupBtn">Kayıt Ol</button>
        </div>

    </div>
    <div class="formBx">
        <div class="form signinForm">
            <form action="" method="post">
                <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo '
                          <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <h1 align="center">Yanlış kullanıcı adı ya da parola!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                    }
                }
                ?>
                <h3>
                   Giriş Yap
                </h3>
                <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="pass" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Giriş" name="submit" >
            </form>

        </div>

        <div class="form signupForm" id="kayit">
            <form action="" method="post">
                <h3>
                   Kayıt Ol
                </h3>
                <input type="text" name="name" required placeholder="enter your name"  maxlength="50">
                <input type="email" name="email" required placeholder="enter your email"  maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="pass" required placeholder="enter your password"  maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="cpass" required placeholder="confirm your password"  maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Kayıt Ol" name="register" >
            </form>

        </div>

    </div>
</div>

<script>
    const signinBtn = document.querySelector('.signinBtn');
    const signupBtn = document.querySelector('.signupBtn');
    const formBx = document.querySelector('.formBx');
    const body = document.querySelector('body');

    signupBtn.onclick =function()
    {
        formBx.classList.add('active')
        body.classList.add('active')

    }

    signinBtn.onclick =function()
    {
        formBx.classList.remove('active')
        body.classList.remove('active')
    }



</script>


</body>
</html>