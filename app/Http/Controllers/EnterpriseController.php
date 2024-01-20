<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnterpriseController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $enterprises = Enterprise::all();

        return response()->json(['data' => $enterprises], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'description' => 'required',
                'image' => 'required|array',
                'owner' => 'required',
            ]);

            $enterprise = Enterprise::create($request->all());

            return response()->json(['data' => $enterprise], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }

    // public function store(Request $request) {
    //     try {
    //         $request->validate([
    //             'name' => 'required',
    //             'address' => 'required',
    //             'description' => 'required',
    //             'image' => 'required',
    //             'owner' => 'required',
    //         ]);

    //         $enterprise = Enterprise::create($request->all());

    //         return response()->json(['data' => $enterprise], Response::HTTP_CREATED);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(Enterprise $enterprise) {
        return response()->json(['data' => $enterprise], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enterprise $enterprise) {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'description' => 'required',
            'image' => 'required',
            'owner' => 'required',
            'products' => 'required',
        ]);

        $enterprise->update($request->all());

        return response()->json(['data' => $enterprise], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprise $enterprise) {
        $enterprise->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
