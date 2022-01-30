<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function get()
    {
        $foodList = Food::orderByDesc('foodName')->get();
        $data = [
            'success' => 1,
            'message' => 'Semua data berhasil ditampilkan',
            'foods' => $foodList,
        ];
        return $data;
    }

    public function create(Request $request)
    {
        $validasi = Validator::make($request->all(), []);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $foodItem = Food::create($request->all());

        if ($foodItem) {
            return response()->json([
                'success' => 1,
                'message' => 'Data baru berhasil dibuat',
                'food' => $foodItem,
            ]);
        }
        return $this->error('Gagal membuat data');
    }

    public function update(Request $request, $id)
    {
        $foodItem = Food::where('id', $id)->first();
        if ($foodItem) {
            $foodItem->update($request->all());
        }

        if ($foodItem) {
            return response()->json([
                'success' => 1,
                'message' => 'Data berhasil diubah',
                'food' => $foodItem,
            ]);
        }
        return $this->error('Gagal merubah data');
    }

    public function delete($id)
    {
        $foodItem = Food::where('id', $id);

        if ($foodItem) {
            $foodItem->delete();
            $foodList = Food::all();
            return response()->json([
                'success' => 1,
                'message' => 'Data berhasil dihapus',
                'foods' => $foodList,
            ]);
        }
        return $this->error('Data tidak ditemukan');
    }

    public function error($message)
    {
        return response()->json([
            'success' => 0,
            'message' => $message,
        ]);
    }
}
