@component('emails.layout.main', [
    'appName' => $appName,
    'frontendUrl' => $frontendUrl,
    'year' => $year,
    'frontendDomain' => $frontendDomain
])

    @slot('content')
        @component('content.auth.code.registration-code.content', [
            'code' => $code,
            'email' => $email,
            'frontendUrl' => $frontendUrl,
        ])
        @endcomponent
    @endslot

@endcomponent
