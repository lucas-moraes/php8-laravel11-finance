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
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thread>
            <tr>
                <th>Data</th>
                <th>Categoria</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th class="has-text-centered">Opções</th>
                <th class="has-text-right">Valor</th>
            </tr>
        </thread>
        <tbody>
            <tr>
                <td><span class="is-size-6"></span></td>
                <td><span class="is-size-6"></span></td>
                <td><span class="is-size-6"></span></td>
                <td><span class="is-size-6"></span></td>
                <td class="has-text-centered has-text-dark">
                    <span class="button icon ml-2 mr-2">
                        <i class="fas fa-cog"></i>
                    </span>
                    <span class="icon ml-2 mr-2 button">
                        <i class="fas fa-times"></i>
                    </span>
                </td>
                <td class="has-text-right"><span class="is-size-6"></span></td>
            </tr>
            <modal-movimento></modal-movimento>
            <modal-categorias></modal-categorias>
            <modal-conjunto></modal-conjunto>
        </tbody>
    </table>
    <table style="display: flex;justify-content: flex-end;">
        <tfoot class="table is-hoverable is-narrow is-bordered">
            <tr align="right">
                <td class='has-text-right'>Resultado</td>
                <td class='has-text-right'></td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection

