<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client;

use DateTime;
use GlobyApp\Zettle\API\Finance\AccountTransaction;
use GlobyApp\Zettle\API\Finance\Enum\AccountTypeGroup;
use GlobyApp\Zettle\API\Finance\PayoutInfo;
use GlobyApp\Zettle\Client\Finance\AccountTransactionBuilderInterface;
use GlobyApp\Zettle\Client\Finance\AccountTransactionParser;
use GlobyApp\Zettle\Client\Finance\PayoutInfoBuilderInterface;
use GlobyApp\Zettle\Client\Finance\PayoutInfoParser;
use GlobyApp\Zettle\IzettleClientInterface;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class FinanceClient
{
    public const BASE_URL = 'https://finance.izettle.com/organizations/%s';

    public const GET_ACCOUNT_TRANSACTIONS = self::BASE_URL . '/accounts/%s/transactions';
    public const GET_ACCOUNT_BALANCE = self::BASE_URL . '/accounts/%s/balance';

    public const GET_PAYOUT_INFO = self::BASE_URL . '/payout-info';

    /**
     * @var IzettleClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $organizationUuid;

    /**
     * @var AccountTransactionBuilderInterface
     */
    private $accountTransactionBuilder;

    /**
     * @var PayoutInfoBuilderInterface
     */
    private $payoutInfoBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid,
        AccountTransactionBuilderInterface $accountTransactionBuilder,
        PayoutInfoBuilderInterface $payoutInfoBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = $organizationUuid ? (string) $organizationUuid : 'self';
        $this->accountTransactionBuilder = $accountTransactionBuilder;
        $this->payoutInfoBuilder = $payoutInfoBuilder;
    }

    /**
     * @return AccountTransaction[]
     */
    public function getAccountTransactions(
        AccountTypeGroup $accountTypeGroup,
        DateTime $start,
        DateTime $end,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $url = sprintf(self::GET_ACCOUNT_TRANSACTIONS, $this->organizationUuid, $accountTypeGroup->value);
        $queryParams = [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'limit' => $limit,
            'offset' => $offset,
        ];

        $json = $this->client->getJson(
            $this->client->get(
                $url,
                $queryParams
            )
        );

        return $this->accountTransactionBuilder->buildFromJson($json);
    }

    public function getBalanceInfo(AccountTypeGroup $accountTypeGroup, ?DateTime $at = null): Money
    {
        $url = sprintf(self::GET_ACCOUNT_BALANCE, $this->organizationUuid, $accountTypeGroup->value);
        $response = $this->client->get($url, ['at' => $at ? $at->format('Y-m-d') : null]);
        $data = json_decode($this->client->getJson($response), true)['data'];
        $currency = new Currency($data['currencyId']);

        return new Money($data['totalBalance'], $currency);
    }

    public function getPayoutInfo(?DateTime $at = null): PayoutInfo
    {
        $url = sprintf(self::GET_PAYOUT_INFO, $this->organizationUuid);
        $json = $this->client->getJson(
            $this->client->get(
                $url,
                ['at' => $at ? $at->format('Y-m-d') : null]
            )
        );

        return $this->payoutInfoBuilder->buildFromJson($json);
    }
}
