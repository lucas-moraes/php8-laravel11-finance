/**
 * card-movement-categories.js
 *
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

document.addEventListener('DOMContentLoaded', async function () {
  const date = new Date();
  const year = date.getFullYear();
  document.getElementById('year').textContent = year;

  const categories = await fetch('/category/get-all')
    .then((response) => response.json())
    .then((data) => {
      return data;
    });

  await fetch(`/movement/filter-year-group-by-category?year=${year}`)
    .then((response) => response.json())
    .then((data) => {
      const newList = data.map((movement) => {
        categories.forEach((category) => {
          if (movement.categoria === category.idCategory) {
            movement.categoria = category.descricao;
          }
        });
        return (
          (!Object.keys(movement).includes('totalYear') &&
            `
            <div class="columns">
              <div class="column pt-1 pb-1 is-flex has-text-left">
                ${
                  movement.total > 0
                    ? '<span class="is-size-7 pr-4"><i class="fas fa-plus"></i></span>'
                    : '<span class="is-size-7 pr-4"><i class="fas fa-minus"></i></span>'
                }
                 <span class="is-size-7">${movement.categoria}</span>
              </div>
              <div class="column pt-1 pb-1 has-text-right">
                <span class="is-size-7">${FormatNumberToCurrency(movement.total)}</span>
              </div>
            </div>
          `) ||
          (Object.keys(movement).includes('totalYear') &&
            `
            <div class="columns">
                                            <div class="column has-text-left">
                                                <span class="is-size-7 pr-4"><i class="fas fa-equals"></i></span>
                                                <span class="is-7">Total</span>
                                            </div>
                                            <div class="column has-text-right">
                                                <span class="is-7">${FormatNumberToCurrency(movement.totalYear)}</span>
                                            </div>
                                         </div>
          `)
        );
      });
      const listHtml = newList.join('');
      const resumeMonthList = document.getElementById('resumeCategoriesList');
      resumeMonthList.innerHTML = listHtml;
    });
});
