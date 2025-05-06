<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../component/BaseHooks.php";
require_once __DIR__ . "/../../../../component/style/BaseStyleComponent.php";
require_once __DIR__ . "/../../../../component/style/Login/LoginModel.php";
require_once __DIR__ . "/../service/UnibeAuthService.php";

/**
 * The class to define the hooks for the plugin.
 */
class AuthExternalHooks extends BaseHooks
{
    /* Constructors ***********************************************************/

    /* Private Properties *****************************************************/

    /**
     * The math executor instance
     */
    private $executor;

    /**
     * The constructor creates an instance of the hooks.
     * @param object $services
     *  The service handler instance which holds all services
     * @param object $params
     *  Various params
     */
    public function __construct($services, $params = array())
    {
        parent::__construct($services, $params);
    }

    /* Private Methods *********************************************************/

    /**
     * Redirect to smx-auth-backend for authentication
     * 
     * @param string $authBackendUrl The base URL of the smx-auth-backend service
     * @param string $redirectUrl URL in your app where the user should return after authentication
     * @param string $callbackUrl Optional URL that will receive user data via POST
     * @param bool $forceLogout Force user to log out and log in again
     * @return string The URL to redirect to
     */
    function redirectToAuth(
        string $authBackendUrl,
        string $redirectUrl,
        string $callbackUrl = null,
        bool $forceLogout = false
    ): string {
        $signinUrl = $authBackendUrl . '/signin';

        $queryParams = [
            'redirectUrl' => $redirectUrl
        ];

        if ($callbackUrl) {
            $queryParams['callbackUrl'] = $callbackUrl;
        }

        if ($forceLogout) {
            $queryParams['forceLogout'] = 'true';
        }

        $signinUrl .= '?' . http_build_query($queryParams);

        return $signinUrl;
    }

    /**
     * Get the callback URL
     * 
     * @return string The callback URL
     */
    private function getRedirectUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        return $protocol . "://" . $host . $uri;
    }

    private function getLoginModel($args): LoginModel
    {
        return $this->get_private_property(array(
            "hookedClassInstance" => $args['hookedClassInstance'],
            "propertyName" => "model"
        ));
    }


    /* Public Methods *********************************************************/

    /**
     * Output the external auth button
     * 
     * @param array $args The arguments
     */
    public function output_external_auth($args)
    {
        $model = $this->getLoginModel($args);
        $fields = $model->get_db_fields();
        $div = new BaseStyleComponent("div", array(
            "css" => "authExternalUnibe my-4",
            "children" => array(
                new BaseStyleComponent("markdown", array(
                    "css" => "authExternalUnibeText text-center text-muted",
                    "text_md" => "Oder mit Uni Bern Konto anmelden"
                )),
                new BaseStyleComponent("button", array(
                    "css" => "authExternalUnibeButton  w-100",
                    "label" => "Mit UniBE Campus-Konto anmelden",
                    "url" => $this->redirectToAuth(AUTH_EXTERNAL_UNIBE, $this->getRedirectUrl()),
                    "type" => "danger"
                ))
            )
        ));
        $div->output_content();
    }

    public function login($args)
    {
        if (isset($_GET['token'])) {
            try {
                $token = $_GET['token'];
                $authService = new UnibeAuthService($this->db, $this->login);
                $publicKey = $authService->fetchPublicKey(AUTH_EXTERNAL_UNIBE);
                $decodedToken = $authService->verifyToken($token, $publicKey);

                // Extract user data from the token
                $email = $decodedToken->email;
                if ($authService->login($email)) {
                    $model = $this->getLoginModel($args);
                    header('Location: ' . $model->get_target_url());
                } else {
                    throw new Exception("Login failed");
                }
            } catch (Exception $e) {
                $this->set_private_property(array(
                    "hookedClassInstance" => $args['hookedClassInstance'],
                    "propertyName" => "failed",
                    "propertyNewValue" => true
                ));
            }
        } else {
            return $this->execute_private_method($args);
        }
    }

    /**
     * Get the plugin version
     */
    public function get_plugin_db_version($plugin_name = 'authExternal')
    {
        return parent::get_plugin_db_version($plugin_name);
    }
}
?>
