@extends('layouts.app')

@section('title', 'movimentations')

@section('content')
<div class="container" id="animation">
  <div class="mt-2 mb-4">
    <h4 class="subtitle is-4 has-text-grey">Resumos</h4>
  </div>
  <div class="tile is-ancestor">
    <div class="tile is-vertical is-12">
      <div class="tile">
        <div class="tile is-parent is-vertical">
          <article class="tile is-child box notification is-white">
            <p class="title is-4">Movimento de <span id="month">mês</span></p>

            <x-card-movement-month></x-card-movement-month>

          </article>
        </div>
        <div class="tile is-parent is-vertical">
          <article class="tile is-child box notification is-primary">
            <p class="title is-4">Saldos Mensais</p>

            <x-card-movement-year></x-card-movement-year>

          </article>
        </div>
        <div class="tile is-parent">
          <article class="tile is-child box notification is-info">
            <p class="title is-4">Movimento de <span id="year">ano</span></p>

            <x-card-movement-categories></x-card-movement-categories>

          </article>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

