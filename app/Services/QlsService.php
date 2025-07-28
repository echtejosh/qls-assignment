<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class QlsService
{
    protected string $username;
    protected string $password;
    protected string $companyId;
    protected string $baseUrl;

    public function __construct()
    {
        $this->username = env('QLS_API_USERNAME');
        $this->password = env('QLS_API_PASSWORD');
        $this->companyId = env('QLS_API_COMPANY_ID');
        $this->baseUrl  = env('QLS_API_BASE_URL');
    }

    protected function http()
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->withoutVerifying()
            ->baseUrl($this->baseUrl);
    }

    public function getShippingOptions(): array
    {
        $response = $this->http()->get("companies/{$this->companyId}/products");

        return $response->json();
    }

    public function createShipment(array $data): array
    {
        $response = $this->http()->post("v2/companies/{$this->companyId}/shipments", $data);

        return $response->json();
    }

    public function fetchLabel(string $labelPdfUrl): ?Response
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withoutVerifying()
            ->get($labelPdfUrl);

        if ($response->successful()) {
            return response($response->body(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="shipping_label.pdf"',
            ]);
        }

        return null;
    }
}
