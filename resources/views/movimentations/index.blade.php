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

            <div id="resumeMonthList"></div>

          </article>
        </div>
        <div class="tile is-parent is-vertical">
          <article class="tile is-child box notification is-primary">
            <p class="title is-4">Saldos Mensais</p>
            <div class="columns">
              <div class="column pt-1 pb-1 is-flex has-text-left">
                <span class="is-size-6 pr-4"><i class="fas fa-plus"></i></span>
                <span class="is-size-6 pr-4"><i class="fas fa-minus"></i></span>
                <span class="is-size-6"></span>
                <span class="is-size-6">/</span>
                <span class="is-size-6"></span>
              </div>
              <div class="column pt-1 pb-1 has-text-right">
                <span class="is-size-6"></span>
              </div>
            </div>
          </article>
        </div>
        <div class="tile is-parent">
          <article class="tile is-child box notification is-info">
            <p class="title is-4">Movimento de </p>
            <div class="columns">
              <div class="column pt-1 pb-1 is-flex has-text-left">
                <span class="is-size-7 pr-4"><i class="fas fa-plus"></i></span>
                <span class="is-size-7 pr-4"><i class="fas fa-minus"></i></span>
                <span class="is-size-7"></span>
              </div>
              <div class="column pt-1 pb-1 has-text-right">
                <span class="is-size-7"></span>
              </div>
            </div>
            <div class="columns">
              <div class="column has-text-left">
                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                <span class="is-7">Total</span>
              </div>
              <div class="column has-text-right">
                <span class="is-7"></span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const date = new Date();
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            document.getElementById('month').textContent = month;

            const categories = await fetch('/category/getAll')
                .then(response => response.json())
                .then(data => {
                    return data;
                });

            await fetch('/movement/filter?year=2024&month=6')
                .then(response => response.json())
                .then(data => {
                    const newList = data.map(movement => {
                        categories.forEach(category => {
                            if (movement.categoria === category.idCategory) {
                                movement.categoria = category.descricao;
                            }
                        });
                        return !Object.keys(movement).includes('total') &&`
                            <div class="columns is-hoverable">
                                <div class="column pt-1 pb-1 is-flex has-text-left">
                                        ${
                                            movement.valor > 0 ?
                                                `<span class="is-size-7 pr-4 positive"><i class="fas fa-plus"></i></span><span class="is-size-7 positive">${movement.categoria}</span>`
                                            :
                                                `<span class="is-size-7 pr-4 negative"><i class="fas fa-minus"></i></span><span class="is-size-7 negative">${movement.categoria}</span>`
                                        }

                                </div>
                                <div class="column pt-1 pb-1 has-text-right">
                                    ${
                                        movement.valor > 0 ?
                                            `<span class="is-size-7 positive">${Number(movement.valor).toFixed(2)}</span>`
                                        :
                                            `<span class="is-size-7 negative">${Number(movement.valor).toFixed(2)}</span>`
                                    }
                                    <span class="is-size-7"></span>
                                </div>
                            </div>
                    ` || `
                            ${
                                movement.total > 0 ?
                                        `<div class="columns">
                                            <div class="column has-text-left">
                                                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                                                <span class="is-7">Total</span>
                                            </div>
                                            <div class="column has-text-right positive">
                                                <span class="is-7">${Number(movement.total).toFixed(2)}</span>
                                            </div>
                                         </div>`
                                    :
                                        `<div class="columns">
                                            <div class="column has-text-left">
                                                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                                                <span class="is-7">Total</span>
                                            </div>
                                            <div class="column has-text-right negative">
                                                <span class="is-7">${Number(movement.total).toFixed(2)}</span>
                                            </div>
                                         </div>`
                            }`;


                    });
                    const listHtml = newList.join('');
                    const resumeMonthList = document.getElementById('resumeMonthList');
                    resumeMonthList.innerHTML = listHtml;
                });
        });
    </script>
@endsection

@section('styles')
    <style>
        .positive {
            color: #3273dc;
        }
        .negative {
            color: #f14668;
        }
    </style>
@endsection


