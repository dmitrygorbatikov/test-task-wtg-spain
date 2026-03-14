<?php

return [
    'validation' => [
        'password_format' => 'The password must contain at least one uppercase letter, one number, and consist only of Latin characters.',
        'password_length' => 'The password must be between 8 and 64 characters long.',
        'authentication_code_not_found' => [
            'code' => 'authentication_code_not_found',
            'message' => 'Invalid code'
        ]
    ],
    'login' => [
        'invalid_credentials' => [
            'code' => 'invalid_credentials',
            'message' => 'Incorrect email or password'
        ],
    ],
    'email_already_taken' => [
        'code' => 'email_already_taken',
        'message' => 'A user with this email already exists'
    ],
    'invalid_signature' => [
        'code' => 'invalid_signature',
        'message' => 'Invalid URL signature or the link has expired.',
    ],
    'invalid_session_token' => [
        'code' => 'invalid_session_token',
        'message' => 'Invalid or expired session token.',
    ],
    'permission_access_forbidden' => [
        'code' => 'permission_access_forbidden',
        'message' => 'Access to this resource is forbidden.',
    ],
    'entity_not_found' => [
        'code' => 'entity_not_found',
        'message' => 'The entity :entity with field :field was not found.',
    ],
    'method_not_found' => [
        'code' => 'method_not_found',
        'message' => 'Method not found.',
    ],
    'method_not_allowed' => [
        'code' => 'method_not_allowed',
        'message' => 'The method is not allowed for this route.',
    ],
    'validation_error' => [
        'code' => 'validation_error',
        'message' => 'Data validation error.',
    ],
    'too_many_requests' => [
        'code' => 'too_many_requests',
        'message' => 'Too many requests. Please try again later.',
    ],
    'external_services' => [
        'some_service_error' => [
            'code' => 'external_service_error',
            'message' => 'External service error.',
        ],
    ],
];
