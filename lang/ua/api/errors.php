<?php

return [
    'validation' => [
        'password_format' => 'Пароль має містити принаймні одну велику літеру, одну цифру та складатися лише з латинських символів.',
        'password_length' => 'Пароль має містити від 8 до 64 символів.',
        'authentication_code_not_found' => [
            'code' => 'authentication_code_not_found',
            'message' => 'Невірний код'
        ]
    ],
    'login' => [
        'invalid_credentials' => [
            'code' => 'invalid_credentials',
            'message' => 'Неправильний email або пароль'
        ],
    ],
    'email_already_taken' => [
        'code' => 'email_already_taken',
        'message' => 'Користувач з цим email уже існує'
    ],
    'invalid_signature' => [
        'code' => 'invalid_signature',
        'message' => 'Неправильна підпис URL або посилання протерміноване.',
    ],
    'invalid_session_token' => [
        'code' => 'invalid_session_token',
        'message' => 'Невірний або прострочений токен сесії.',
    ],
    'permission_access_forbidden' => [
        'code' => 'permission_access_forbidden',
        'message' => 'Доступ до цього ресурсу заборонено.',
    ],
    'entity_not_found' => [
        'code' => 'entity_not_found',
        'message' => 'Об’єкт :entity з полем :field не знайдено.',
    ],
    'method_not_found' => [
        'code' => 'method_not_found',
        'message' => 'Метод не знайдено.',
    ],
    'method_not_allowed' => [
        'code' => 'method_not_allowed',
        'message' => 'Метод не дозволений для цього маршруту.',
    ],
    'validation_error' => [
        'code' => 'validation_error',
        'message' => 'Помилка валідації даних.',
    ],
    'too_many_requests' => [
        'code' => 'too_many_requests',
        'message' => 'Забагато запитів. Спробуйте пізніше.',
    ],
    'external_services' => [
        'some_service_error' => [
            'code' => 'external_service_error',
            'message' => 'Помилка зовнішнього сервісу.',
        ],
    ],
];
