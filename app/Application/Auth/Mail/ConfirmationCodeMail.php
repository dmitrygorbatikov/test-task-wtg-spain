<?php

declare(strict_types=1);

namespace App\Application\Auth\Mail;

use App\Domains\AuthenticationCode\Models\AuthenticationCode;
//use App\Domains\SystemEmail\Enums\{PartEnum, SectionEnum};
use App\Domains\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Infrastructure\Mail\BaseMail;

class ConfirmationCodeMail extends BaseMail
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly User $user,
        private readonly AuthenticationCode $code,
    ) {
        $this->subject(__('emails/auth/registration-code.subject'));
    }

    public function build(): self
    {
        $appName = config('app.name');
        $email = $this->user->email;
        $code = $this->code->code;
        $frontendDomain = config('app.frontend_domain');
        $frontendUrl = config('app.frontend_url') . '/register';
        $year = now()->year;

        return $this->view('components.auth.code.registration-code', compact(
            'appName',
            'email',
            'code',
            'frontendDomain',
            'frontendUrl',
            'year',
        ));
    }
}
