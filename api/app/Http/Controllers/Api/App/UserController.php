<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\GeneralResource;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    private $_model;

    public function __construct(User $md, Request $request)
    {
        $this->_model = $md;

        $this->parseRequest($request);
    }

    public function scopeQuery($data, $params = [])
    {
        if ($this->search) {
            $search = $this->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%');
            });
        }
        if ($this->sortField) {
            $data = $data->orderBy($this->sortField ?: 'username', $this->sortOrder ?: 'asc');
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
            'username' => 'required',
            'password' => [
                'required',
                'min:6',
            ],
        ]);
        $params['password'] =$params['password'];
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
            'username' => 'nullable|unique:admin,username,' . $id,
            'password' => [
                'nullable',
                'min:6',
            ]
        ]);
        if (@$params['password']) {
            $params['password'] = $params['password'];
        }else {
            unset($params['password']);
        }
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

    function info() {
        $user = $this->user();
        return $this->success([
            'username'=>$user->username,
            'id'=>$user->id,
            'role'=>$user->role,
        ]);
    }
    
    public function changePassword(Request $request)
    {
        $params = $request->validate([
            'password_old' => 'required',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);
        $data = auth_user();
        if($data instanceof Pelanggan) {
            $data = Pelanggan::find($data->id_pelanggan);
        }else{
            $data = User::find($data->id);
        }
        $data->password = $params['password'];
        $data->save();
        return $this->success(new GeneralResource($data), 'success');
    }
    
    public function dashboard(Request $request)
    {
        $now= Carbon::now()->format('Y-m-d');
        $data = [
            'total_pelanggan'=>Pelanggan::count(),
            'total_trx_hari_ini'=>Transaksi::where('status','Lunas')->whereDate('tanggal_transaksi',$now)->count(),
            'total_pemasukan'=>Transaksi::where('status','Lunas')->sum('total'),
        ];
        return $this->success($data);
    }

    public function notif()  {
        
        return $this->success(
            [
                'notif_pesanan'=>Transaksi::whereNotIn('status', ['Lunas','Dibatalkan'])->count()
            ]
        );
    }
}
