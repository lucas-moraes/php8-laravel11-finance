/**
 * table-movements.js
 *
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

window.DeleteMovement = async function (rowid) {
  await fetch(`/movement/${rowid}`, { method: 'DELETE' }).then((response) => {
    window.getMovementsFiltered();
  });
};

window.getMovementsFiltered = async function () {
  const s = window.GlobalStore;
  const month = s.getData('month');
  const year = s.getData('year');
  const category = s.getData('category');

  await fetch(
    `/movement/filter?year=${year}&month=${month}${category > 0 ? `&category=${category}` : ''}`
  )
    .then((response) => response.json())
    .then((data) => {
      const newList = data.map((movement) => {
        window.categories.forEach((category) => {
          if (movement.categoria === category.idCategory) {
            movement.categoria = category.descricao;
          }
        });
        return {
          movement:
            (!Object.keys(movement).includes('total') &&
              `
            <tr class=${movement.valor > 0 ? 'positive' : 'negative'}>
                <td><span class="is-size-6">${movement.dia} / ${movement.mes} / ${movement.ano}</span></td>
                <td><span class="is-size-6">${movement.categoria}</span></td>
                <td><span class="is-size-6">${movement.tipo}</span></td>
                <td><span class="is-size-6">${movement.descricao}</span></td>
                <td class="has-text-centered has-text-dark">
                    <button class="icon ml-2 mr-2 button is-warning" onclick="ToggleModalMovementUpdate(); ConsultMovement(${movement.rowid}); ">
                              <i class="fas fa-cog"></i>
                    </button>
                    <button class="icon ml-2 mr-2 button is-danger" onclick="DeleteMovement(${movement.rowid})">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
                <td class="has-text-right"><span class="is-size-6">${FormatNumberToCurrency(movement.valor)}</span></td>
            </tr>
            `) ||
            '',
          sumary:
            (Object.keys(movement).includes('total') &&
              `
                <tr align="right">
                    <td class='has-text-right'>Resultado</td>
                    <td class='has-text-right ${movement.total > 0 ? 'positive' : 'negative'}'>${FormatNumberToCurrency(movement.total)}</td>
                </tr>
            `) ||
            '',
        };
      });
      const listHtml = newList.map((item) => item.movement).join('');
      const resumeMonthList = document.getElementById('tableMovements');
      resumeMonthList.innerHTML = listHtml;

      const sumaryHtml = newList.map((item) => item.sumary).join('');
      const resumeMonthSumary = document.getElementById('tableSumary');
      resumeMonthSumary.innerHTML = sumaryHtml;
    });
};

document.addEventListener(
  'DOMContentLoaded',
  await window.getMovementsFiltered()
);
