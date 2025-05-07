# SelfHelp Plugin - External Auth

A SelfHelp plugin that enables external authentication methods for your SelfHelp platform. Currently supports University of Bern (UniBe) Azure AD authentication.

## Features

- Integration with University of Bern (UniBe) Azure AD authentication
- JWT token verification using ES256 algorithm
- Customizable login button and text
- Seamless user experience with automatic redirection
- Secure authentication flow with public key verification

## Requirements

- SelfHelp v7.5.1+
- PHP with OpenSSL support
- Firebase JWT library (included in SelfHelp)
- Internet connection to access the authentication backend

## Installation

1. Download the code into the `plugins` folder of your SelfHelp installation
2. Checkout the latest version (currently v1.0.0)
3. Execute all `.sql` scripts in the `server/db` folder in their version order
   - Currently only `v1.0.0.sql` is available

## Configuration

The plugin uses the following configuration settings:

- The authentication backend URL is defined in `server/service/globals.php`
- Default value: `https://selfhelp.philhum.unibe.ch`
- You can modify this URL to point to your own authentication backend if needed

## Usage

Once installed and configured, the plugin will:

1. Add a UniBe login button to the standard SelfHelp login page
2. When clicked, redirect users to the UniBe authentication service
3. After successful authentication, redirect back to SelfHelp with a JWT token
4. Verify the token and log the user in if valid

## Customization

The plugin adds the following customizable fields to the login style:

- `label_auth_external_unibe`: Text displayed above the login button
- `label_auth_external_unibe_button`: Text displayed on the login button

You can customize these texts in the SelfHelp admin interface under Styles > Login.

## How It Works

1. The plugin hooks into the SelfHelp login process using two hooks:
   - `auth-external-output`: Adds the UniBe login button to the login page
   - `auth-external-login`: Handles the login process when a token is received

2. Authentication flow:
   - User clicks the UniBe login button
   - User is redirected to the UniBe authentication service
   - After successful authentication, user is redirected back with a JWT token
   - Plugin verifies the token using the public key from the authentication service
   - If valid, user is logged in and redirected to the target URL

## Troubleshooting

- If the login button doesn't appear, check if the plugin is properly installed and the SQL scripts have been executed
- If authentication fails, check the connection to the authentication backend
- For more detailed error information, check your SelfHelp logs

## License

This plugin is licensed under the Mozilla Public License, v. 2.0. See the LICENSE file for details.

