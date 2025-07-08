<?php

namespace App\Classes;

use App\Models\GeneralSetting;
use App\Models\XmppAccount;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request as TelegramBotRequest;

class TeleBot
{
    public function sendNotif(array $user_ids, String $msg)
    {
        $username = GeneralSetting::getByKey('tele_bot_username');
        $token = GeneralSetting::getByKey('tele_bot_key');

        if (!$username && !$token) {
            return;
        }
        $telegram = new Telegram($token, $username);
        $telegram->handleGetUpdates();

        foreach ($user_ids as $key => $user_id) {
            $dt = [
                'chat_id' => @$user_id,
                'text' => $msg,
                'reply_markup' => Keyboard::remove(['selective' => true]),
            ];

            TelegramBotRequest::sendMessage($dt);
        }
    }

    public function accountXmppNotifApprove(XmppAccount $xmppAccount) {
        $xmppAccount->refresh();
        $msg = "";
        $msg .="Akun R1 Jabber anda telah aktif  \n";
        $msg .= "Username : ".$xmppAccount->username."@".$xmppAccount->host."\n";
        $msg .= "Password : ".$xmppAccount->password." \n";
        $this->sendNotif([$xmppAccount->telegram_id], $msg);
    }

    public function accountXmppNotifResetPassword(XmppAccount $xmppAccount) {
        $xmppAccount->refresh();
        $msg = "";
        $msg .="Akun R1 Jabber anda telah reset password baru \n";
        $msg .= "Username : ".$xmppAccount->username."@".$xmppAccount->host."\n";
        $msg .= "Password Baru : ".$xmppAccount->password." \n";
        $this->sendNotif([$xmppAccount->telegram_id], $msg);
    }

    public function accountXmppNotifExpired(XmppAccount $xmppAccount) {
        $xmppAccount->refresh();
        $msg = "";
        $msg .="Pendaftaran R1 Jabber anda gagal \n";
        $msg .= "Username : ".$xmppAccount->username."@".$xmppAccount->host."\n";
        $this->sendNotif([$xmppAccount->telegram_id], $msg);
    }
    
    
}
