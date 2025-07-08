<?php

namespace App\Http\Controllers\Api\Open;

use App\Classes\ApiResponseClass;
use App\Classes\TeleBot;
use App\Http\Controllers\Api\ApiController;
use App\Models\GeneralSetting;
use App\Models\XmppAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XmppRegistController extends ApiController
{

    public function regist(Request $request)
    {
        $params = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'host' => 'required',
            'telegram_id' => 'required',
            'phone' => 'required',
        ]);

        $params['password'] = '';
        $checkAxist = XmppAccount::where('username', $params['username'])->where('host', $params['host'])->first();
        if ($checkAxist) {
            abort(422, 'username already used');
        }

        $params['status'] = 0;
        XmppAccount::create($params);
        return $this->success(['message' => 'success create']);
    }
    public function resetPassword(Request $request)
    {
        $params = $request->validate([
            'username' => 'required',
            'host' => 'required',
            'phone' => 'required'
        ]);

        $checkAxist = XmppAccount::where('username', $params['username'])
            ->where('host', $params['host'])
            ->where('phone', $params['phone'])
            ->first();
        if (!$checkAxist) {
            abort(422, 'xmpp account not found');
        }

        if ($checkAxist->status != 1) {
            abort(422, 'xmpp account not active');
        }

        $newPwd = random_string(10);

        DB::beginTransaction();
        try {
            $checkAxist->password = $newPwd;
            $checkAxist->save();
            (new TeleBot)->accountXmppNotifResetPassword($checkAxist);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422, $th->getMessage());
        }
        $msg = 'success reset password, new password send to telegram';
        return $this->success(['message' => $msg], $msg);
    }

    public function config()
    {
        $host = GeneralSetting::getByKey('xmpp_host') ?: '';
        $bank = GeneralSetting::getByKey('reg_xmpp_bank');
        $bankDetail = [
            'reg_xmpp_bank_name' => "",
            'reg_xmpp_bank_no_rek' => "",
            'reg_xmpp_bank_nm_rek' => "",
        ];
        if ($bank) {
            $bank = explode('/', $bank);
            $bankDetail = [
                'reg_xmpp_bank_name' => @$bank[0],
                'reg_xmpp_bank_no_rek' => @$bank[1],
                'reg_xmpp_bank_nm_rek' => @$bank[2],
            ];
        }
        $data = [
            'reg_xmpp_fee' => GeneralSetting::getByKey('reg_xmpp_fee') ?: '10.000',
            'xmpp_host' => explode(',', $host),
            'reg_xmpp_confirm' => GeneralSetting::getByKey('reg_xmpp_confirm') ?: '',
            ...$bankDetail
        ];

        return $this->success($data);
    }
}
