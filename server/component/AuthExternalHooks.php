<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../component/BaseHooks.php";
require_once __DIR__ . "/../../../../component/style/BaseStyleComponent.php";

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



    /* Public Methods *********************************************************/

    public function output_external_auth($args)
    {  
        $model = $this->get_private_property(array(
            "hookedClassInstance" => $args['hookedClassInstance'],
            "propertyName" => "model"
        ));     
        $fields = $this->execute_private_method(array(
            "hookedClassInstance" => $model,
            "methodName" => "get_db_fields"
        ));
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
                    "url" => "https://auth.unibe.ch",
                    "type" => "danger"
                ))
            )
        ));
        $div->output_content();
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
