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
        'item_group_name' => 'required|string|min:2'
    ]);

    try {
        $this->erp->createItemGroup([
            'item_group_name' => $request->item_group_name
        ]);
        return redirect()->route('item-groups.index');

    } catch (\Exception $e) {
        return redirect()->route('item-groups.create')
            ->with('error', $e->getMessage())
            ->withInput();
    }
}

    public function edit($name)
    {
        try {
            $group = $this->erp->getItemGroup($name);
            $itemGroups = $this->erp->listItemGroups();
            
            // Check if item group is in use
            $itemsUsingGroup = $this->erp->getItemsUsingItemGroup($name);
            $isProtected = !empty($itemsUsingGroup);
            
            return view('item-groups.edit', compact('group', 'itemGroups', 'isProtected'));
        } catch (\Exception $e) {
            return redirect()->route('item-groups.index')->with('error', $e->getMessage());
        }
    }



public function update(Request $request, $name)
{
    try {
        $this->erp->renameItemGroup(
            $request->original_name,
            $request->item_group_name
        );
        return redirect()->route('item-groups.index')->with('success', 'Item Group updated');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage())->withInput();
    }
}
    public function destroy($name)
    {
        try {
            $response = $this->erp->deleteItemGroup($name);
            return redirect()->route('item-groups.index')
                ->with('success', $response['message'] ?? 'Item Group deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('item-groups.index')
                ->with('error', $e->getMessage())
                ->with('protected_group', $name); // Pass the protected group name
        }
    }
public function forceDestroy($name)
{
    try {
        $response = $this->erp->forceDeleteItemGroup($name);
        return redirect()->route('item-groups.index')
            ->with('success', 'Item Group and all associated items deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('item-groups.index')
            ->with('error', 'Force delete failed: ' . $e->getMessage());
    }
}



}
