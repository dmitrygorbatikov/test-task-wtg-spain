<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail;

//use App\Domains\SystemEmail\Enums\SectionEnum;
use Illuminate\Mail\Mailable;
//use App\Infrastructure\Interfaces\Mail\MailableDescriptionInterface;
//use App\Infrastructure\Traits\Mail\NormalizeMailableNameTrait;
//use Modules\EmailSender\Dto\MailDescriptionData;

abstract class BaseMail extends Mailable
{
//    use NormalizeMailableNameTrait;
//
//    public static function getDescriptionData(): MailDescriptionData
//    {
//        return new MailDescriptionData(
//            name: static::getName(),
//            section: static::getSystemEmailSection(),
//            subject: static::getSystemEmailSubject(),
//            fromName: static::getSystemEmailFromName(),
//            fromEmail: static::getSystemEmailFromEmail(),
//            description: static::getSystemEmailDescription()
//        );
//    }

//    abstract public static function getSystemEmailSubject(): string;

//    public static function getSystemEmailFromName(): ?string
//    {
//        return null;
//    }
//
//    public static function getSystemEmailFromEmail(): ?string
//    {
//        return null;
//    }

    public static function getSystemEmailDescription(): ?string
    {
        return null;
    }

    public static function getFakeStub(): string
    {
        return 'stub';
    }
}
