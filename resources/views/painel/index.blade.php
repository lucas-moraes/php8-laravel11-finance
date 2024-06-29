@extends('layouts.app')

@section('title', 'painel')

@section('content')
<div class="container" id="animation">
    <div class="pt-2 pb-2">
        <h4 class="subtitle is-4 has-text-grey">Lançamentos</h4>
    </div>
    <div class="pt-4 pb-4 is-justify-content-space-between is-flex">
        <div>
            <div class="select">
                <select id="select_category">
                    <option value='0' selected>Categoria</option>
                    <option value="categoria">categoria1</option>
                </select>
            </div>
            <div class="select">
                <select id="select_month">
                    <option value='0' selected>Mês</option>
                    <option  value="mes">mes</option>
                </select>
            </div>
            <div class="select">
                <select id="select_year">
                    <option value='0' selected>Ano</option>
                    <option value="ano">ano</option>
                </select>
            </div>
            <button class="button is-primary"><i class="fas fa-filter mr-2"></i>Filtrar</button>
        </div>
        <div>
            <button class="button has-background-link-light"><i class="fas fa-plus mr-2"></i>Categorias</button>
            <button class="button has-background-danger-light"><i class="fas fa-plus mr-2"></i>Conjunto</button>
            <button class="button is-info"><i class="fas fa-plus mr-2"></i>Movimento</button>
        </div>
    </div>
   <x-table-movements></x-table-movements>
</div>

@endsection
