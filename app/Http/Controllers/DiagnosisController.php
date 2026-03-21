<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    private const QUESTIONS = [
        [
            'text' => '保険の提案を受ける際、最も重視することは？',
            'options' => [
                100 => 'データや数値を自分で確認して合理的に判断したい',
                67  => '専門家の説明を論理的に聞いて納得してから決めたい',
                33  => '感情とデータのバランスを見ながら判断したい',
                0   => '親身に相談に乗ってくれる担当者の言葉を信じたい',
            ],
        ],
        [
            'text' => '担当者に求めるスタイルは？',
            'options' => [
                100 => '数字や根拠を示しながら論理的に説明してほしい',
                67  => '長所・短所を整理してフラットに伝えてほしい',
                33  => '私の状況に合わせて丁寧に説明してほしい',
                0   => 'とにかく親身に話を聞いて寄り添ってほしい',
            ],
        ],
        [
            'text' => '保険に関する情報収集の方法は？',
            'options' => [
                100 => '比較サイトやスペック表を自分でじっくり調べる',
                67  => 'クチコミと数値の両方を確認して判断する',
                33  => '知人の体験談や口コミを参考にする',
                0   => '専門家にすべて任せたい',
            ],
        ],
        [
            'text' => '相談時の雰囲気として好むのは？',
            'options' => [
                100 => '短時間で要点をまとめてスパッと提案してほしい',
                67  => '必要な情報を整理しながら効率よく進めたい',
                33  => 'ゆっくり話を聞いてもらいながら進めたい',
                0   => '気軽に何度でも話を聞いてもらいたい',
            ],
        ],
        [
            'text' => '保険を選ぶときの最終的な決め手は？',
            'options' => [
                100 => '保障内容と保険料の費用対効果（コスパ）',
                67  => '担当者の信頼性とコストのバランス',
                33  => '担当者との相性・話しやすさ',
                0   => '安心感・直感・信頼できる人の推薦',
            ],
        ],
    ];

    private const TYPE_INFO = [
        'logic_seeker'   => ['label' => '論理・データ重視タイプ', 'emoji' => '📊', 'color' => '#004e92', 'desc' => '数値や根拠を大切にする、分析的なタイプです。データドリブンなアドバイスを好み、論理的に納得してから行動します。'],
        'empathy_seeker' => ['label' => 'バランス重視タイプ',     'emoji' => '🤝', 'color' => '#2e7d32', 'desc' => '感情と論理のバランスを保つ、柔軟なタイプです。信頼できる関係の中で、合理的かつ感情的にも満足できる選択をします。'],
        'support_seeker' => ['label' => '感情・寄り添い重視タイプ','emoji' => '💛', 'color' => '#e65100', 'desc' => '安心感と共感を大切にする、感性豊かなタイプです。親身になって話を聞いてくれる担当者との長期的な関係を重視します。'],
    ];

    public function index(): View
    {
        return view('diagnosis', [
            'questions' => self::QUESTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'answers'   => ['required', 'array', 'size:' . count(self::QUESTIONS)],
            'answers.*' => ['required', 'integer', 'in:0,33,67,100'],
        ]);

        $scores = array_map('intval', $request->input('answers'));
        $score  = (int) round(array_sum($scores) / count($scores));

        $type = match(true) {
            $score >= 61 => 'logic_seeker',
            $score >= 41 => 'empathy_seeker',
            default      => 'support_seeker',
        };

        // ログイン中UserはDBにも保存
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->user()->update([
                'diagnosis_type'  => $type,
                'diagnosis_score' => $score,
            ]);
        }

        session([
            'diagnosis_type'  => $type,
            'diagnosis_score' => $score,
        ]);

        return redirect()->route('diagnosis.result');
    }

    public function result(): View
    {
        $type  = session('diagnosis_type');
        $score = session('diagnosis_score');

        if (!$type) {
            return view('diagnosis', ['questions' => self::QUESTIONS]);
        }

        return view('diagnosis_result', [
            'typeInfo' => self::TYPE_INFO[$type] ?? self::TYPE_INFO['empathy_seeker'],
            'type'     => $type,
            'score'    => $score,
        ]);
    }
}
