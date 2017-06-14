<?php
/**
 * Created by PhpStorm.
 * User: romangorbatko
 * Date: 6/14/17
 * Time: 9:49 PM
 */

namespace App\Services;

use Carbon\Carbon;
use Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate;
use Spatie\SslCertificate\SslCertificate;

/**
 * Class DomainSSL
 * @package App\Services
 */
class DomainSSL
{
    /**
     * @var SslCertificate
     */
    private $domain;

    /**
     * DomainSSL constructor.
     * @param string $domain
     */
    public function __construct(string $domain)
    {
        $this->domain = SslCertificate::createForHostName($domain);
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->prepareMessage($this->domain);
    }

    /**
     * @return SslCertificate
     */
    public function getDomain(): SslCertificate
    {
        return $this->domain;
    }

    /**
     * @param SslCertificate $data
     * @return string
     */
    private function prepareMessage(SslCertificate $data): string
    {
        $tmp = [];
        $tmp[] = 'Issuer: ' . $data->getIssuer() . PHP_EOL;
        $tmp[] = 'Valid: ' . ($data->isValid() ? 'True' : 'False')  . PHP_EOL;
        $tmp[] = 'Expired In: ' . $this->getDays($data->expirationDate()) . ' days' . PHP_EOL;

        return implode(PHP_EOL, $tmp);
    }

    /**
     * @param Carbon $date
     * @return int
     */
    private function getDays(Carbon $date): int
    {
        $now = Carbon::now('UTC');

        return $now->diffInDays($date);
    }
}