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
            'item_code' => 'required|string',  // Removed 'unique:item'
            'item_name' => 'required|string',
            'item_group' => 'required|string',
            'stock_uom' => 'required|string'
        ]);

        try {
            // Create the item with the necessary fields
            $this->erp->createItem([
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'item_group' => $request->item_group, // Item Group needs to exist
                'stock_uom' => $request->stock_uom  // Stock UOM needs to exist
            ]);
        } catch (\Exception $e) {
            // Handle the exception and return error message
            return redirect()->route('inventory.create')->with('error', 'Error creating item: ' . $e->getMessage());
        }

        return redirect()->route('inventory.index');
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
