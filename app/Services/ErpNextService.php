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
        $requiredFields = [
            'item_code',
            'item_name',
            'item_group',
            'stock_uom'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }

        $response = Http::withHeaders($this->authHeaders)
            ->post("{$this->baseUrl}/resource/Item", $payload);

        if ($response->failed()) {
            throw new \Exception("ERPNext create error: " . $response->body());
        }

        return $response->json();
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

    public function itemExists(string $itemCode): bool
    {
        $response = Http::withHeaders($this->authHeaders)
            ->get("{$this->baseUrl}/resource/Item/{$itemCode}");

        return $response->successful();
    }

    public function uploadImage($file)
    {
        $response = Http::withHeaders($this->authHeaders)
            ->attach('file', file_get_contents($file->path()), $file->getClientOriginalName())
            ->post("{$this->baseUrl}/method/upload_file", [
                'is_private' => 0 // Make the file public
            ]);

        if ($response->failed()) {
            throw new \Exception("Failed to upload image: " . $response->body());
        }

        return $response->json();
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
    // Corrected UOM creation using proper endpoint and field names
    $response = Http::withHeaders($this->authHeaders)
        ->post("{$this->baseUrl}/resource/UOM", [
            'uom_name' => $payload['uom_name'],
            'must_be_whole_number' => $payload['must_be_whole_number'] ?? 0
        ]);

    if ($response->failed()) {
        $error = $response->json();
        throw new \Exception($error['message']['exc'][1] ?? 'Failed to create UOM');
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

// For Item Group Creation
public function createItemGroup(array $payload): array
{
    // Simplified payload with ERPNext requirements
    $response = Http::withHeaders($this->authHeaders)
        ->post("{$this->baseUrl}/resource/Item Group", [
            'item_group_name' => $payload['item_group_name'],
            'is_group' => 0 // Default to not being a parent group
        ]);

    if ($response->failed()) {
        throw new \Exception("ERPNext Error: " . $response->body());
    }

    return $response->json();
}

    // For Item Groups
    public function getItemGroup(string $name): ?array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->get("{$this->baseUrl}/resource/Item Group/{$name}");
        return $response->json('data');
    }

    public function updateItemGroup(string $name, array $payload): array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->put("{$this->baseUrl}/resource/Item Group/{$name}", $payload);
        return $response->json();
    }

