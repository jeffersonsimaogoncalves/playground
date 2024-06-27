<?php

namespace App\Util;

use AshAllenDesign\FaviconFetcher\Facades\Favicon;
use Exception;
use Illuminate\Support\Str;
use Spatie\SslCertificate\SslCertificate;

class CertificateDomain
{
    public static function getDomain(string $domain): array
    {
        if (self::isValidDomain($domain)) {

            try {
                $certificate = SslCertificate::createForHostName($domain);
            } catch (Exception $ignored) {
                $certificate = null;
            }
            return [
                'domain' => $domain,
                'is_valid' => $certificate && $certificate->isValid(),
                'issuer' => $certificate ? $certificate->getIssuer() : null,
                'expiration_date' => $certificate ? $certificate->expirationDate()->diffForHumans() : null,
                'expiration_date_in_days' => $certificate ? $certificate->expirationDate()->diffInDays() : null,
                'favicon' => self::getFaviconByDomain($domain),
            ];
        } else {
            return [
                'domain' => $domain,
                'is_valid' => false,
                'issuer' => null,
                'expiration_date' => null,
                'expiration_date_in_days' => null,
                'favicon' => null,
            ];
        }
    }

    private static function isValidDomain($domain): bool
    {
        return (bool)preg_match('/^(?:[a-z0-9](?:[a-z0-9-æøå]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/isu', $domain);
    }

    private static function getFaviconByDomain(string $domain): ?string
    {
        if (!Str::contains($domain, ['http://', 'https://'])) {
            $domain = 'https://' . $domain;
        }

        try {
            return Favicon::fetch($domain)?->cache(now()->addDay())->getFaviconUrl() ?? null;
        } catch (Exception $ignored) {
            return null;
        }
    }
}
