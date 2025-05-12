<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ErpNextService;

class InventoryController extends Controller
{
    // Declare the $erp property to hold the instance of ErpNextService
    protected $erp;

    // Inject the ErpNextService into the controller constructor
    public function __construct(ErpNextService $erp)
    {
        $this->erp = $erp;
    }

    public function index()
    {
        $items = $this->erp->listItems();
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        $uoms = $this->erp->listUOMs();
        $itemGroups = $this->erp->listItemGroups();
        return view('inventory.create', compact('uoms', 'itemGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string',
            'item_name' => 'required|string',
            'item_group' => 'required|string',
            'stock_uom' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if item already exists
        if ($this->erp->itemExists($request->item_code)) {
            return redirect()
                ->route('inventory.create')
                ->with('error', 'An item with this code already exists!')
                ->withInput();
        }

        try {
            $payload = [
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'item_group' => $request->item_group,
                'stock_uom' => $request->stock_uom,
                'description' => $request->description
            ];

            if ($request->hasFile('image')) {
                $imageResponse = $this->erp->uploadImage($request->file('image'));
                $payload['image'] = $imageResponse['message']['file_url'];
            }

            $this->erp->createItem($payload);

        } catch (\Exception $e) {
            return redirect()
                ->route('inventory.create')
                ->with('error', 'Error creating item: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Item created successfully!');
    }
    public function edit($id)
    {
        $item = $this->erp->getItem($id);
        $uoms = $this->erp->listUOMs();
        $itemGroups = $this->erp->listItemGroups();
        return view('inventory.edit', compact('item', 'uoms', 'itemGroups'));
    }

    public function update(Request $request, $id)
    {
        $payload = $request->only(['item_name', 'item_group', 'stock_uom', 'description']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/items');
            $payload['image'] = str_replace('public/', 'storage/', $imagePath);
        }

        $this->erp->updateItem($id, $payload);
        return redirect()->route('inventory.index');
    }

    public function destroy($id)
    {
        $this->erp->deleteItem($id);
        return redirect()->route('inventory.index');
    }
}
