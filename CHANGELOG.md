# Changelog

All notable changes to the SelfHelp External Authentication Plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.0.1] - 2025-05-07

### Bug Fixes

- Properly show the user name in the profile once logged in with external auth

## [v1.0.0] - 2025-05-07

### Added

- Initial release of the External Authentication Plugin
- Integration with University of Bern (UniBe) Azure AD authentication
- JWT token verification using ES256 algorithm
- Two new hooks for the login process:
  - `auth-external-output`: Adds the UniBe login button to the login page
  - `auth-external-login`: Handles the login process when a token is received
- Customizable login button and text via two new fields in the login style:
  - `label_auth_external_unibe`: Text displayed above the login button
  - `label_auth_external_unibe_button`: Text displayed on the login button
- Secure authentication flow with public key verification
- Automatic user redirection after successful authentication

### Dependencies

- Requires SelfHelp v7.5.1+
- Requires PHP with OpenSSL support
- Uses Firebase JWT library (included in SelfHelp)
