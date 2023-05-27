<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;

class DonorController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $donors = Donor::latest()->when(request()->q, function($donors) {
            $donors = $donors->where('name', 'like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.donor.index', compact('donors'));
    }
}