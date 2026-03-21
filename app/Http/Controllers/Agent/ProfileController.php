<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private const PREFECTURES = [
        '北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
        '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
        '新潟県','富山県','石川県','福井県','山梨県','長野県',
        '岐阜県','静岡県','愛知県','三重県',
        '滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県',
        '鳥取県','島根県','岡山県','広島県','山口県',
        '徳島県','香川県','愛媛県','高知県',
        '福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県',
    ];

    public function edit(): View
    {
        $agent = Auth::guard('agent')->user();

        return view('agent.profile.edit', [
            'agent'       => $agent,
            'prefectures' => self::PREFECTURES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::guard('agent')->user();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:agents,email,' . $agent->id],
            'title'       => ['nullable', 'string', 'max:255'],
            'story'       => ['nullable', 'string', 'max:3000'],
            'philosophy'  => ['nullable', 'string', 'max:3000'],
            'area'        => ['nullable', 'string', 'max:20'],
            'area_detail' => ['nullable', 'string', 'max:255'],
            'tags'        => ['nullable', 'string', 'max:500'],
            'profile_img' => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        if ($request->hasFile('profile_img')) {
            $path = $request->file('profile_img')->store('uploads', 'public');
            $validated['profile_img'] = $path;
        } else {
            unset($validated['profile_img']);
        }

        $agent->update($validated);

        return redirect()
            ->route('agent.profile.edit')
            ->with('status', 'プロフィールを更新しました。');
    }

    public function showKycForm(): View
    {
        $agent = Auth::guard('agent')->user();

        return view('agent.kyc.form', ['agent' => $agent]);
    }

    public function submitKyc(Request $request): RedirectResponse
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::guard('agent')->user();

        $request->validate([
            'affiliation_url' => ['required', 'url', 'max:500'],
        ]);

        $agent->update([
            'affiliation_url'     => $request->input('affiliation_url'),
            'verification_status' => 1, // 審査待ち
        ]);

        return redirect()
            ->route('agent.kyc.form')
            ->with('status', '本人確認URLを提出しました。審査完了までしばらくお待ちください。');
    }
}
