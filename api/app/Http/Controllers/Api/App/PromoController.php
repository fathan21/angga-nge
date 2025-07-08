<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\PromoDetail;
use App\Models\User;
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
    
    public function store(Request $request)
    {
        $params = $request->validate([
            'nama_promo' => 'required',
            'details' => 'required|array',
        ]);
        $data = $this->_model->updateOrCreate(['id_promo'=>1],['nama_promo'=>$params['nama_promo']]);

        $data->details()->delete();
        foreach ($params['details'] as $key => $value) {
            $value['id_promo'] = 1;
            PromoDetail::create($value);
        }
        return $this->success(new GeneralResource($data), 201);
    }

    public function show()
    {
        $data = $this->_model->with(['details'])->find(1);
        if (!$data) {
            return $this->success([
                    'nama_promo'=>'',
                    'details'=>[]
            ]);
        }
        return $this->success(new GeneralResource($data));
    }
    
}
