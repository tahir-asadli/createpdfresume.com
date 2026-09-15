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

    'between' => [
        'numeric' => ':attribute alanı :min ile :max arasında olmalıdır.',
    ],
    'date_format' => ':attribute alanı :format biçimiyle eşleşmelidir.',
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'gt' => [
    ],
    'gte' => [
    ],
    'image' => ':attribute alanı bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'lt' => [
    ],
    'lte' => [
    ],
    'max' => [
        'file' => ':attribute alanı :max kilobayttan büyük olmamalıdır.',
        'string' => ':attribute alanı :max karakterden büyük olmamalıdır.',
    ],
    'mimes' => ':attribute alanı :values ​​türünde bir dosya olmalıdır.',
    'min' => [
        'string' => ':attribute alanı en az :min karakter uzunluğunda olmalıdır.',
    ],
    'not_in' => 'Seçilen :attribute geçersiz.',
    'password' => [
    ],
    'required' => ':attribute alanı zorunludur.',
    'same' => 'The :attribute field must match :other.',
    'size' => [
    ],
    'uploaded' => ':attribute yüklenemedi.',
    'uuid' => ':attribute alanı geçerli bir UUID olmalıdır.',

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
        "profile_image" => __('Profil Resmi')
    ],

];
