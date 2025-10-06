<p align="center"><img src="/art/logo.png" alt="Logo CodeIgniter3 Social"></p>

<p align="center">
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/dt/iescarro/codeigniter3-social" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/v/iescarro/codeigniter3-social" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/l/iescarro/codeigniter3-social" alt="License"></a>
</p>

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback

FB_APP_ID=your-fb-app-id
FB_APP_SECRET=your-fb-app-secret
FB_CALLBACK_URL=http://localhost:8000/auth/facebook/callback

# CodeIgniter3-Social

A simple, minimal library for integrating Social Login (OAuth 2.0) into your CodeIgniter 3 projects. Easily enable login functionality for major platforms like Google, Facebook, and GitHub without managing complex API calls.

## ✨ Features

- Multi-Provider Support: Easily configure and use multiple social providers.
- Simple Setup: Follows CodeIgniter 3 conventions (Controller/Library/Config).
- OAuth 2.0 Flow Management: Handles the entire authorization and token exchange process.
- User Data Retrieval: Fetches essential user information (ID, Name, Email) from the provider.
- Customizable Scopes: Request specific user permissions (e.g., email, profile).

## ⚙️ Requirements

- CodeIgniter 3.x
- PHP 5.6 or higher (PHP 7.x recommended)
- cURL PHP extension enabled (necessary for secure API calls)
- Composer (highly recommended for dependency management)

## 📦 Installation

Option 1: Using Composer (Recommended)

- Navigate to your CodeIgniter project root directory.
- Run the following command:

```bash
composer require iescarro/codeigniter3-social
```

- Ensure your application/config/config.php file is configured to use Composer's autoloader:

```php
$config['composer_autoload'] = TRUE;
```

Option 2: Manual Installation

- Download the repository files.
- Place the Social.php file into your application/libraries/ folder.
- Manually install any third-party dependencies required by the library (check the repository's composer.json for details).

## 🔑 Configuration

1. Register OAuth Apps

   Before use, you must register your application on each provider's developer console (e.g., Google Cloud Console, Meta for Developers, GitHub Developer Settings) to obtain a Client ID and Client Secret.

2. Configure social.php

   Create a file named social.php inside your application/config/ directory. Use this file to define the credentials and settings for each provider.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['social'] = [

    // --- Google Settings ---
    'google' => [
        'client_id'     => 'YOUR_GOOGLE_CLIENT_ID',
        'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
        'redirect_uri'  => base_url('auth/google/callback'),
        'scope'         => ['email', 'profile'], // Permissions requested
    ],

    // --- GitHub Settings ---
    'github' => [
        'client_id'     => 'YOUR_GITHUB_CLIENT_ID',
        'client_secret' => 'YOUR_GITHUB_CLIENT_SECRET',
        'redirect_uri'  => base_url('auth/github/callback'),
        'scope'         => ['user:email', 'read:user'],
    ],

    // --- Facebook Settings ---
    'facebook' => [
        'client_id'     => 'YOUR_FACEBOOK_APP_ID',
        'client_secret' => 'YOUR_FACEBOOK_APP_SECRET',
        'redirect_uri'  => base_url('auth/facebook/callback'),
        'scope'         => ['email', 'public_profile'],
    ],

    // ... add other providers here
];
```

3. Set Redirect URIs

   Ensure the redirect_uri defined above is entered exactly into the respective developer console for each provider.

## 💡 Usage Example

You will typically interact with the library from a central Auth Controller.

1. Load the Library
   Auto-load the library in application/config/autoload.php or load it in your controller's constructor:

```php
$this->load->library('social'); 2. Auth Controller (application/controllers/Auth.php)
```

This controller handles the initiation (redirect) and the response (callback) for the OAuth flow.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('social'); // Assumes library is named 'social'
        $this->load->model('user_model'); // Your custom user handling model
    }

    /**
     * Step 1: Redirects the user to the provider's authorization screen.
     * @param string $provider The provider name (e.g., 'google', 'github')
     */
    public function social_redirect($provider)
    {
        try {
            $redirect_url = $this->social->get_auth_url($provider);
            redirect($redirect_url);
        } catch (Exception $e) {
            // Handle error (e.g., provider not configured)
            show_error($e->getMessage());
        }
    }

    /**
     * Step 2: Handles the callback from the provider after user authorization.
     * @param string $provider The provider name (e.g., 'google', 'github')
     */
    public function social_callback($provider)
    {
        try {
            // Get the user data from the provider (contains ID, name, email)
            $social_user = $this->social->get_user_info($provider);

            // --- Your Authentication Logic ---
            $user = $this->user_model->get_user_by_social_id($provider, $social_user->id);

            if ($user) {
                // User exists: Log them in
                $this->session->set_userdata('user_id', $user->id);
            } else {
                // New user: Create account and log them in
                $new_user_id = $this->user_model->create_social_user($provider, $social_user);
                $this->session->set_userdata('user_id', $new_user_id);
            }

            redirect(base_url('dashboard'));

        } catch (Exception $e) {
            // Handle errors (e.g., user denied permission, invalid token)
            $this->session->set_flashdata('error', 'Social login failed: ' . $e->getMessage());
            redirect(base_url('login'));
        }
    }
}
```

## 🤝 Contributing

Contributions are welcome! Please feel free to open issues or submit pull requests to improve the library.

## 📄 License

This library is released under the MIT License.
