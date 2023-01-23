<?php

namespace App\Http\Controllers;

use App\Http\Requests\NormalizeAddressRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function normalize(NormalizeAddressRequest $request)
    {
        $response = app('usps')->validate([
            'Address'   => request('address'),
            'Zip'       => request('zip'),
            'Apartment' => request('apartment'),
            'City'      => request('city'),
            'State'     => request('state'),
        ]);

        if (array_key_exists('error', $response)) {
            return response()->json([
                'message' => $response['error'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response);
    }

    public function store(StoreAddressRequest $request)
    {
        $address = Address::create($request->validated());

        return response()->json($address)->setStatusCode(Response::HTTP_CREATED);
    }
}
