/*
 *  modal-movement-batch.js
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

window.ToggleModalBatch = function () {
  const modal = document.getElementById('modalBatch');
  if (modal.classList.contains('is-active')) {
    modal.classList.remove('is-active');
  } else {
    modal.classList.add('is-active');
    GetLastMovements();
  }
};

async function GetLastMovements() {
  const s = window.GlobalStore;
  const month = s.getData('month');
  const year = s.getData('year');

  try {
    const response = await fetch(
      `/movement/filter?year=${year}&month=${month}`
    );
    const data = await response.json();
    window.dataArray = data.filter(
      (movement) => !Object.keys(movement).includes('total')
    );
    updateList();
  } catch (error) {
    console.error('Erro ao obter movimentos:', error);
  }
}

window.deleteItemConjunto = function (index) {
  if (typeof dataArray !== 'undefined') {
    dataArray.splice(index, 1);
    updateList();
  }
};

function updateList() {
  const newList = dataArray
    .map((movement, index) => {
      window.categories.forEach((category) => {
        if (movement.categoria === category.idCategory) {
          movement.categoria = category.descricao;
        }
      });
      return `<tr class="${movement.valor > 0 ? 'positive' : 'negative'}">
            <td><span class="is-size-6">${movement.dia} / ${movement.mes} / ${movement.ano}</span></td>
            <td><span class="is-size-6">${movement.categoria}</span></td>
            <td><span class="is-size-6">${movement.tipo}</span></td>
            <td class="has-text-right"><span class="is-size-6">${FormatNumberToCurrency(movement.valor)}</span></td>
            <td class="has-text-centered has-text-dark">
                <span class="icon ml-2 mr-2 button" onclick="deleteItemConjunto(${index})">
                    <i class="fas fa-times"></i>
                </span>
            </td>
           </tr>`;
    })
    .join('');

  const resumeMonthList = document.getElementById('batchMovements');
  resumeMonthList.innerHTML = newList;
}

window.saveBatch = async function () {
  const s = window.GlobalStore;
  const month = s.getData('month');
  const year = s.getData('year');

  const dataToSave = dataArray.map((movement) => {
    window.categories.forEach((category) => {
      if (movement.categoria === category.descricao) {
        movement.categoria = category.idCategory;
      }
    });
    return {
      dia: movement.dia,
      mes: movement.mes + 1,
      ano: movement.ano,
      categoria: movement.categoria,
      tipo: movement.tipo,
      valor: movement.valor,
    };
  });

  try {
    const response = await fetch(`/movement/create-multiple`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(dataToSave),
    });
    const data = await response.json();
    if (data.error) {
      console.error('Erro ao salvar movimentos:', data.error);
    } else {
      window.ToggleModalBatch();
      s.setData('month', month);
      s.setData('year', year);
      window.GetMovements();
    }
  } catch (error) {
    console.error('Erro ao salvar movimentos:', error);
  }
};
