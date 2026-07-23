<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #ececec;
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 30px;
        }

        .register-card {
            width: 650px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .12);
        }

        .card-header {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            border-bottom: 4px solid #0088d7;
            padding: 30px 18px;
        }



        .card-body {
            padding: 18px;
        }

        .field {
            margin-bottom: 22px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-size: 15px;
        }

        .field input {
            width: 100%;
            height: 36px;
            border: 1px solid #d8d8d8;
            border-radius: 3px;
            padding: 0 15px;
            font-size: 15px;
            outline: none;
        }

        .field input:focus {
            border-color: #0088d7;
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .half {
            width: 50%;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .checkbox {
            font-size: 14px;
            color: #666;
        }

        .checkbox input {
            margin-right: 8px;
        }

        .checkbox a {
            color: #0088d7;
            text-decoration: none;
        }

        .register-btn {
            background: #0088d7;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 4px;
            font-size: 17px;
            cursor: pointer;
            transition: .3s;
        }

        .register-btn:hover {
            background: #006fb0;
        }

        .login-link {
            margin-top: 30px;
            text-align: center;
            color: #666;
        }

        .login-link a {
            color: #0088d7;
            text-decoration: none;
            font-weight: 500;
        }

        .copyright {
            margin-top: 20px;
            color: #888;
            font-size: 14px;
        }

        @media(max-width:768px) {

            .register-card {
                width: 100%;
            }

            .row {
                flex-direction: column;
            }

            .half {
                width: 100%;
            }

            .bottom {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }

            .signup-box {
                padding: 25px;
            }

        }
    </style>
</head>

<body>

    <div class="register-wrapper">

        <div class="register-card">

            <div class="card-header">

                <b>
                    <p>Create Your Account </p>
                </b>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="field">
                        <label>Business Owner Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="field">
                        <label>E-mail Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}">
                    </div>

                    <div class="row">

                        <div class="field half">
                            <label>Password</label>
                            <input type="password" name="password" required>
                        </div>

                        <div class="field half">
                            <label>Password Confirmation</label>
                            <input type="password" name="password_confirmation" required>
                        </div>

                    </div>

                    

                    <div class="bottom">

                        <label class="checkbox">
                            <input type="checkbox" required>
                            I agree with
                            <a href="#">Terms of Use</a>
                        </label>

                        <button class="register-btn" type="submit">
                            Sign Up
                        </button>

                    </div>

                </form>


                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign In!</a>
                </div>

            </div>

        </div>

        <div class="copyright">
            © Copyright 2026. All Rights Reserved.
        </div>

    </div>

</body>