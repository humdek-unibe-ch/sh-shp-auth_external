-- add plugin entry in the plugin table
INSERT IGNORE INTO plugins (name, version) 
VALUES ('authExternal', 'v1.0.0');

-- register hook output_external_auth
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`, `priority`) VALUES 
((SELECT id FROM lookups WHERE lookup_code = 'hook_overwrite_return' LIMIT 0,1), 'auth-external-output', 'Output external authentication method', 'LoginView', 'output_external_auth', 'AuthExternalHooks', 'output_external_auth', 10);

-- register hook login
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`, `priority`) VALUES 
((SELECT id FROM lookups WHERE lookup_code = 'hook_overwrite_return' LIMIT 0,1), 'auth-external-login', 'Login external authentication method', 'LoginController', 'login', 'AuthExternalHooks', 'login', 10);
