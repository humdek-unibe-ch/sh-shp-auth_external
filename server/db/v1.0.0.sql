-- add plugin entry in the plugin table
INSERT IGNORE INTO plugins (name, version) 
VALUES ('authExternal', 'v1.0.0');

-- register hook output_external_auth
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`, `priority`) VALUES 
((SELECT id FROM lookups WHERE lookup_code = 'hook_overwrite_return' LIMIT 0,1), 'auth-external-output', 'Output external authentication method', 'LoginView', 'output_external_auth', 'AuthExternalHooks', 'output_external_auth', 10);

-- register hook login
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`, `priority`) VALUES 
((SELECT id FROM lookups WHERE lookup_code = 'hook_overwrite_return' LIMIT 0,1), 'auth-external-login', 'Login external authentication method', 'LoginController', 'login', 'AuthExternalHooks', 'login', 10);

-- add field `label_auth_external_unibe`
INSERT IGNORE INTO `fields` (`id`, `name`, `id_type`, `display`) VALUES (NULL, 'label_auth_external_unibe', get_field_type_id('markdown-inline'), '1');
INSERT IGNORE INTO `styles_fields` (`id_styles`, `id_fields`, `default_value`, `help`, `disabled`, `hidden`) 
VALUES (get_style_id('login'), get_field_id('label_auth_external_unibe'), 'Oder mit Uni Bern Konto anmelden', 'Text displayed above the login button to indicate the option to sign in using a UniBE campus account.', 0, 0);

-- add field `label_auth_external_unibe_button`
INSERT IGNORE INTO `fields` (`id`, `name`, `id_type`, `display`) VALUES (NULL, 'label_auth_external_unibe_button', get_field_type_id('markdown-inline'), '1');
INSERT IGNORE INTO `styles_fields` (`id_styles`, `id_fields`, `default_value`, `help`, `disabled`, `hidden`) 
VALUES (get_style_id('login'), get_field_id('label_auth_external_unibe_button'), 'Mit UniBE Campus-Konto anmelden', 'Text displayed on the button that initiates the login process using a UniBE campus account.', 0, 0);
