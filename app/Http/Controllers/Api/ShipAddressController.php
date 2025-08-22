<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ship_address;
use Illuminate\Http\Request;

class ShipAddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipient_name' => 'required|string|max:255',
            'ship_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            // 'is_default' => 'boolean',
        ]);

        $shipAddress = Ship_address::create($request->all());

        return response()->json($shipAddress, 201);
    }
}
