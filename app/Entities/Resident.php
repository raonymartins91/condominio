<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Resident extends Entity
{

    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => '?integer',
        'user_id' => '?integer',
        'code' => '?integer',
    ];

    /**
     * indica se o residente tem usuario associado
     */
    public function hasUser() : bool {
        return $this->user_id !== null;
    }
}
