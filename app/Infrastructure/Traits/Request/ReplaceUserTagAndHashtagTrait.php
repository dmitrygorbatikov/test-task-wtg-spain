<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

trait ReplaceUserTagAndHashtagTrait
{
    public const REGEX_REPLACE_USER_TAG = '/@\[(.*?)\]\(user:.*?\)/';

    public const REGEX_REPLACE_HASHTAG = '/#\[(.*?)\]/';

    public function processText(string $text): string
    {
        $text = preg_replace(self::REGEX_REPLACE_USER_TAG, '@$1', $text);

        return preg_replace(self::REGEX_REPLACE_HASHTAG, '#$1', $text);
    }
}
