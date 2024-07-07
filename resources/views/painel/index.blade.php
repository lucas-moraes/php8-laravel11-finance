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
                <select id="categoryOptionList"></select>
            </div>
            <div class="select">
                <select id="monthOptionList"></select>
            </div>
            <div class="select">
                <select id="yearOptionList"></select>
            </div>
            <button class="button is-primary" onclick="getMovementsFiltered()"><i class="fas fa-filter mr-2"></i>Filtrar</button>
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
