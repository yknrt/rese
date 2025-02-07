<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    protected $skipImgRequired = false;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function __construct()
    {
        // 現在のメソッド（アクション名）を取得
        $method = request()->route()->getActionMethod();

        // 特定のメソッド（例: 'update'）では 'area' の required を無効化
        if ($method === 'update') {
            $this->skipImgRequired = true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'area' => ['required'],
            'newArea' => ['required_if:area,other', 'string', 'max:191'],
            'genre' => ['required'],
            'newGenre' => ['required_if:genre,other', 'string', 'max:191'],
            'summary' => ['required', 'string'],
            'image' => ['image', 'max:2048'],
        ];
        if (!$this->skipImgRequired) {
            $rules['image'][] = 'required'; // 'required' を追加
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名を入力してください',
            'area.required' => '地域を選択してください',
            'newArea.required_if' => '「その他」を選択した場合は、新しい地域名を入力してください',
            'newArea.string' => '新しい地域名は文字列で入力してください',
            'newArea.max' => '新しい地域名は191文字以内で入力してください',
            'genre.required' => 'ジャンルを選択してください',
            'newGenre.required_if' => '「その他」を選択した場合は、新しいジャンル名を入力してください',
            'newGenre.string' => '新しいジャンル名は文字列で入力してください',
            'newGenre.max' => '新しいジャンル名は191文字以内で入力してください',
            'summary.required' => '店舗概要を入力してください',
            'summary.string' => '店舗概要は文字列で入力してください',
            'image.required' => 'ファイルを選択してください',
            'image.image' => '画像ファイルを選択してください',
            'image.max' => 'ファイルサイズは2MB以内にしてください'
        ];
    }



}
