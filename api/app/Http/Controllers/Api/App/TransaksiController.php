<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Promo;
use App\Models\PromoDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransaksiController extends ApiController
{
    private $_model;

    public function __construct(Transaksi $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        if ($this->search) {
            $search = $this->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%');
                $query->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
            });
        }
        if (@$params['id_pelanggan']) {
            $data = $data->where('id_pelanggan', $params['id_pelanggan']);
        }
        if (@$params['admin_id']) {
            $data = $data->where('admin_id', $params['admin_id']);
        }
        if ($this->sortField) {
            $data = $data->orderBy($this->sortField ?: 'id', $this->sortOrder ?: 'desc');
        }
        return $data;
    }

    public function index(Request $request)
    {
        $data = $this->_model;
        $data = $this->scopeQuery($data, $request->all());
        $data = $data->with(['details', 'admin', 'pelanggan']);
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
            'tanggal_taransaksi' => 'required',
            'id_pelanggan' => 'required'
        ]);
        $params['tanggal_taransaksi'] = Carbon::now();
        $paramsAll = $request->validate([
            'details' => 'required|array'
        ]);
        $params['id_admin'] = auth_user()->id;
        $params['total'] = 0;
        $params['poin'] = 0;
        $data = $this->_model->create($params);

        $subtotal = 0;
        $subtotal_poin = 0;
        foreach ($paramsAll['details'] as $detail) {
            $detail['id_transaksi'] = $data->id_transaksi;
            TransaksiDetail::create($detail);
            $subtotal += $detail['subtotal'];
        }

        $data->total = $subtotal;
        $data->save();

        $promo = PromoDetail::where('min_trx', '<=', $subtotal)->where('max_trx', '>=', $subtotal)->first();
        // dd($promo);
        if ($promo) {
            $subtotal_poin = $promo->poin;
        }

        $pelanggan = $data->pelanggan;
        $pelanggan->total_transaksi = $pelanggan->total_transaksi + $subtotal;
        $pelanggan->toal_poin = $pelanggan->toal_poin + $subtotal_poin;
        $pelanggan->save();

        return $this->success(new GeneralResource($data), 201);
    }

    public function show($id)
    {
        $data = $this->_model->with(['details', 'admin', 'pelanggan'])->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        return $this->success(new GeneralResource($data));
    }

    public function update(Request $request, $id)
    {
        $params = $request->validate([
            'tanggal_taransaksi' => 'required',
            'id_pelanggan' => 'required'
        ]);

        $paramsAll = $request->validate([
            'details' => 'required|array'
        ]);
        $params['id_admin'] = auth_user()->id;
        $params['total'] = 0;
        $params['poin'] = 0;

        $data = $this->_model->find($id);
        if (!$data) {
            abort(404, ' data not found');
        }
        $data->update($params);

        $data->details()->delete();

        $subtotal = 0;
        foreach ($paramsAll['details'] as $detail) {
            $detail['id_transaksi'] = $data->id_transaksi;
            TransaksiDetail::create($detail);
            $subtotal += $detail['subtotal'];
        }

        $data->total = $subtotal;
        $data->save();

        return $this->show($data->id);
    }

    public function destroy($id)
    {
        try {
            $data = $this->_model->find($id);
            $data->details()->delete();
            $data->delete();
            return $this->success('sucess', 204);
        } catch (\Throwable $th) {
            return $this->error(400, 'error :' . $th->getMessage());
        }
    }
}
