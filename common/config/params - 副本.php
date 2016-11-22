<?php
return [
    'oauth2_0_config'=>[
        'client_table'        => 'oauth_clients',
        'access_token_table'  => 'oauth_access_tokens',
        'refresh_token_table' => 'oauth_refresh_tokens',
        'code_table'          => 'oauth_authorization_codes',
        'user_table'          => 'oauth_users',
        'jwt_table'           => 'oauth_jwt',
        'jti_table'           => 'oauth_jti',
        'scope_table'         => 'oauth_scopes',
        'public_key_table'    => 'oauth_public_keys',
    ]
];
