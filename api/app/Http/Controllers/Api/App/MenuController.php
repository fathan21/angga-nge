<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MenuController extends ApiController
{
    private $_model;

    public function __construct(Menu $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        if ($this->search) {
            $search = $this->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('nama_menu', 'like', '%' . $search . '%');
            });
        }
        if(@$params['status']) {
            $data = $data->where('status', $params['status']);
        }
        if(@$params['kategori_menu']) {
            $data = $data->where('kategori_menu', $params['kategori_menu']);
        }
        if ($this->sortField) {
            $data = $data->orderBy($this->sortField ?: 'nama_menu', $this->sortOrder ?: 'asc');
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
            'nama_menu' => 'required',
            'harga' => 'required',
            'status' => 'required',
            'kategori_menu' => 'required',
        ]);
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
            'nama_menu' => 'required',
            'harga' => 'required',
            'status' => 'required',
            'kategori_menu' => 'required',
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
