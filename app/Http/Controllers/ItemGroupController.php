<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ErpNextService;

class ItemGroupController extends Controller
{
    protected $erp;

    public function __construct(ErpNextService $erp)
    {
        $this->erp = $erp;
    }

    
    public function index(ErpNextService $erp)
    {
        $itemGroups = $erp->listItemGroups();
        return view('item-groups.index', compact('itemGroups'));
    }

    public function create()
    {
        // We need UOM list so the form can pick Default UOM
        $uoms = $this->erp->listUOMs();
        return view('item-groups.create', compact('uoms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_group_name' => 'required|string'
        ]);

        // Create the item group without the parent_item_group
        try {
            $this->erp->createItemGroup([
                'item_group_name' => $request->item_group_name, // Only pass the necessary field
            ]);
        } catch (\Exception $e) {
            // Handle exception if there's an error creating the item group
            return redirect()->route('item-groups.create')->with('error', 'Error creating Item Group: ' . $e->getMessage());
        }

        return redirect()->route('item-groups.index');
    }


}
