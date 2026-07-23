<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>


</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background: #f7f3f3;
    }

    .wrapper {

        width: 100%;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;

    }

    .register-box {

        width: 550px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);

    }

    .top {

        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 4px solid #1b8ed9;
        padding: 30px 18px;
    }

    .content {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        color: #666;
    }

    input[type=text],
    input[type=email],
    input[type=password] {
        width: 100%;
        height: 36px;
        border: 1px solid #d5d5d5;
        border-radius: 3px;
        padding: 10px;
        outline: none;
    }

    input:focus {
        border: 1px solid #1b8ed9;
    }

    .col {
        width: 100%;
    }

    .terms {
        margin-top: 25px;
        display: flex;
        align-items: center;
    }

    .terms input {
        width: 18px;
        height: 18px;
        margin-right: 10px;
    }

    .terms a {

        color: #1b8ed9;
        text-decoration: none;
    }

    .button-area {
        text-align: right;
        margin-top: 25px;
    }

    .signup-btn {

        background: #1b8ed9;
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        margin-right: 190px;
    }

    .signup-btn:hover {
        background: #1279bc;
    }

    .error {
        color: red;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    .copyright {
        margin-top: 20px;
        color: #999;
        font-size: 15px;
    }
</style>

<body>

    <div class="wrapper">

        <div class="register-box">

            <div class="top">
                <b>
                    <p>Welcome Back !!</p>
                </b>
            </div>

            <div class="content">

                @if(session('error'))
                    <div class="error-box">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">

                        <label>E-mail Address</label>

                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>

                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label>Password</label>

                        <input type="password" name="password" required>

                        @error('password')
                            <span class="error">{{ $message }}</span>
                        @enderror

                    </div>

                    <div class="terms">

                      
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif

                    </div>

                    <div class="button-area">

                        <button type="submit" class="signup-btn">
                            Login
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <div class="copyright">
            © Copyright {{ date('Y') }}. All Rights Reserved.
        </div>

    </div>

</body>

</html>