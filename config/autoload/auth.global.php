<?php

return [
    'authentication' => [
        'pdo' => [
            'sql_get_roles' =>
                'select
                  ar.slug
                from
                  access_user_roles aur
                inner join user u ON u.user_id = aur.user_id
                inner join access_role ar ON ar.access_role_id = aur.access_role_id
                where
                  u.email = :identity',
            'sql_get_details' =>
                'select
                  u.user_id as id, u.name, u.email, u.attendant_id, u.status
                from
                  user u
                where
                  u.email = :identity'
        ]
    ]
];
