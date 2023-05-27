<?php

namespace App\Http\Controllers\Api;

use App\Models\Donatur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Profile',
            'data'    => auth()->guard('api')->user(),
        ], 200);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //get data profile
        $donor = Donor::whereId(auth()->guard('api')->user()->id)->first();

        //update with upload avatar
        if($request->file('avatar')) {

            //hapus image lama
            Storage::disk('local')->delete('public/donors/'.basename($donor->image));

            //upload image baru
            $image = $request->file('avatar');
            $image->storeAs('public/donors', $image->hashName());

            $donor->update([
                'name'      => $request->name,
                'avatar'    => $image->hashName()
            ]);

        }

        //update without avatar
        $donor->update([
            'name'      => $request->name,
        ]);

        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Profile Berhasil Diupdate!',
            'data'    => $donor,
        ], 201);

    }

    /**
     * updatePassword
     *
     * @param  mixed $request
     * @return void
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $donor = Donor::whereId(auth()->guard('api')->user()->id)->first();
        $donor->update([
            'password'  => Hash::make($request->password)
        ]);


        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'Password Berhasil Diupdate!',
            'data'    => $donor,
        ], 201);
    }
}
