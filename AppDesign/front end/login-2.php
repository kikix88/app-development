<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!--Custom styles-->
    <style>
        :root {
            --input-padding-x: 1.5rem;
            --input-padding-y: .75rem;
        }

        body {
            background: #9bd5f7d6;
        }

        .card-signin {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }

        .card-signin .card-title {
            margin-bottom: 2rem;
            font-weight: 300;
            font-size: 70px;
        }

        .card-signin .card-body {
            padding: 2rem;
        }

        .form-signin {
            width: 100%;
        }

        .form-signin .btn {
            font-size: 80%;
            border-radius: 5rem;
            letter-spacing: .1rem;
            font-weight: bold;
            padding: 1rem;
            transition: all 0.2s;
        }

        .form-label-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-label-group input {
            height: auto;
            border-radius: 2rem;
        }

        .form-label-group>input,
        .form-label-group>label {
            padding: var(--input-padding-y) var(--input-padding-x);
        }

        .form-label-group>label {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            margin-bottom: 0;
            /* Override default `<label>` margin */
            line-height: 1.5;
            color: #495057;
            border: 1px solid transparent;
            border-radius: .25rem;
            transition: all .1s ease-in-out;
        }

        .form-label-group input::-webkit-input-placeholder {
            color: transparent;
        }

        .form-label-group input:-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-ms-input-placeholder {
            color: transparent;
        }

        .form-label-group input::-moz-placeholder {
            color: transparent;
        }

        .form-label-group input::placeholder {
            color: transparent;
        }

        .form-label-group input:not(:placeholder-shown) {
            padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
            padding-bottom: calc(var(--input-padding-y) / 3);
        }

        .form-label-group input:not(:placeholder-shown)~label {
            padding-top: calc(var(--input-padding-y) / 3);
            padding-bottom: calc(var(--input-padding-y) / 3);
            font-size: 12px;
            color: #777;
        }

        .welcome {
            font-size: 3.5rem;
            color: #1666D5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row my-5">
            <div class="col-sm-12 col-md-12 col-lg-12 mx-auto text-center">
            <img src="http://churchbuild.net/public/churchbuild_logo.jpeg" alt="ChurchBuild Logo" class="rounded" style="width:270px;height:210px;">
            <br>
            <h1 class="welcome">Welcome to ChurchBuild</h1>
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <br>
                        <form class="form-signin" method="post">
                            <div class="form-label-group">
                                <input id="inputUsername" class="form-control" placeholder="Username" name="username" required autofocus style="font-size:50px;">
                                <label for="inputUsername">Email Id</label>
                            </div>
                            <br>
                            <br>
                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required style="font-size:50px;">
                                <label for="inputPassword">Password</label>
                            </div>
                            <br>
                            <br>
                            <button class="btn btn-lg btn-warning btn-block text-uppercase" type="submit" name="login" value="Login" style="font-size:50px;">Sign in</button>
                        </form
                    </div>
                </div>
            </div>
            Any questions, please contact Lin@AnalyticsConsult.com

                <?php if($this->session->error) {
                    echo '<div class="alert alert-warning alert-dismissible fade show my-5 text-center" role="alert"><strong>' . $this->session->error .'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                } ?>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>