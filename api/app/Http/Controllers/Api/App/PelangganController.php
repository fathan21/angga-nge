<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends ApiController
{
    private $_model;

    public function __construct(Pelanggan $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        if ($this->search) {
            $search = $this->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
                $query->where('no_hp', 'like', '%' . $search . '%');
                $query->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if(@$params['tanggal_daftar']) {
            $data = $data->where('tanggal_daftar', $params['tanggal_daftar']);
        }
        if ($this->sortField) {
            $data = $data->orderBy($this->sortField ?: 'nama', $this->sortOrder ?: 'asc');
        }
        return $data;
    }

    public function index(Request $request)
    {
        $data = $this->_model;
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
            'nama' => 'required',
            'no_hp' => 'required',
            'email' => 'nullable'
        ]);
        $params['tanggal_daftar'] = Carbon::now()->format('Y-m-d');
        $data = $this->_model->create($params);
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
        $params = $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'email' => 'nullable'
        ]);
        
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
