<p align="center"><img src="/art/logo.png" alt="Logo CodeIgniter3 Social"></p>

<p align="center">
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/dt/iescarro/codeigniter3-social" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/v/iescarro/codeigniter3-social" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/iescarro/codeigniter3-social"><img src="https://img.shields.io/packagist/l/iescarro/codeigniter3-social" alt="License"></a>
</p>

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

2. Configure Environment Variables (.env)
   For security and portability, all credentials must be stored in a .env file in your project root. This version utilizes the APP_URL variable to ensure consistency when deploying across different environments.

Place the following configuration into your .env file, replacing the placeholder values with the credentials obtained from each provider's developer console.

```
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback

GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=${APP_URL}/auth/github/callback

FB_APP_ID=your-fb-app-id
FB_APP_SECRET=your-fb-app-secret
FB_CALLBACK_URL=${APP_URL}/auth/facebook/callback
```

3. Set Redirect URIs

   Ensure the redirect_uri defined above is entered exactly into the respective developer console for each provider.

## 💡 Usage Example

You will typically interact with the library from a central Auth Controller.

1. Configure Routes (application/config/routes.php)
   The callback URLs must be mapped to your controller functions:

```php
$route['auth/google/callback'] = 'login/google_callback';
$route['auth/github/callback'] = 'login/github_callback';
```

2. Load the Library
   Auto-load the library in application/config/autoload.php or load it in your controller's constructor:

```php
$this->load->library('social');
```

3. Login Controller (application/controllers/Login.php)
   This controller handles the initiation (redirect) and the response (callback) for the OAuth flow.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'support']);
        $this->load->library(['social']);
    }

    function index()
    {
        $data['google_login_url'] = Social::driver('google')->redirect();
        $data['github_login_url'] = Social::driver('github')->redirect();
        $this->load->view('social/login', $data);
    }

    function google_callback()
    {
        $data['user'] = Social::driver('google')->user();
        // var_dump($data['user']);
    }

    function github_callback()
    {
        $data['user'] = Social::driver('github')->user();
        // var_dump($data['user']);
    }
}
```

## 🤝 Contributing

Contributions are welcome! Please feel free to open issues or submit pull requests to improve the library.

## 📄 License

This library is released under the MIT License.
