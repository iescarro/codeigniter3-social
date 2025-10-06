<?php

session_start();

require_once ROOT_PATH . '/vendor/autoload.php';

class Social
{
    static function driver($driver)
    {
        if ($driver == 'google') {
            return new Social_Google();
        } elseif ($driver == 'facebook') {
            return new Social_Facebook();
        } else if ($driver == 'github') {
            return new Social_Github();
        } else {
            throw new Exception("Driver not supported");
        }
    }
}

// Your Google OAuth 2.0 Credentials
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI')); // Must match console setting
class Social_Google extends Social
{
    var $client;

    public function __construct()
    {
        $this->init();
    }

    function init()
    {
        // Initialize the Google Client
        $this->client = new Google\Client();
        $this->client->setClientId(GOOGLE_CLIENT_ID);
        $this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $this->client->setRedirectUri(GOOGLE_REDIRECT_URI);

        // Request access to the user's profile and email
        $this->client->addScope('email');
        $this->client->addScope('profile');
        // You can optionally request offline access to get a refresh token
        // $this->client->setAccessType('offline'); 
    }

    function redirect()
    {
        return $this->client->createAuthUrl();
    }

    function user()
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
        $this->client->setAccessToken($token);

        // Store the access token in the session for future use
        $_SESSION['access_token'] = $token;

        // Fetch the user's data (profile)
        $google_oauth = new Google\Service\Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $id = $google_account_info->id;
        $name = $google_account_info->name;
        return (object)[
            'email' => $email,
            'id' => $id,
            'name' => $name,
        ];
    }
}

// --- YOUR GITHUB CREDENTIALS ---
define('GITHUB_CLIENT_ID', getenv('GITHUB_CLIENT_ID'));
define('GITHUB_CLIENT_SECRET', getenv('GITHUB_CLIENT_SECRET'));
define('GITHUB_REDIRECT_URI', getenv('GITHUB_REDIRECT_URI'));
class Social_Github extends Social
{
    var $scopes;

    function __construct()
    {
        // Permissions (scopes) you are requesting. 'user:email' is required for the email.
        $this->scopes = 'read:user,user:email';
    }

    function redirect()
    {
        $state = bin2hex(random_bytes(16));
        // Construct the GitHub authorization URL
        $auth_url = 'https://github.com/login/oauth/authorize?' . http_build_query([
            'client_id' => GITHUB_CLIENT_ID,
            'redirect_uri' => GITHUB_REDIRECT_URI,
            'scope' => $this->scopes,
            'state' => $state, // Essential for security (CSRF)
        ]);

        // Store the state in the session to verify later
        $_SESSION['oauth_state'] = $state;

        // echo '<h2>GitHub Login</h2>';
        // echo '<a href="' . htmlspecialchars($auth_url) . '" class="btn btn-dark" role="button">';
        // echo 'Login with GitHub';
        // echo '</a>';
        return  htmlspecialchars($auth_url);
    }

    function user()
    {

        // 1. Verify CSRF State
        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            die("Error: Invalid state parameter. Possible CSRF attack.");
        }

        if (isset($_GET['code'])) {
            // 2. Exchange the Code for an Access Token
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://github.com/login/oauth/access_token',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'client_id' => GITHUB_CLIENT_ID,
                    'client_secret' => GITHUB_CLIENT_SECRET,
                    'code' => $_GET['code'],
                    'redirect_uri' => GITHUB_REDIRECT_URI,
                    'state' => $_GET['state'],
                ]),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                CURLOPT_USERAGENT => 'YourAppName (your email or site url)', // Required by GitHub API
            ]);

            $response = curl_exec($ch);
            $data = json_decode($response, true);

            if (isset($data['error']) || !isset($data['access_token'])) {
                die("Error fetching token: " . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'));
            }

            $accessToken = $data['access_token'];

            // 3. Use the Access Token to Fetch User Data (Profile)
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.github.com/user',
                CURLOPT_HTTPHEADER => [
                    'Authorization: token ' . $accessToken,
                    'User-Agent: YourAppName (your email or site url)', // Required by GitHub API
                ],
                CURLOPT_POST => false,
            ]);
            $user_response = curl_exec($ch);
            $user_data = json_decode($user_response, true);

            // 4. Fetch the User's Primary Email (requires 'user:email' scope)
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.github.com/user/emails',
                CURLOPT_POST => false,
            ]);
            $email_response = curl_exec($ch);
            $email_data_array = json_decode($email_response, true);

            $primary_email = '';
            foreach ($email_data_array as $email_info) {
                if ($email_info['primary'] && $email_info['verified']) {
                    $primary_email = $email_info['email'];
                    break;
                }
            }

            curl_close($ch);

            // --- YOUR DATABASE LOGIC HERE ---
            // Use $user_data['id'] (GitHub ID) to check if the user exists.
            // Use $user_data['name'] and $primary_email for login/creation.

            // 5. Store data in session and redirect
            $_SESSION['github_id'] = $user_data['id'];
            $_SESSION['github_name'] = $user_data['name'] ?? $user_data['login'];
            $_SESSION['github_email'] = $primary_email;
            $_SESSION['access_token'] = $accessToken;

            // header('Location: index.php');
            // exit();

            return (object)[
                'id' => $user_data['id'],
                'name' => $user_data['name'] ?? $user_data['login'],
                'email' => $primary_email,
            ];
        } else {
            // Error handling if authorization was denied
            header('Location: index.php?error=' . urlencode($_GET['error_description'] ?? 'Authorization denied'));
            exit();
        }
    }
}

// --- YOUR CREDENTIALS ---
define('FB_APP_ID', 'YOUR_APP_ID');          // Replace with your App ID
define('FB_APP_SECRET', 'YOUR_APP_SECRET');  // Replace with your App Secret
define('FB_CALLBACK_URL', 'http://localhost/fb-callback.php');
class Social_Facebook extends Social
{
    var $fb;
    var $helper;
    var $permissions;

    public function __construct()
    {
        // Permissions (scopes) you are requesting from the user
        // 'email' is almost always required for login
        $this->permissions = ['email', 'public_profile'];

        // Initialize the Facebook PHP SDK
        $this->fb = new Facebook\Facebook([
            'app_id' => FB_APP_ID,
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v19.0', // Use a current version
        ]);

        $this->helper = $this->fb->getRedirectLoginHelper();
    }

    function redirect()
    {
        // Generate the URL that leads to the Facebook login page
        $loginUrl = $this->helper->getLoginUrl(FB_CALLBACK_URL, $this->permissions);
        return $loginUrl;
    }

    function redirectx()
    {
        if (isset($_SESSION['fb_access_token'])) {
            // echo "You are already logged in with Facebook.<br>";
            // echo '<a href="logout.php">Logout</a>';

            // Optional: Fetch user data immediately
            try {
                $response = $this->fb->get('/me?fields=id,name,email', $_SESSION['fb_access_token']);
                $user = $response->getGraphUser();
                echo "<pre>";
                print_r($user->asArray());
                echo "</pre>";
            } catch (Facebook\Exception\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
            } catch (Facebook\Exception\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }
        } else {
            // Generate the URL that leads to the Facebook login page
            $loginUrl = $this->helper->getLoginUrl(FB_CALLBACK_URL, $this->permissions);

            // Display the login button (using the beautifully styled link from the previous example)
            echo '<h2>Facebook Login</h2>';

            // Note: You should adapt this anchor tag to link to $loginUrl
            echo '<a href="' . htmlspecialchars($loginUrl) . '" class="btn btn-primary" role="button">';
            echo 'Login with Facebook';
            echo '</a>';
        }
    }
}
