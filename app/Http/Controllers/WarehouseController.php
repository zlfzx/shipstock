<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\TambahWareHouse;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct() {
        $this->middleware('check.statusUser', [
            'except' => ['index', 'data']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouse');
    }

    public function data() {
        $data = Warehouse::get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TambahWareHouse $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TambahWareHouse $request)
    {
        try {
            Warehouse::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'messages' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => TRUE
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TambahWareHouse $request, $id)
    {
        try {
            $warehouse = Warehouse::find($id);
            $warehouse->nama = $request->nama;
            $warehouse->alamat = $request->alamat;
            $warehouse->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => TRUE
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => TRUE
        ], 200);
    }

        // select2
    public function select(Request $request) {
        $search = $request->get('q');

        $data = Warehouse::select('id', 'nama')->where('nama', 'LIKE', "%$search%")->get();
        return response()->json($data, 200);
    }
}
