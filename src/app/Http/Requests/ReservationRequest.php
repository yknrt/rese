<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'], // 日付のバリデーション
            'time' => ['required', 'date_format:H:i'], // 時間のバリデーション
            'number' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has(['date', 'time'])) {
            $this->merge([
                'datetime' => "{$this->date} {$this->time}", // 日付と時間を結合
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->has('datetime')) {
                $datetime = $this->input('datetime');

                // 日時が現在以降かどうかをチェック
                if (!strtotime($datetime) || strtotime($datetime) < strtotime("+1 hour")) {
                    $validator->errors()->add('datetime', '予約日時は現在時刻から1時間以上後である必要があります。');
                }

                // 重複チェック
                if ($this->has('edit')) {
                    $isOverlap = Reservation::where('id', '<>', $this->id)->where('date', $this->date)->where('time', $this->time)->exists();
                }
                else {
                    $isOverlap = Reservation::where('date', $this->date)->where('time', $this->time)->exists();
                }

                if ($isOverlap) {
                    $validator->errors()->add('datetime', '指定された時間と重複する予約があります。');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'date.required' => '日付を選択してください。',
            'date.date' => '有効な日付を入力してください。',
            'time.required' => '時間を選択してください。',
            'time.date_format' => '時間はHH:MM形式で入力してください。',
            'number.required' => '人数を選択してください。'
        ];
    }
}