public function deleteItemGroup(string $name): array
{
    $response = Http::withHeaders($this->authHeaders)
        ->post("{$this->baseUrl}/method/frappe.client.delete", [
            'doctype' => 'Item Group',
            'name' => $name
        ]);

    return $response->json();
}
    // For UOMs
    public function getUOM(string $name): ?array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->get("{$this->baseUrl}/resource/UOM/{$name}");
        return $response->json('data');
    }

    public function updateUOM(string $name, array $payload): array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->put("{$this->baseUrl}/resource/UOM/{$name}", $payload);
        return $response->json();
    }

    
    public function deleteUOM(string $name): array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->post("{$this->baseUrl}/method/frappe.client.delete", [
                'doctype' => 'UOM',
                'name' => $name
            ]);

        if ($response->failed()) {
            $error = $response->json();
            if (str_contains($error['message'] ?? '', 'Cannot delete')) {
                throw new \Exception("This UOM is being used by inventory items and cannot be deleted.");
            }
            throw new \Exception("Error deleting UOM: " . ($error['message'] ?? 'Unknown error'));
        }

        return $response->json();
    }

    public function getItemsUsingUOM(string $uomName): array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->get("{$this->baseUrl}/resource/Item", [
                'fields' => '["name","item_name"]',
                'filters' => json_encode(['stock_uom' => $uomName])
            ]);

        return $response->json('data') ?? [];
    }

    public function getItemsUsingItemGroup(string $itemGroupName): array
    {
        $response = Http::withHeaders($this->authHeaders)
            ->get("{$this->baseUrl}/resource/Item", [
                'fields' => '["name","item_name"]',
                'filters' => json_encode(['item_group' => $itemGroupName])
            ]);

        return $response->json('data') ?? [];
    }

    public function migrateItemsAndDeleteUOM(string $oldUom, string $newUom): array
    {
        try {
            // Get all items using the old UOM
            $items = $this->getItemsUsingUOM($oldUom);
            
            // Update all items to use the new UOM
            foreach ($items as $item) {
                $this->updateItem($item['name'], ['stock_uom' => $newUom]);
            }
            
            // Now delete the old UOM
            return $this->deleteUOM($oldUom);
            
        } catch (\Exception $e) {
            throw new \Exception("Failed to migrate and delete UOM: " . $e->getMessage());
        }
    }

    public function migrateItemsAndDeleteItemGroup(string $oldGroup, string $newGroup): array
    {
        try {
            // Get all items using the old item group
            $items = $this->getItemsUsingItemGroup($oldGroup);
            
            // Update all items to use the new item group
            foreach ($items as $item) {
                $this->updateItem($item['name'], ['item_group' => $newGroup]);
            }
            
            // Now delete the old item group
            return $this->deleteItemGroup($oldGroup);
            
        } catch (\Exception $e) {
            throw new \Exception("Failed to migrate and delete Item Group: " . $e->getMessage());
        }
    }
            
    // Add these methods to your ErpNextService.php

    public function forceDeleteUOM(string $name): array
    {
        // First get all items using this UOM
        $items = $this->getItemsUsingUOM($name);
        
        // Delete all associated items
        foreach ($items as $item) {
            $this->deleteItem($item['name']);
        }
        
        // Now delete the UOM
        return $this->deleteUOM($name);
    }

    public function forceDeleteItemGroup(string $name): array
    {
        // First get all items using this group
        $items = $this->getItemsUsingItemGroup($name);
        
        // Delete all associated items
        foreach ($items as $item) {
            $this->deleteItem($item['name']);
        }
        
        // Now delete the item group
        return $this->deleteItemGroup($name);
    }

// In ErpNextService.php

public function renameUOM(string $oldName, string $newName): array
{
    try {
        // 1. Check if new UOM exists
        if ($this->getUOM($newName)) {
            throw new \Exception("UOM $newName already exists");
        }

        // 2. Get original UOM data
        $originalUom = $this->getUOM($oldName);
        if (!$originalUom) {
            throw new \Exception("Original UOM not found");
        }

        // 3. Create new UOM with correct field name
        $newUomData = [
            'uom_name' => $newName, // Correct field name
            'must_be_whole_number' => $originalUom['must_be_whole_number'] ?? 0
        ];
        
        $createResponse = $this->createUOM($newUomData);

        // 4. Migrate items
        $items = $this->getItemsUsingUOM($oldName);
        foreach ($items as $item) {
            $this->updateItem($item['name'], [
                'stock_uom' => $newName // Should match UOM name
            ]);
        }

        // 5. Delete old UOM
        return $this->deleteUOM($oldName);

    } catch (\Exception $e) {
        throw new \Exception("UOM rename failed: " . $e->getMessage());
    }
}
public function renameItemGroup(string $oldName, string $newName): array
{
    // 1. Check for existing group
    if ($this->getItemGroup($newName)) {
        throw new \Exception("Item Group '$newName' already exists");
    }

    // 2. Get original group data
    $originalGroup = $this->getItemGroup($oldName);
    if (!$originalGroup) {
        throw new \Exception("Item Group '$oldName' not found");
    }

    // 3. Create new group
    $this->createItemGroup([
        'item_group_name' => $newName,
        'is_group' => $originalGroup['is_group'] ?? 0,
        'parent_item_group' => $originalGroup['parent_item_group'] ?? 'All Item Groups'
    ]);

    // 4. Migrate items
    $items = $this->getItemsUsingItemGroup($oldName);
    foreach ($items as $item) {
        $this->updateItem($item['name'], ['item_group' => $newName]);
    }

    // 5. Delete old group
    return $this->deleteItemGroup($oldName);
}
}
