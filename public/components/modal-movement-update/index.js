/*
 *  modal-movement-update
 */

import { FormatNumberToCurrency } from '../../js/tools.js';

window.ToggleModalMovementUpdate = function () {
  const modal = document.getElementById('modalUpdateMovement');
  if (modal.classList.contains('is-active')) {
    modal.classList.remove('is-active');
  } else {
    modal.classList.add('is-active');
  }
};

window.ConsultMovement = async function (id) {
  await fetch(`/movement/${id}`)
    .then((response) => response.json())
    .then((movement) => {
      let formatedValue =
        movement.valor < 0 ? movement.valor * -1 : movement.valor;
      formatedValue = parseFloat(formatedValue);
      formatedValue = formatedValue.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
      });

      document.getElementById('modalMovementUpdateId').value = movement.id;
      document.getElementById('modalMovementUpdateDate').value =
        `${movement.ano}-${movement.mes.toString().padStart(2, '0')}-${movement.dia.toString().padStart(2, '0')}`;
      document.getElementById('modalMovementUpdateCategoria').value =
        movement.categoria;
      document.getElementById('modalMovementUpdateTipo').value = movement.tipo;
      document.getElementById('modalMovementUpdatePreco').value = formatedValue;
      document.getElementById('modalMovementUpdateDescricao').value =
        movement.descricao;
    });
};

const inputValuePrice = document.getElementById('modalMovementUpdatePreco');
inputValuePrice.addEventListener('input', (event) => {
  let value = inputValuePrice.value.replace(/\D/g, '');
  value = value.padStart(3, '0');
  const formattedValue = formatCurrency(value);
  inputValuePrice.value = formattedValue;
});

function formatCurrency(value) {
  const numero = parseInt(value) / 100;
  return numero.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

window.UpdateMovement = function (id) {
  const form = document.getElementById('formUpdateMovement');
  const formData = new FormData(form);

  const formDate = formData
    .get('date')
    .split('-')
    .map((item) => parseInt(item));
  const formCategory = parseInt(formData.get('category'));
  const formTypeMovement = formData.get('movType');
  let formPrice = formData.get('price');
  formPrice = formPrice.replace(/[^\d,]/g, '').replace(',', '.');
  formPrice = parseFloat(formPrice);
  formPrice = formTypeMovement !== 'entrada' ? formPrice * -1 : formPrice;
  const formDescription = formData.get('description');
  const formItemId = formData.get('id');

  const movement = {
    dia: formDate[2],
    mes: formDate[1],
    ano: formDate[0],
    tipo: formTypeMovement,
    categoria: formCategory,
    descricao: formDescription,
    valor: formPrice,
  };

  fetch(`/movement/${formItemId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(movement),
  }).then((response) => {
    window.ToggleModalMovementUpdate();
    window.getMovementsFiltered();
  });
};
