<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Kapal\TambahKapal;
use App\Models\Kapal;
use Yajra\DataTables\Facades\DataTables;

class KapalController extends Controller
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
        return view('kapal');
    }

    public function data() {
        $data = Kapal::get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TambahKapal $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TambahKapal $request)
    {
        try {
            Kapal::create($request->all());
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
     * @param TambahKapal $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TambahKapal $request, $id)
    {
        try {
            $kapal = Kapal::find($id);
            $kapal->nama = $request->nama;
            $kapal->kapten = $request->kapten;
            $kapal->save();
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
            $kapal = Kapal::findOrFail($id);
            $kapal->delete();
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

        $data = Kapal::select('id', 'nama')->where('nama', 'LIKE', "%$search%")->get();
        return response()->json($data, 200);
    }
}
