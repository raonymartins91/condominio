<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Resident;
use App\Models\ResidentModel;
use App\Validation\ResidentValidation;
use CodeIgniter\HTTP\RedirectResponse;

class ResidentsController extends BaseController
{

    private ResidentModel $model;

    public function __construct()
    {
        $this->model = model(ResidentModel::class);
    }

    /**
     * PAGINA INICIO
     */
    public function index()
    {
        $data = [
            'title'  => 'Gerenciador residentes',
            'residents' => $this->model->orderBy('created_at', 'DES')->findAll(),
        ];

        return view('Residents/index', $data);
    }

    /**
     * PAGINA FORMULARIO
     */
    public function new()
    {


        $data = [
            'title'  => 'Novo residente',
            'resident' => new Resident(),
            'route' => route_to('residents.create'),

        ];

        return view('Residents/form', $data);
    }

    /**
     * CRIAR RESIDENTE
     */
    public function create(): RedirectResponse
    {

        $rules = (new ResidentValidation)->getRules();

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $resident = new Resident($this->validator->getValidated());
        $id = $this->model->insert($resident);
        $resident = $this->model->find($id);

        return redirect()->route('residents.show', [$resident->code])->with('success', 'Criado com Sucesso!');
    }

    /**
     * PAGINA DE LISTAR RESIDENTE
     */
    public function show(string $code)
    {
        $resident = $this->model->getByCode(code: $code);

        $data = [
            'title'  => 'Detalhes do residente',
            'resident' => $resident,
        ];

        return view('Residents/show', $data);
    }

    /**
     * EDITAR RESIDENTE
     */
    public function edit(string $code)
    {
        $resident = $this->model->getByCode(code: $code);

        $data = [
            'title'  => 'Editar residente',
            'resident' => $resident,
            'route' => route_to('residents.update', $resident->code),
            'hidden' => ['_method' => 'PUT']
        ];

        return view('Residents/form', $data);
    }

    /**
     * ATUALIZAR RESIDENTE
     */
    public function update(string $code): RedirectResponse
    {
      
        $rules = (new ResidentValidation)->getRules(code: $code);
        dd($this->request->getPost());

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $resident = $this->model->getByCode(code: $code);
        $resident->fill($this->validator->getValidated());
        $this->model->save($resident);
        

        return redirect()->route('residents.show', [$resident->code])->with('success', 'Atualizado com Sucesso!');
    }

    /**
     * DELETAR RESIDENTE
     */
    public function destroy(string $code): RedirectResponse
    {
        $this->model->where('code', $code)->delete();

        return redirect()->route('residents')->with('success', 'Deletado com Sucesso!');
    }
}
