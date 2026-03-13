<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

/**
 * \x{2014} (—) em dash (long)
 * \x{2013} (–) en dash (medium)
 * \x{2212} (−) minus
 */
abstract class BaseRequest extends FormRequest
{
    public const CF_CONNECTING_IP_HEADER = 'CF-Connecting-IP';
    public const CF_IP_COUNTRY_HEADER = 'CF-IPCountry';
    public const CF_REGION_HEADER = 'CloudFlare-Region';
    public const CF_IP_CITY_HEADER = 'CloudFlare-IPCity';
    public const MAX_CK_EDITOR_STRING_LENGTH = 65000;
    public const EMAIL_REGEX = "/^[!#$%&'*+\\/=?^_`{|}~.a-zA-Z\\d-]+@[a-zA-Z\\d._-]+\\.[a-zA-Z\\d]{2,}$/";
    public const EMAIL_LOCAL_PART_MAX_64_REGEX = "/^[!#$%&'*+\\/=?^_`{|}~.a-zA-Z\\d-]{1,64}+@[a-zA-Z\\d._-]+\\.[a-zA-Z\\d]{2,}$/";
    public const EXTENDED_EMAIL_REGEX = '/^(?=.{1,64}@.*)(?!^[^a-zA-Z\\d].*)(?!.*[^a-zA-Z\\d]@.*)(?!.*@[^a-zA-Z\\d].*)[!#$%&\'*+\\/\\\\=?^_`{|}~.a-zA-Z\\d-]+@[a-zA-Z\\d._-]+\.[a-zA-Z\\d]{2,}$/';
    public const PASSWORD_REGEX = '/^(?=[\x20-\x7E]*?[a-z])(?=[\x20-\x7E]*?[A-Z])(?=[\x20-\x7E]*?[0-9])[\x20-\x7E]+$/';
    public const PHONE_REGEX = '/^[\+]?[0-9]{8,16}$/';
    public const PHONE_SIMPLE_REGEX = '/^\+?[0-9()\-\s]+$/';
    public const REGISTRATION_NAME_REGEX = '/^(?=.*\p{L}).+$/';
    public const SLUG_REGEX = '/^[a-z\d]+(?:-[a-z\d]+)*$/';
    // may be not use regex?
    public const ALL_SYMBOLS_REGEX = '/^[\p{L}\p{N}\s\p{P}\p{Sc}\p{So}!@#$%^&*()\-+={}[\]:";\'<>,.?\/|~’“”`´‘’£€\\\\😀-🙏]+$/';
    protected const PHONE_WITH_PLUS_REGEX = '/^\+\d+$/';
    protected const BASE_NAME_REGEX = '/^[\w\s!@#$%^&*()\-+={}[\]:";\'<>,.?\/|~’“”`´‘’\\\\]+$/';
    protected const BASE_STRING_REGEX = '/^[a-zA-Z\d_\s!@#$%^&*()\-+={}[\]:";\'<>,.?\/|~’“”`´‘’£€\\\\\x{2014}\x{2013}\x{2212}]+$/u';
    protected const BASE_LINK_REGEX = '/^(https?:\/\/)([a-zA-Z0-9-]{1,}\.)+[a-zA-Z]{2,}(\/[^\s]*)?$/';
    protected const BASE_TIME_RANGE_REGEX = '/^\s*(?:((?:[01]\d|2[0-3]):[0-5]\d)(?:\s*[\-–—−]\s*(?:[01]\d|2[0-3]):[0-5]\d)?)(?:\s*,\s*((?:[01]\d|2[0-3]):[0-5]\d)(?:\s*[\-–—−]\s*(?:[01]\d|2[0-3]):[0-5]\d)?)*\s*$/u';
    protected const BASE_COLOR_REGEX = '/^#([A-Fa-f0-9]{3,4}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/';
    protected const LINK_WITH_OPTIONAL_PROTOCOL_REGEX = '/^(https?:\/\/)?(www\.)?([a-zA-Z0-9-]{1,}\.)+[a-zA-Z]{2,}(\/[^\s]*)?$/';
    protected const BASE_STRING_REGEX_WITH_EMOJI = '/^[\w\s!@#$%^&*()\-+={}[\]:";\'<>,.?\/|~’“”`´‘’£€\\\\😀-🙏]+$/';
    protected const BASE_STRING_REGEX_WITHOUT_EMOJI = '/^(?!.*(?:\p{Extended_Pictographic}|[\x{1F1E6}-\x{1F1FF}]))[\p{L}\p{N}\p{P}\p{S}\p{Z}]+$/u';
    protected const STRING_WITH_REQUIRED_LETTER_REGEX
        = '/^(?=.*[a-zA-Z])[\w\s!@#$%^&*()\-+={}[\]:";\'<>,.?\/|~’“”`´‘’£€\\\\]+$/';
    protected const DATETIME_FORMAT = 'Y-m-d\\TH:i:sP';
    protected const FACEBOOK_REGEX = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w-]*\/)*(?:[\w\-]+\.?)(?:\/)?/i';
    protected const INSTAGRAM_REGEX = '/(?:http(?:s)?:\/\/)?(?:www\.)?instagram\.com\/(?:[a-zA-Z0-9._]+)/';
    protected const TWITTER_REGEX = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:twitter|x)\.com\/(?:[a-zA-Z0-9_]+)/';
    protected const YOUTUBE_REGEX = '/^(?:http(?:s)?:\/\/)?(?:www\.)?youtube\.com\/(?:[a-zA-Z0-9_!@£$%^&*()]+)/';
    protected const TIKTOK_REGEX = '/^(?:http(?:s)?:\/\/)?(?:www\.)?tiktok\.com\/@(?:[a-zA-Z0-9._-]+)/';
    protected const SNAPCHAT_REGEX = '/^(?:http(?:s)?:\/\/)?(?:(?:www\.)|(?:t\.))?snapchat\.com\/@?(?:[a-zA-Z0-9._-]+)/';
    protected const LINKEDIN_REGEX = '/^(?:http(?:s)?:\/\/)?(?:www\.)?linkedin\.com\/(?:in|pub|company)\/(?:[a-zA-Z0-9._-]+)/';
    protected const BLUESKY_REGEX = '/^(?:https?:\/\/)?(?:www\.)?bsky\.app\/profile\/[^\s\/]+\/?$/';

    protected const EMAIL_SEPARATED_COMMA_LIST = '/^[A-Za-z0-9\+\-\._]+@[A-Za-z0-9\-\._]+\.[A-Za-z0-9\-\._]{2,}(?:,\s*[A-Za-z0-9\+\-\._]+@[A-Za-z0-9\-\._]+\.[A-Za-z0-9\-\._]{2,})*$/';
    protected const COMPANY_NAME_REGEX = '/^[\p{L}\p{N}\s\p{P}\p{Sc}]+$/u';

    public function validationData(): array
    {
        return [...$this->all(), ...$this->route()->parameters];
    }

    public function tryGetRouteParameter(string $key): string|null|object
    {
        return $this->route()?->parameter($key);
    }

    public function parseJsonData(string $data): array
    {
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

            if (!is_array($data)) {
                return [];
            }

            return $data;
        } catch (Exception) {
            return [];
        }
    }

    public function getIp(): ?string
    {
        return $this->header(self::CF_CONNECTING_IP_HEADER) ?? $this->ip();
    }
}
