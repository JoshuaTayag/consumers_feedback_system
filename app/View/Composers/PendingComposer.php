<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Pending;

class PendingComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $pendingCount = Pending::where('status', 0)
            ->where('recipient_user_id', auth()->id())
            ->orWhereNull('status')
            ->count();
            
        $view->with('pendingNotificationCount', $pendingCount);
    }
}