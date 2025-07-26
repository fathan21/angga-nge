<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Loyal;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Promo;
use App\Models\PromoDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Ulasan;
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
                $query->where('id_transaksi', 'like', '%' . $search . '%');
            });
        }
        if (@$params['start_date']) {
            $data = $data->whereDate('tanggal_transaksi', '>=', $params['start_date']);
        }
        if (@$params['end_date']) {
            $data = $data->whereDate('tanggal_transaksi', '<=', $params['end_date']);
        }
        if (@$params['id_pelanggan']) {
            $data = $data->where('id_pelanggan', $params['id_pelanggan']);
        }
        if (@$params['admin_id']) {
            $data = $data->where('admin_id', $params['admin_id']);
        }
        if (@$params['status']) {
            $data = $data->whereIn('status', explode(',', string: $params['status']));
        }
        if (@$params['status_not']) {
            $data = $data->whereNotIn('status', explode(',', $params['status_not']));
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
        $data = $data->with(['details.menu', 'admin', 'pelanggan', 'ulasan']);
        if (!$this->page) {
            $result = $data->get();
        } else {
            $result = $data->paginate($this->perPage);
            $result->getCollection()->transform(function ($product) {
                $details = $product->details->map(function ($q) {
                    $ulasan = Ulasan::where('id_menu', $q->id_menu)
                        ->where('id_transaksi', $q->id_transaksi)
                        ->first();
                    $q->ulasan = $ulasan;
                    $q->rating = $ulasan ? $ulasan->rating : null;
                    return $q;
                });
                $product->details = $details;
                return $product;
            });
        }
        // return $this->perPage;
        return $this->success(GeneralResource::collection($result)->response()->getData(true));
    }
    public function store(Request $request)
    {
        $params = $request->validate([
            'tanggal_transaksi' => 'required',
            'id_pelanggan' => 'required'
        ]);
        $params['status'] = 'dipesan';
        $params['tanggal_transaksi'] = Carbon::now();
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

        // $promo = PromoDetail::where('min_trx', '<=', $subtotal)->where('max_trx', '>=', $subtotal)->first();
        // // dd($promo);
        // if ($promo) {
        //     $subtotal_poin = $promo->poin;
        // }

        // $pelanggan = $data->pelanggan;
        // $pelanggan->total_transaksi = $pelanggan->total_transaksi + $subtotal;
        // $pelanggan->total_poin = $pelanggan->total_poin + $subtotal_poin;
        // $pelanggan->save();

        // $data->poin = $subtotal_poin;
        $data->save();

        return $this->success(new GeneralResource($data), 'success');
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
            'tanggal_transaksi' => 'required',
            'id_pelanggan' => 'required',
            'status' => 'nullable'
        ]);

        if (@$params['status']) {
            $data = $this->_model->find($id);
            if (!$data) {
                abort(404, ' data not found');
            }
            $data->update(['status' => $params['status']]);

            if (@$params['status'] == 'selesai') {

                $loyal1 = Loyal::find(1);
                $loyalKelipatan = Loyal::find(2);

                $trxC = Transaksi::where('id_pelanggan', $data->id_pelanggan)
                    ->where('status', 'selesai')
                    ->count();
                $pelanggan = Pelanggan::find($data->id_pelanggan);

                if ($trxC == 0) {
                    $data->poin = $loyal1->poin;
                    $pelanggan->total_poin = $loyal1->poin;
                } else {
                    $data->poin = $loyalKelipatan->poin;
                    $pelanggan->total_poin += $loyalKelipatan->poin;
                }
                $pelanggan->total_transaksi += $data->total;
                $pelanggan->save();
                $data->save();
            }

            return $this->show($data->id_transaksi);
        }

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
            if (isset($detail['menu'])) {
                unset($detail['menu']);
            }
            if (isset($detail['rating'])) {
                unset($detail['rating']);
            }
            $detail['id_transaksi'] = $data->id_transaksi;
            TransaksiDetail::create($detail);
            $subtotal += $detail['subtotal'];
        }

        $data->total = $subtotal;
        $data->save();

        return $this->show($data->id_transaksi);
    }

    public function updateRating(Request $request, $id)
    {
        $params = $request->validate([
            'rating' => 'required'
        ]);
        $data = $this->_model->find($id);
        $data->update($params);
        return $this->show($data->id_transaksi);
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
