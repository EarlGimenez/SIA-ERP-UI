<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ErpNextService;

class UOMController extends Controller
{
    protected $erp;

    public function __construct(ErpNextService $erp)
    {
        $this->erp = $erp;
    }

    public function index()
    {
        $uoms = $this->erp->listUOMs();
        return view('uoms.index', compact('uoms'));
    }

    public function create()
    {
        return view('uoms.create');
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'uom_name'     => 'required|string',
            'must_be_whole_number' => 'nullable|boolean',
        ]);
        $this->erp->createUOM($data);
        return redirect()->route('uoms.index');
    }
    
    public function edit($name)
    {
        try {
            $uom = $this->erp->getUOM($name);
            $uoms = $this->erp->listUOMs();
            
            // Check if UOM is in use
            $itemsUsingUom = $this->erp->getItemsUsingUOM($name);
            $isProtected = !empty($itemsUsingUom);
            
            return view('uoms.edit', compact('uom', 'uoms', 'isProtected'));
        } catch (\Exception $e) {
            return redirect()->route('uoms.index')->with('error', $e->getMessage());
        }
    }

// In UOMController.php

public function update(Request $request, $name)
{
    try {
        $this->erp->renameUOM(
            $request->original_name,
            $request->uom_name
        );

        return redirect()->route('uoms.index')
            ->with('success', 'UOM renamed successfully');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}
    public function destroy($name)
    {
        try {
            $response = $this->erp->deleteUOM($name);
            return redirect()->route('uoms.index')
                ->with('success', $response['message'] ?? 'UOM deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('uoms.index')
                ->with('error', $e->getMessage())
                ->with('protected_uom', $name); // Pass the protected UOM name
        }
    }

public function forceDestroy($name)
{
    try {
        $response = $this->erp->forceDeleteUOM($name);
        return redirect()->route('uoms.index')
            ->with('success', 'UOM and all associated items deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('uoms.index')
            ->with('error', 'Force delete failed: ' . $e->getMessage());
    }
}



}
