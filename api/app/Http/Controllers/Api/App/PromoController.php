<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\PromoDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PromoController extends ApiController
{
    private $_model;

    public function __construct(Promo $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        if ($this->search) {
            $search = $this->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('nama_promo', 'like', '%' . $search . '%');
            });
        }
        if(@$params['status']) {
            $data = $data->where('status', $params['status']);
        }
        return $data;
    }

    public function index(Request $request)
    {
        Promo::where('status', 'aktif')
            ->whereDate('periode_akhir', '<', Carbon::now()->format('Y-m-d'))
            ->update(['status' => 'nonaktif']);
        $data = $this->_model->with(['details.menu']);
        $data = $this->scopeQuery($data, $request->all());

        if (!$this->page) {
            $result = $data->get();
        } else {
            $result = $data->paginate($this->perPage);
        }
        return $this->success(GeneralResource::collection($result)->response()->getData(true));
    }
    public function store(Request $request)
    {
        $params = $request->validate([
            'nama_promo' => 'required',
            'periode_mulai' => 'required',
            'periode_akhir' => 'required',
            'deskripsi' => 'nullable',
        ]);
        $paramsAll = $request->validate([
            'details' => 'required',
        ]);
        $paramsAll = $request->all();
        $params['status'] = 'aktif';
        $data = $this->_model->create($params);

        foreach ($paramsAll['details'] as $key => $value) {
            $value['id_promo'] = $data->id_promo;
            PromoDetail::create($value);
        }

        return $this->success(new GeneralResource($data), 'success');
    }

    public function show($id)
    {
        $data = $this->_model->with(['details.menu'])->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        return $this->success(new GeneralResource($data));
    }

    public function update(Request $request, $id)
    {
        $params = $request->validate([
            'nama_promo' => 'required',
            'periode_mulai' => 'required',
            'periode_akhir' => 'required',
            'deskripsi' => 'nullable',
        ]);
        $paramsAll = $request->validate([
            'details' => 'required',
        ]);
        
        $data = $this->_model->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        $params['status'] = 'aktif';
        $data->update($params);
        PromoDetail::where('id_promo', $data->id_promo)->delete();
        foreach ($paramsAll['details'] as $key => $value) {
            $value['id_promo'] = $data->id_promo;
            PromoDetail::create($value);
        }
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
