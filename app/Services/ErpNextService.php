<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class ErpNextService
{
    protected string $baseUrl;
    protected array $authHeaders;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.erpnext.url'), '/') . '/api';
        $this->authHeaders = [
            'Authorization' => 'token ' . config('services.erpnext.key') . ':' . config('services.erpnext.secret'),
            'Accept' => 'application/json',
        ];
    }

    // ──────────────── ITEMS ────────────────

    public function listItems()
    {
        $query = ['fields' => json_encode([
            'name', 'item_code', 'item_name', 'description', 'stock_uom', 'item_group', 'image'
        ])];

        $resp = Http::withHeaders($this->authHeaders)
                ->get("{$this->baseUrl}/resource/Item", $query);

        return $resp->json()['data'] ?? [];
    }
    public function getItem(string $name): ?array
    {
        $response = Http::withHeaders($this->authHeaders)
                        ->get("{$this->baseUrl}/resource/Item/{$name}");

        return $response->json('data') ?? null;
    }

    public function createItem(array $payload)
    {
        // Ensure the required fields are passed
        $requiredFields = [
            'item_code',  // This is the unique ID for the item
            'item_name',  // This is the name of the item
            'item_group', // This should be a valid Item Group
            'stock_uom'   // This should be a valid Stock UOM
        ];

        // Validate that all required fields are in the payload
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }

        // Proceed to create the item
        $resp = Http::withHeaders($this->authHeaders)
                    ->post("{$this->baseUrl}/resource/Item", $payload);

        $json = $resp->json();
        if (!empty($json['message']) && isset($json['message']['exc'])) {
            throw new \Exception("ERPNext create error: " . json_encode($json['message']));
        }
        return $json;
    }


    public function updateItem(string $name, array $payload): array
    {
        $response = Http::withHeaders($this->authHeaders)
                        ->put("{$this->baseUrl}/resource/Item/{$name}", $payload);

        return $response->json();
    }

    public function deleteItem(string $name): bool
    {
        $response = Http::withHeaders($this->authHeaders)
                        ->post("{$this->baseUrl}/method/frappe.client.delete", [
                            'doctype' => 'Item',
                            'name' => $name,
                        ]);

        $json = $response->json();

        if (!empty($json['exc'])) {
            throw new \Exception("ERPNext delete error: " . json_encode($json['exc']));
        }

        return ($json['message'] ?? '') === 'ok';
    }

    // ──────────────── UOMs ────────────────

    public function listUOMs(): array
    {
        $response = Http::withHeaders($this->authHeaders)
                        ->get("{$this->baseUrl}/resource/UOM");

        return $response->json('data') ?? [];
    }

    public function createUOM(array $payload): array
    {
        $doc = array_merge(['doctype' => 'UOM'], $payload);

        $response = Http::withHeaders($this->authHeaders)
                        ->post("{$this->baseUrl}/method/frappe.client.insert", [
                            'doc' => $doc
                        ]);

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json();
    }

    // ──────────────── ITEM GROUPS ────────────────

    public function listItemGroups(): array
    {
        $response = Http::withHeaders($this->authHeaders)
                        ->get("{$this->baseUrl}/resource/Item Group");

        return $response->json('data') ?? [];
    }

    public function createItemGroup(array $payload)
    {
        $doc = array_merge(['doctype' => 'Item Group'], $payload);

        // Remove the parent_item_group field if it's not necessary
        unset($doc['parent_item_group']);  // Ensure parent_item_group is not passed if not required

        $resp = Http::withHeaders($this->authHeaders)
                ->post("{$this->baseUrl}/resource/Item", ['data' => $payload]);

        return $resp->json();
    }

}
