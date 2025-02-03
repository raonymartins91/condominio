<?php

namespace App\Models\Basic;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Model;

abstract class AppModel extends Model
{

    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;


    protected bool $updateOnlyChanged = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';



    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData', 'setCode'];
    protected $beforeUpdate   = ['escapeData'];

    protected function escapeData(array $data) : array {

        return esc($data);
    }

    protected function setCode(array $data) : array {

        do {
        $code = rand(10000000, 99999999);
        $result = $this->select('code')->where('code', $code)->countAllResults();
    
        }   while ($result > 0);

        $data['data']['code'] = $code;
        return $data;
    }

    public function getByCode(string $code, array $contains = []) : object {

        $row = $this->where('code', $code)->first();
        
        if(!$row){
            $className = static::class;
            throw new   PageNotFoundException("Registro com o codigo {$code} não encontrado na tabela {$this->table} ({$className}) ");
        }

        $this->relateData($row, $contains);
        return $row;
    } 

    protected function relateData(object &$entity, array $contains = []) : void {
        

    }


}