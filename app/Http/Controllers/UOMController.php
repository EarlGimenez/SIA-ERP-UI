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
}
