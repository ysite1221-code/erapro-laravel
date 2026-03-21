<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $agent = Auth::guard('agent')->user();

        // このAgentへ問い合わせたUserを重複排除・最新問い合わせ順で取得
        $customers = User::whereIn('id', function ($query) use ($agent) {
                $query->select('user_id')
                      ->from('inquiries')
                      ->where('agent_id', $agent->id);
            })
            ->with(['inquiries' => function ($q) use ($agent) {
                $q->where('agent_id', $agent->id)->latest()->limit(1);
            }])
            ->withMax(['inquiries as last_inquiry_at' => function ($q) use ($agent) {
                $q->where('agent_id', $agent->id);
            }], 'created_at')
            ->orderByDesc('last_inquiry_at')
            ->paginate(30);

        return view('agent.customers.index', compact('customers', 'agent'));
    }
}
