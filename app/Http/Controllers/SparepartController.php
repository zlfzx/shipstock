<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sparepart\TambahSparepart;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SparepartController extends Controller
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
        return view('sparepart');
    }

    public function data() {
        $data = Sparepart::with('warehouse:id,nama')->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TambahSparepart $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TambahSparepart $request)
    {
        try {
            Sparepart::create($request->all());
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = Sparepart::findOrFail($id);

        return response()->json([
            'status' => TRUE,
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TambahSparepart $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TambahSparepart $request, $id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->kode = $request->kode;
            $sparepart->nama = $request->nama;
            $sparepart->stok = $request->stok;
            $sparepart->warehouse_id = $request->warehouse_id;
            $sparepart->save();
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
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->delete();
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

    public function select(Request $request) {
        $search = $request->get('q');

        $data = Sparepart::select('id', 'nama')->where('nama', 'LIKE', "%$search%")->get();
        return response()->json($data, 200);
    }
}
