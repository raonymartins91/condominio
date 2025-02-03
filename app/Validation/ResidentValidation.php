<?php

namespace App\Validation;

class ResidentValidation
{
    public function getRules(?string $code = null): array
    {

        return [
            'id' => [
                'rules' => 'permit_empty|is_natural_no_zero'
            ],

            'name' => [
                'rules' => [
                    'required',
                    'max_length[100]',
                ],
                'erros' => [
                    'required' => 'O nome e obrigatorio',
                    'required' => 'O nome deve ter no maximo 100 caractéres',
                ],
            ],

            'mobile_phone' => [
                'rules' => [
                    'required',
                    "is_unique[residents.mobile_phone,code,{$code}]",
                ],

                'apartment' => [
                    'rules' => [
                        'required',
                    ],
                ],

            ],
        ];
    }
}
