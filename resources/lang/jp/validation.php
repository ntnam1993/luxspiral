<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | such as the size rules. Feel free to tweak each of these messages.
      |
     */

    'accepted' => ':attributeを承認してください。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeには、今や以降の日付を指定してください。',
    'alpha' => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash' => ":attributeには、英数字('A-Z','a-z','0-9')とハイフンと下線('-','_')が使用できます。",
    'alpha_num' => ":attributeには、英数字('A-Z','a-z','0-9')が使用できます。",
    'array' => ':attributeには、配列を指定してください。',
    'before' => ':attributeには、今や以前の日付を指定してください。',
    'between' => [
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'file' => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string' => ':attributeは、:min文字から:max文字にしてください。',
        'array' => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean' => ":attributeには、'true'か'false'を指定してください。",
    'confirmed' => ':attributeと:attribute確認が一致しません。',
    'date' => ':attributeは、正しい日付ではありません。',
    'date_format' => ":attributeの形式は、':format'と合いません。",
    'different' => ':attributeと:otherには、異なるものを指定してください。',
    'digits' => ':attributeは、:digits桁にしてください。',
    'digits_between' => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attributeは、有効なメールアドレス形式で指定してください。',
    'exists' => '選択された:attributeは、有効ではありません。',
    'file' => 'The :attribute must be a file.',
    'filled' => ':attributeは必須です。',
    'image' => ':attributeには、画像を指定してください。',
    'in' => '選択された:attributeは、有効ではありません。',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attributeには、整数を指定してください。',
    'ip' => ':attributeには、有効なIPアドレスを指定してください。',
    'json' => ':attributeには、有効なJSON文字列を指定してください。',
    'max' => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file' => ':attributeには、:max KB以下のファイルを指定してください。',
        'string' => ':attributeは、:max文字以下にしてください。',
        'array' => ':attributeの項目は、:max個以下にしてください。',
    ],
    'mimes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min' => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file' => ':attributeには、:min KB以上のファイルを指定してください。',
        'string' => ':attributeは、:min文字以上にしてください。',
        'array' => ':attributeの項目は、:max個以上にしてください。',
    ],
    'not_in' => '選択された:attributeは、有効ではありません。',
    'numeric' => ':attributeには、数字を指定してください。',
    'present' => 'The :attribute field must be present.',
    'regex' => ':attributeには、有効な正規表現を指定してください。',
    'required' => ':attributeは、必ず指定してください。',
    'required_if' => ':otherが:valueの場合、:attributeを指定してください。',
    'required_unless' => ':otherが:value以外の場合、:attributeを指定してください。',
    'required_with' => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all' => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without' => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same' => ':attributeと:otherが一致しません。',
    'size' => [
        'numeric' => ':attributeには、:sizeを指定してください。',
        'file' => ':attributeには、:size KBのファイルを指定してください。',
        'string' => ':attributeは、:size文字にしてください。',
        'array' => ':attributeの項目は、:size個にしてください。',
    ],
    'string' => ':attributeには、文字を指定してください。',
    'timezone' => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique' => '指定の:attributeは既に使用されています。',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => ':attributeは、有効なURL形式で指定してください。',

    /*
      |--------------------------------------------------------------------------
      | Custom Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | Here you may specify custom validation messages for attributes using the
      | convention "attribute.rule" to name the lines. This makes it quick to
      | specify a specific custom language line for a given attribute rule.
      |
     */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'phone_number' => [
            'required' => '電話番号を入力してください。',
            'phone'    => '電話番号に誤りがあります。',
            'unique'   => '入力された電話番号は既に登録されています。',
            'min'      => '電話番号は半角英数の10桁です。'
        ],
        'title'=> [
            'required' => 'タイトルが登録されていません。',
            'max'      => '255文以内入力してください。'
        ],
        'filemsgsend'  => [
            'required' => '配信メッセージが登録されていません。'
        ],
        'filemsgnoans' => [
            'required' => '非応答メッセージが登録されていません。'
        ],
        'filemsgans'   => [
            'required' => '応答メッセージが登録されていません。'
        ],
        'date' => [
            'required' => '配信日付を指定してください。'
        ],
        'time' => [
            'required' => '配信時間を指定ください。'
        ],
        'datetime' => [
            'required' => '配信日時を指定ください。'
        ],
        'email' => [
            'required' => '入力したメールアドレスが正しくないです。'
        ],
        'description'  => [
            'required' => 'お知らせ本文が登録されていません。',
            'max' => '3000字以内で入力してください。'
        ],
        'schedule' => [
            'required' => '配信時間が登録されていません。',
            'after' => '配信日時が過去日です。'
        ],
        'id' => [
            'required' => '配信着手しましたので編集できません。'
        ]

    ],
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
     */
    'attributes' => [
        'name' => '氏名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'role_id' => '役割設定',
        'staff_name' => '事業部担当者名',
        'staff_mail' => '事業部担当者 メールアドレス',
        'staff_phone' => '事業部担当者 電話番号(ハイフンなし)',
        'client_staff_name' => '訪問企業 担当者名',
        'client_staff_mail' => '訪問企業 担当者 メールアドレス',
        'client_staff_phone' => '訪問企業 担当者 電話番号(ハイフンなし)',
        'address' => '訪問場所',
        'time_dating_begin' => '訪問予定日時',
        'time_dating_end' => '訪問予定日時',
        'date' => '日付',
        'time_begin' => '開始時間',
        'time_end' => '終了時間',
        'sfa_kind' => 'SFA種類',
        'lms_id' => '訪問企業'
    ],
];
