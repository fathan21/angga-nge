<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\Ulasan;
use App\Models\UlasanPertanyaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UlasanController extends ApiController
{
    private $_model;

    public function __construct(Ulasan $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        
        if(@$params['tipe']) {
            $data = $data->where('tipe', $params['tipe']);
        }
        if(@$params['id_menu']) {
            $data = $data->where('id_menu', $params['id_menu']);
        }

        if(@$params['id_pelanggan']) {
            $data = $data->whereHas('transaksi.pelanggan', function($query) use ($params) {
                $query->where('id_pelanggan', $params['id_pelanggan']);
            });
        }
        if ($this->sortField) {
            $data = $data->orderBy($this->sortField ?: 'nama_menu', $this->sortOrder ?: 'asc');
        }
        return $data;
    }

    public function index(Request $request)
    {
        $data = $this->_model->with(['transaksi.pelanggan','menu']);
        $params = $request->all();
        $data = $this->scopeQuery($data, $params);

        if (!$this->page) {
            $result = $data->get();
        } else {
            if(@$params['tipe'] == 'Makanan' && !@$params['detail']) {
                $data = $data->selectRaw('
                    id_transaksi,
                    id_menu,
                    max(tanggal) as tanggal,
                    tipe,
                    sum(rating) / count(id) as rating
                ');
                $data = $data->groupBy('id_menu', 'tipe');
            } 
            $result = $data->paginate($this->perPage);
        }
        return $this->success(GeneralResource::collection($result)->response()->getData(true));
    }
    public function store(Request $request)
    {
        $params = $request->all();
        if(@$params['type'] == 'menu') {
            // 
            foreach ($params['details'] as $key => $value) {
                $data = $this->_model->updateOrCreate(['id_transaksi'=>$value['id_transaksi'],'id_menu'=>$value['id_menu']], $value);
            }
        }else{
            $data = $this->_model->updateOrCreate(['id_transaksi'=>$params['id_transaksi'],'id_menu'=>NULL], $params);

        }
        return $this->success(new GeneralResource($data), 'success');
    }

    public function show($id)
    {
        $data = $this->_model->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        return $this->success(new GeneralResource($data));
    }

    public function update(Request $request, $id)
    {
        $params = $request->all();
        
        $data = $this->_model->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        $data->update($params);
        return $this->success(new GeneralResource($data), 'success');
    }

    public function destroy($id)
    {
        try {
            $data = $this->_model->find($id);
            $data->delete();
            return $this->success('sucess', 204);
        } catch (\Throwable $th) {
            return $this->error(400,'error :' . $th->getMessage());
        }
    }
    
}
