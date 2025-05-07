<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>

<?php

/**
 * UniBE Authentication Service
 * 
 * Handles authentication with University of Bern's authentication system
 * 
 * @package    SelfHelp
 * @subpackage sh-shp-auth_external
 * @author     Stefan Kodzhabashev
 */

require_once __DIR__ . "/../../../../service/ext/firebase-php/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * UniBE Authentication Service
 * 
 * Handles authentication with University of Bern's authentication system
 * 
 * @package    SelfHelp
 * @subpackage sh-shp-auth_external
 * @author     Stefan Kodzhabashev
 */
class UnibeAuthService
{

    /**
     * The db instance which grants access to the DB.
     */
    private $db;

    /**
     * The login instance.
     */
    private $login;

    public function __construct($db, $login)
    {
        $this->db = $db;
        $this->login = $login;
    }

    /**
     * Fetch public key from the auth backend
     * 
     * @param string $authBackendUrl The base URL of the smx-auth-backend service
     * @return string The public key as PEM
     */
    public function fetchPublicKey(string $authBackendUrl): string
    {
        $url = $authBackendUrl . '/.well-known/public.key.pem';
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Accept: application/text'
            ]
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception("Failed to fetch public key from $url");
        }

        return $response;
    }

    /**
     * Verify and decode JWT token
     * 
     * @param string $token The JWT token to verify
     * @param string $publicKey The public key in PEM format
     * @return object The decoded token payload
     * @throws Exception If the token is invalid
     */
    public function verifyToken(string $token, string $publicKey): object
    {
        try {
            $decoded = JWT::decode($token, new Key($publicKey, 'ES256'));
            return $decoded;
        } catch (Exception $e) {
            // Token is invalid
            throw new Exception('Token verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Login the user
     * 
     * @param string $email The email of the user
     * @return bool True if the login was successful, false otherwise
     */
    public function login(string $email): bool
    {
        $sql = 'SELECT u.id AS id, u.id_genders AS id_genders, u.`name` AS user_name, u.id_languages AS id_languages
                FROM `users` u
                INNER JOIN userStatus us ON (us.id = u.id_status)
                AND us.`name` = "active" AND blocked <> 1 AND u.email = :email';
        $user = $this->db->query_db_first($sql, array(
            ':email' => $email
        ));
        if($user !== false){
            $_SESSION['logged_in'] = true;
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['gender'] = $user['id_genders'];
            $_SESSION['user_gender'] = $user['id_genders'];
            $_SESSION['user_name'] = $user['user_name'];
            if(isset($user['id_languages'])){
                 $_SESSION['user_language'] = $user['id_languages'];
            }
            $this->login->update_timestamp($user['id']);
        }
        return $user !== false;
    }
}
