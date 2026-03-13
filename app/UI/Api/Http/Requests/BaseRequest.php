<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests;

use App\Domains\User\Models\User;
use Illuminate\Auth\AuthenticationException;
use App\Infrastructure\Exceptions\HeaderNotFoundException;
use App\Infrastructure\Http\Requests\BaseRequest as InfrastructureBaseRequest;

class BaseRequest extends InfrastructureBaseRequest
{
    public function tryGetAuthUser(): ?User
    {
        /** @var ?User $user */
        $user = $this->user() ?? $this->user('sanctum');

        return $user;
    }

    public function tryGetAuthByUser(): ?User
    {
        return $this->tryGetAuthUser();
    }

    /**
     * @throws AuthenticationException
     */
    public function getAuthByUser(): User
    {
        return $this->tryGetAuthByUser() ?? throw new AuthenticationException();
    }

    /**
     * @throws AuthenticationException
     */
    public function getAuthUser(): User
    {
        return $this->tryGetAuthUser() ?? throw new AuthenticationException();
    }

    public function getRealUserIp(): string
    {
        return $this->header(self::CF_CONNECTING_IP_HEADER) ?? $this->ip();
    }

    /**
     * @throws HeaderNotFoundException
     */
    public function getIpCountryCode(): string
    {
        $header = $this->tryGetIpCountryCode();

        if ($header === null) {
            throw (new HeaderNotFoundException())->setHeader($this->getCFIPCountryHeaderName());
        }

        return $header;
    }

    public function tryGetIpCountryCode(): ?string
    {
        $code = $this->header($this->getCFIPCountryHeaderName());

        if (
            is_string($code)
            && preg_match('/^([A-Z]{2}|T1)$/', $code)
        ) {
            return $code;
        }

        return null;
    }

    public function tryGetRegionName(): ?string
    {
        return $this->header($this->getCFRegionHeaderName());
    }

    public function tryGetIpCityName(): ?string
    {
        return $this->header($this->getCFIPCityHeaderName());
    }

    /**
     * The country code in ISO-3166-1 alpha-2 format.
     *  XX - Used for clients without country code data.
     *  T1 - Used for clients using the Tor network.
     *
     * @see https://developers.cloudflare.com/fundamentals/reference/http-headers/#cf-ipcountry
     */
    private function getCFIPCountryHeaderName(): string
    {
        return self::CF_IP_COUNTRY_HEADER;
    }

    private function getCFRegionHeaderName(): string
    {
        return self::CF_REGION_HEADER;
    }

    private function getCFIPCityHeaderName(): string
    {
        return self::CF_IP_CITY_HEADER;
    }
}
