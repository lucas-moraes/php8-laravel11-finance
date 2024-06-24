/**
 * card-movement-month.js
 *
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

document.addEventListener('DOMContentLoaded', async function () {
  const date = new Date();
  const month = date.toLocaleString('default', { month: 'long' });
  const year = date.getFullYear();
  document.getElementById('month').textContent = month;

  const categories = await fetch('/category/get-all')
    .then((response) => response.json())
    .then((data) => {
      return data;
    });

  await fetch(`/movement/filter?year=${year}&month=6`)
    .then((response) => response.json())
    .then((data) => {
      const newList = data.map((movement) => {
        categories.forEach((category) => {
          if (movement.categoria === category.idCategory) {
            movement.categoria = category.descricao;
          }
        });
        return (
          (!Object.keys(movement).includes('total') &&
            `
                            <div class="columns is-hoverable">
                                <div class="column pt-1 pb-1 is-flex has-text-left">
                                        ${
                                          movement.valor > 0
                                            ? `<span class="is-size-7 pr-4 positive"><i class="fas fa-plus"></i></span><span class="is-size-7 positive">${movement.categoria}</span>`
                                            : `<span class="is-size-7 pr-4 negative"><i class="fas fa-minus"></i></span><span class="is-size-7 negative">${movement.categoria}</span>`
                                        }

                                </div>
                                <div class="column pt-1 pb-1 has-text-right">
                                    ${
                                      movement.valor > 0
                                        ? `<span class="is-size-7 positive">${FormatNumberToCurrency(movement.valor)}</span>`
                                        : `<span class="is-size-7 negative">${FormatNumberToCurrency(movement.valor)}</span>`
                                    }
                                    <span class="is-size-7"></span>
                                </div>
                            </div>
                    `) ||
          `${
            movement.total > 0
              ? `<div class="columns">
                                            <div class="column has-text-left">
                                                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                                                <span class="is-7">Total</span>
                                            </div>
                                            <div class="column has-text-right positive">
                                                <span class="is-7">${FormatNumberToCurrency(movement.total)}</span>
                                            </div>
                                         </div>`
              : `<div class="columns">
                                            <div class="column has-text-left">
                                                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                                                <span class="is-7">Total</span>
                                            </div>
                                            <div class="column has-text-right negative">
                                                <span class="is-7">${FormatNumberToCurrency(movement.total)}</span>
                                            </div>
                                         </div>`
          }`
        );
      });
      const listHtml = newList.join('');
      const resumeMonthList = document.getElementById('resumeMonthList');
      resumeMonthList.innerHTML = listHtml;
    });
});
