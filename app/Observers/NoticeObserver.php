<?php

namespace App\Observers;

use App\Jobs\NoticeJob;
use App\Models\Notice;
use Illuminate\Support\Facades\Log;

class NoticeObserver
{
    // 添加完成后，执行方法  create   creating
    public function created(Notice $notice)
    {
//        Log::info('=======================你好世界=======================');
        // 发布一个任务，生成一个任务
        dispatch(new NoticeJob());
    }

    // 修改完成后，执行此方法  update   updating
    public function updated(Notice $notice)
    {
        //
    }

}
