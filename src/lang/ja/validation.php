<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attributeを承認する必要があります。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認する必要があります。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeは、:dateより後の日付にしてください。',
    'after_or_equal' => ':attributeは、:dateより後か同じ日付にしてください。',
    'alpha' => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash' => ':attributeには、英数字(\'A-Z\',\'a-z\',\'0-9\')とハイフンとアンダーバーのみ使用できます。',
    'alpha_num' => ':attributeには、英数字のみ使用できます。',
    'any_of' => ':attributeの値が正しくありません。',
    'array' => ':attributeは、配列にしてください。',
    'ascii' => ':attributeは、半角英数字と記号のみ使用できます。',
    'before' => ':attributeは、:dateより前の日付にしてください。',
    'before_or_equal' => ':attributeは、:dateより前か同じ日付にしてください。',
    'between' => [
        'array' => ':attributeは、:min個から:max個までの要素にしてください。',
        'file' => ':attributeは、:min KBから:max KBまでのサイズにしてください。',
        'numeric' => ':attributeは、:minから:maxまでの数値にしてください。',
        'string' => ':attributeは、:min文字から:max文字までで入力してください。',
    ],
    'boolean' => ':attributeは、trueかfalseを指定してください。',
    'can' => ':attributeに不正な値が含まれています。',
    'confirmed' => ':attributeと確認用フィールドが一致しません。',
    'contains' => ':attributeに必須の値が含まれていません。',
    'current_password' => '現在のパスワードが正しくありません。',
    'date' => ':attributeは、正しい日付ではありません。',
    'date_equals' => ':attributeは、:dateと同じ日付にしてください。',
    'date_format' => ':attributeは、:format形式で入力してください。',
    'decimal' => ':attributeは、:decimal桁の小数点にしてください。',
    'declined' => ':attributeは、拒否される必要があります。',
    'declined_if' => ':otherが:valueの場合、:attributeは拒否される必要があります。',
    'different' => ':attributeと:otherには、異なる値を指定してください。',
    'digits' => ':attributeは、:digits桁で入力してください。',
    'digits_between' => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions' => ':attributeの画像サイズが無効です。',
    'distinct' => ':attributeに重複した値があります。',
    'doesnt_contain' => ':attributeには次の値を含めることはできません: :values。',
    'doesnt_end_with' => ':attributeは次の値で終わることはできません: :values。',
    'doesnt_start_with' => ':attributeは次の値で始まることはできません: :values。',
    'email' => ':attributeは、有効なメールアドレス形式で入力してください。',
    'ends_with' => ':attributeは、次の値で終わる必要があります: :values',
    'enum' => 'selected :attributeは、正しくありません。',
    'exists' => 'selected :attributeは、正しくありません。',
    'extensions' => ':attributeは次の拡張子のファイルでなければなりません: :values。',
    'file' => ':attributeはファイルを指定してください。',
    'filled' => ':attributeは必須です。',
    'gt' => [
        'array' => ':attributeの要素数は、:value個より大きくしてください。',
        'file' => ':attributeは、:value KBより大きくしてください。',
        'numeric' => ':attributeは、:valueより大きくしてください。',
        'string' => ':attributeは、:value文字より多く入力してください。',
    ],
    'gte' => [
        'array' => ':attributeの要素数は、:value個以上にしてください。',
        'file' => ':attributeは、:value KB以上にしてください。',
        'numeric' => ':attributeは、:value以上にしてください。',
        'string' => ':attributeは、:value文字以上で入力してください。',
    ],
    'hex_color' => ':attributeは、有効な16進数カラーコードにしてください。',
    'image' => ':attributeは、画像を指定してください。',
    'in' => 'selected :attributeは、正しくありません。',
    'in_array' => ':attributeが:other内に存在しません。',
    'in_array_keys' => ':attributeには次のキーのうち少なくとも1つを含める必要があります: :values。',
    'integer' => ':attributeは、整数で入力してください。',
    'ip' => ':attributeは、有効なIPアドレスを指定してください。',
    'ipv4' => ':attributeは、有効なIPv4アドレスを指定してください。',
    'ipv6' => ':attributeは、有効なIPv6アドレスを指定してください。',
    'json' => ':attributeは、正しいJSON形式で入力してください。',
    'list' => ':attributeはリストである必要があります。',
    'lowercase' => ':attributeは小文字で入力してください。',
    'lt' => [
        'array' => ':attributeの要素数は、:value個より小さくしてください。',
        'file' => ':attributeは、:value KBより小さくしてください。',
        'numeric' => ':attributeは、:valueより小さくしてください。',
        'string' => ':attributeは、:value文字より少なく入力してください。',
    ],
    'lte' => [
        'array' => ':attributeの要素数は、:value個以下にしてください。',
        'file' => ':attributeは、:value KB以下にしてください。',
        'numeric' => ':attributeは、:value以下にしてください。',
        'string' => ':attributeは、:value文字以下で入力してください。',
    ],
    'mac_address' => ':attributeは、有効なMACアドレスを指定してください。',
    'max' => [
        'array' => ':attributeの要素数は、:max個以下にしてください。',
        'file' => ':attributeは、:max KB以下のファイルを選択してください。',
        'numeric' => ':attributeは、:max以下で入力してください。',
        'string' => ':attributeは、:max文字以下で入力してください。',
    ],
    'max_digits' => ':attributeは:max桁以下で入力してください。',
    'mimes' => ':attributeは、:valuesタイプのファイルを選択してください。',
    'mimetypes' => ':attributeは、:valuesタイプのファイルを選択してください。',
    'min' => [
        'array' => ':attributeの要素数は、:min個以上にしてください。',
        'file' => ':attributeは、:min KB以上のファイルを選択してください。',
        'numeric' => ':attributeは、:min以上で入力してください。',
        'string' => ':attributeは、:min文字以上で入力してください。',
    ],
    'min_digits' => ':attributeは:min桁以上で入力してください。',
    'missing' => ':attributeは存在しない必要があります。',
    'missing_if' => ':otherが:valueの場合、:attributeは存在しない必要があります。',
    'missing_unless' => ':otherが:valuesに含まれていない場合、:attributeは存在しない必要があります。',
    'missing_with' => ':valuesが存在する場合、:attributeは存在しない必要があります。',
    'missing_with_all' => ':valuesがすべて存在する場合、:attributeは存在しない必要があります。',
    'multiple_of' => ':attributeは、:valueの倍数にしてください。',
    'not_in' => 'selected :attributeは、正しくありません。',
    'not_regex' => ':attributeの形式が正しくありません。',
    'numeric' => ':attributeは、数値で入力してください。',
    'password' => [
        'letters' => ':attributeは少なくとも1つの文字を含む必要があります。',
        'mixed' => ':attributeは少なくとも1つの大文字と1つの小文字を含む必要があります。',
        'numbers' => ':attributeは少なくとも1つの数字を含む必要があります。',
        'symbols' => ':attributeは少なくとも1つの記号を含む必要があります。',
        'uncompromised' => 'この:attributeはデータ漏洩で発見されています。別の:attributeを選択してください。',
    ],
    'present' => ':attributeが存在している必要があります。',
    'present_if' => ':otherが:valueの場合、:attributeが存在している必要があります。',
    'present_unless' => ':otherが:valuesに含まれていない場合、:attributeが存在している必要があります。',
    'present_with' => ':valuesが存在する場合、:attributeが存在している必要があります。',
    'present_with_all' => ':valuesがすべて存在する場合、:attributeが存在している必要があります。',
    'prohibited' => ':attributeの入力は禁止されています。',
    'prohibited_if' => ':otherが:valueの場合、:attributeの入力は禁止されています。',
    'prohibited_if_accepted' => ':otherが承認された場合、:attributeの入力は禁止されています。',
    'prohibited_if_declined' => ':otherが拒否された場合、:attributeの入力は禁止されています。',
    'prohibited_unless' => ':otherが:valuesに含まれていない場合、:attributeの入力は禁止されています。',
    'prohibits' => ':attributeは:otherの存在を禁止しています。',
    'regex' => ':attributeの形式が正しくありません。',
    'required' => ':attributeは必須項目です。',
    'required_array_keys' => ':attributeには次のエントリが含まれている必要があります: :values。',
    'required_if' => ':otherが:valueの場合、:attributeも入力してください。',
    'required_if_accepted' => ':otherが承認された場合、:attributeは必須です。',
    'required_if_declined' => ':otherが拒否された場合、:attributeは必須です。',
    'required_unless' => ':otherが:valuesに含まれない場合、:attributeを入力してください。',
    'required_with' => ':valuesが入力されている場合、:attributeも入力してください。',
    'required_with_all' => ':valuesがすべて入力されている場合、:attributeも入力してください。',
    'required_without' => ':valuesが入力されていない場合、:attributeを入力してください。',
    'required_without_all' => ':valuesがすべて入力されていない場合、:attributeを入力してください。',
    'same' => ':attributeと:otherが一致していません。',
    'size' => [
        'array' => ':attributeは、:size個の要素にしてください。',
        'file' => ':attributeは、:size KBにしてください。',
        'numeric' => ':attributeは、:sizeにしてください。',
        'string' => ':attributeは、:size文字にしてください。',
    ],
    'starts_with' => ':attributeは、次のうちのいずれかで始まる必要があります。:values',
    'string' => ':attributeは、文字で入力してください。',
    'timezone' => ':attributeは、正しいタイムゾーンを指定してください。',
    'unique' => 'その:attributeは既に使用されています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'uppercase' => ':attributeは大文字で入力してください。',
    'url' => ':attributeは、有効なURL形式で入力してください。',
    'ulid' => ':attributeは、有効なULIDである必要があります。',
    'uuid' => ':attributeは、有効なUUIDである必要があります。',

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
        'password' => [
            'min' => 'パスワードは:min文字以上で入力してください。',
            'max' => 'パスワードは:max文字以下で入力してください。',
            'confirmed' => 'パスワードと確認用パスワードが一致しません。',
        ],
        'email' => [
            'max' => 'メールアドレスは:max文字以下で入力してください。',
            'unique' => 'このメールアドレスは既に使用されています。',
        ],
        'name' => [
            'required' => '名前は必須項目です。',
            'max' => '名前は:max文字以下で入力してください。',
        ],
        'grade' => [
            'required' => '学年は必須項目です。',
            'in' => '正しい学年を選択してください。',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'grade' => '学年',
        'current_password' => '現在のパスワード',
        'remember' => 'ログイン状態を保持する',
    ],

];
