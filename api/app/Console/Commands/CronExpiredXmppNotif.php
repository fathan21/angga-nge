<?php

namespace App\Console\Commands;

use App\Classes\TeleBot;
use App\Models\XmppAccount;
use Illuminate\Console\Command;

class CronExpiredXmppNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cron-expired-xmpp-notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $accounts = XmppAccount::where('status', '0')->get();
        foreach ($accounts as $account) {
            try {
                (new TeleBot)->accountXmppNotifExpired($account);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $account->forceDelete();
        }
    }
}
