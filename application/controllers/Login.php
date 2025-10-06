<?php

/**
 * CodeIgniter3
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2024, CodeIgniter3 Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter3
 * @author	CodeIgniter3 Team
 * @copyright	Copyright (c) 2014, CodeIgniter3 Team (https://github.com/iescarro/codeigniter3)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://github.com/iescarro/codeigniter3
 * @since	Version 1.0.0
 * @filesource
 */
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
        var_dump($data['user']);
    }

    function github_callback()
    {
        $data['user'] = Social::driver('github')->user();
        var_dump($data['user']);
    }
}
