<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ8gT3+iPBLG8qgS1i3iY41T0YjC/wK1OQ5QnC5W0T8zOq4t9z5zXf0F6W6w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            /* Full screen centered container */
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            /* font-family: Arial, sans-serif; */
        }

        .login-container {
            /* Card styling */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            /* Slightly wider max-width for the full form */
        }

        .login-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-container p.lead {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .social-icon {
            width: 18px;
            /* Sets the width to 18 pixels */
            height: 18px;
            /* Sets the height to 18 pixels */
            margin-right: 10px;
        }

        /* --- Social Button Styling --- */
        .social-btn {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 12px;
            border: 1px solid #dcdfe3;
            /* Light border as in the image */
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            color: #333;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.2s ease-in-out;
        }

        .social-btn:hover {
            background-color: #f5f5f5;
            border-color: #c0c0c0;
            color: #333;
        }

        .social-btn i.fab {
            position: absolute;
            left: 15px;
            font-size: 1.25rem;
        }

        /* OR Divider Style */
        .or-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0 25px 0;
            color: #888;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dcdfe3;
        }

        .or-divider:not(:empty)::before {
            margin-right: 15px;
        }

        .or-divider:not(:empty)::after {
            margin-left: 15px;
        }

        /* --- Form Input Styling --- */
        .form-control {
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 1rem;
            height: auto;
            /* Override default input height */
        }

        /* --- Custom Button Styling --- */
        .btn-continue {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
        }

        .btn-black {
            background-color: #000;
            color: #fff;
            border: 1px solid #000;
        }

        .btn-white {
            background-color: #fff;
            color: #333;
            border: 1px solid #dcdfe3;
            margin-top: 15px;
        }

        /* Final Text/Links */
        .final-links {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .final-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="login-container text-center">

        <h1>Login</h1>
        <p class="lead">Welcome back! Let's take you to your account.</p>

        <a href="<?= $github_login_url ?? '#' ?>" class="btn social-btn" role="button">
            <svg class="social-icon" xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 98 96">
                <path fill-rule="evenodd" d="M48.854 0C21.839 0 0 22 0 49.217c0 21.756 13.993 40.172 33.405 46.69 2.427.49 3.316-1.059 3.316-2.362l-.08-9.127c-13.59 2.934-16.42-5.867-16.42-5.867-2.184-5.704-5.42-7.17-5.42-7.17-4.448-3.015.324-3.015.324-3.015 4.934.326 7.523 5.052 7.523 5.052 4.367 7.496 11.404 5.378 14.235 4.074.404-3.178 1.699-5.378 3.074-6.6-10.839-1.141-22.243-5.378-22.243-24.283 0-5.378 1.94-9.778 5.014-13.2-.485-1.222-2.184-6.275.486-13.038 0 0 4.125-1.304 13.426 5.052a46.97 46.97 0 0 1 12.214-1.63c4.125 0 8.33.571 12.213 1.63 9.302-6.356 13.427-5.052 13.427-5.052 2.67 6.763.97 11.816.485 13.038 3.155 3.422 5.015 7.822 5.015 13.2 0 18.905-11.404 23.06-22.324 24.283 1.78 1.548 3.316 4.481 3.316 9.126l-.08 13.526c0 1.304.89 2.853 3.316 2.364 19.412-6.52 33.405-24.935 33.405-46.691C97.707 22 75.788 0 48.854 0z" fill="#24292f"></path>
            </svg>
            Continue with GitHub
        </a>

        <a href="<?= $google_login_url ?? '#' ?>" class="btn social-btn" role="button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 92" fill="none" class="social-icon">
                <path d="M90 47.1c0-3.1-.3-6.3-.8-9.3H45.9v17.7h24.8c-1 5.7-4.3 10.7-9.2 13.9l14.8 11.5C85 72.8 90 61 90 47.1z" fill="#4280ef"></path>
                <path d="M45.9 91.9c12.4 0 22.8-4.1 30.4-11.1L61.5 69.4c-4.1 2.8-9.4 4.4-15.6 4.4-12 0-22.1-8.1-25.8-18.9L4.9 66.6c7.8 15.5 23.6 25.3 41 25.3z" fill="#34a353"></path>
                <path d="M20.1 54.8c-1.9-5.7-1.9-11.9 0-17.6L4.9 25.4c-6.5 13-6.5 28.3 0 41.2l15.2-11.8z" fill="#f6b704"></path>
                <path d="M45.9 18.3c6.5-.1 12.9 2.4 17.6 6.9L76.6 12C68.3 4.2 57.3 0 45.9.1c-17.4 0-33.2 9.8-41 25.3l15.2 11.8c3.7-10.9 13.8-18.9 25.8-18.9z" fill="#e54335"></path>
            </svg>
            Continue with Google
        </a>

        <div class="or-divider">OR</div>

        <form>
            <input type="email" class="form-control" placeholder="Email Address" required>
            <input type="password" class="form-control" placeholder="Password" required>

            <div class="text-start mb-4">
                <a href="#" class="text-decoration-none" style="color: #4285F4; font-size: 0.9rem; font-weight: 500;">Reset your password?</a>
            </div>

            <button type="submit" class="btn btn-continue btn-black">
                Continue
            </button>
        </form>

        <a href="#" class="btn btn-continue btn-white" role="button">
            Don't have an account? <span style="font-weight: 600;">Sign up</span>
        </a>

        <div class="final-links">
            By continuing to use our services, you acknowledge that you have both read and agree to our
            <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>