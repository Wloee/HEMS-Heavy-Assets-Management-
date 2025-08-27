<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - HEMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .form-section {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            max-width: 350px;
            width: 100%;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .form-box p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #aaa;
        }

        .otp-button {
            background-color: #eee;
            color: #333;
            margin-top: 10px;
        }

        .otp-button:hover {
            background-color: #ddd;
        }

        .image-section {
            flex: 1;
            background-color: #111;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .image-section img {
            max-width: 80%;
            height: auto;
        }

        .image-section p {
            margin-top: 30px;
            font-style: italic;
            font-size: 16px;
            text-align: center;
        }

        /* Error messages styling */
        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .error-messages ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-messages li {
            margin-bottom: 5px;
        }

        /* Remember me checkbox */
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me input {
            margin-right: 8px;
            margin-bottom: 0;
            width: auto;
        }

        .remember-me label {
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }

        /* Register link */
        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Loading state */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .form-section {
                padding: 30px 20px;
            }

            .image-section {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-section">
        <div class="form-box">
            <h2>HEMS</h2>
            <p>Login menggunakan akun yang terdaftar, atau hubungi Admin</p>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ url('/login') }}" id="loginForm">
                @csrf

                <input
                    type="text"
                    name="login"
                    placeholder="Username / Email"
                    value="{{ old('login') }}"
                    required
                    autocomplete="username"
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                >

                <!-- Remember Me -->
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" id="loginButton">Login</button>
            </form>

            <div class="divider">OR CONTINUE WITH</div>

            <button class="otp-button" onclick="showOtpMessage()">WhatsApp OTP</button>
        </div>
    </div>

    <div class="image-section">
        <img src="{{ asset('images/excavator.png') }}" alt="Excavator">
        <p>"Aplikasi ini mempermudah alur kerja, memungkinkan pengelolaan peralatan berat yang efisien dan memberikan hasil mengesankan kepada klien dengan cepat."</p>
    </div>
</div>

</body>
</html>
