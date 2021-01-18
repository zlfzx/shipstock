<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pemakaian\TambahPemakaian;
use App\Models\Pemakaian;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PemakaianController extends Controller
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
        return view('pemakaian');
    }

    public function data() {
        $data = Pemakaian::with([
            'sparepart',
            'kapal'
        ])->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TambahPemakaian $request)
    {
        try {
            $sparepart = Sparepart::find($request->sparepart_id);
            if ($sparepart->stok > 0) {
                $sparepart->pemakaian()->create($request->all());
                $sparepart->stok -= $request->jumlah;
                $sparepart->save();
            } else {
                throw new \Exception('Stok habis!');

            }
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
        $data = Pemakaian::with([
            'sparepart',
            'kapal'
        ])->findOrFail($id);

        return response()->json([
            'status' => TRUE,
            'data' => $data
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TambahPemakaian $request, $id)
    {
        try {
            $data = Pemakaian::findOrFail($id);
            $data->sparepart_id = $request->sparepart_id;
            $data->kapal_id = $request->kapal_id;
            $data->jumlah = $request->jumlah;
            $data->tanggal_pemakaian = $request->tanggal_pemakaian;
            $data->save();
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
            Pemakaian::destroy($id);
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
}
