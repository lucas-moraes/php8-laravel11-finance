/**
 * card-movement-year.js
 *
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

document.addEventListener('DOMContentLoaded', async function () {
  const monthDictionary = {
    1: 'Janeiro',
    2: 'Fevereiro',
    3: 'Março',
    4: 'Abril',
    5: 'Maio',
    6: 'Junho',
    7: 'Julho',
    8: 'Agosto',
    9: 'Setembro',
    10: 'Outubro',
    11: 'Novembro',
    12: 'Dezembro',
  };

  const date = new Date();
  const year = date.getFullYear();

  await fetch(`/movement/filter-year-group-by-month/${year}`)
    .then((response) => response.json())
    .then((data) => {
      const newList = data.map((movement) => {
        return `<div class="columns">
                            <div class="column pt-1 pb-1 is-flex has-text-left">
                                ${
                                  movement.total > 0
                                    ? `<span class="is-size-6 pr-4"><i class="fas fa-plus"></i></span>`
                                    : `<span class="is-size-6 pr-4"><i class="fas fa-minus"></i></span>`
                                }
                                <span class="is-size-6">${monthDictionary[movement.mes]}</span>
                                <span class="is-size-6">/</span>
                                <span class="is-size-6">${year}</span>
                                </div>
                                <div class="column pt-1 pb-1 has-text-right">
                                    <span class="is-size-7">${FormatNumberToCurrency(movement.total)}</span>
                                </div>
                            </div>
                        `;
      });
      const listHtml = newList.join('');
      const resumeYearList = document.getElementById('resumeYearList');
      resumeYearList.innerHTML = listHtml;
    });
});
