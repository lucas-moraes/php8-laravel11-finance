/**
 * modal-movement-create.js
 */

window.ToggleModalMovementInsert = function () {
  const modal = document.getElementById('modalInsertMovement');
  if (modal.classList.contains('is-active')) {
    modal.classList.remove('is-active');
  } else {
    modal.classList.add('is-active');
  }
};

const inputValuePrice = document.getElementById('modalMovementInsertPreco');
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

window.RegisterMovement = function () {
  const form = document.getElementById('formInsertMovement');
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

  const movement = {
    dia: formDate[2],
    mes: formDate[1],
    ano: formDate[0],
    tipo: formTypeMovement,
    categoria: formCategory,
    descricao: formDescription,
    valor: formPrice,
  };

  fetch('/movement/create', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(movement),
  }).then((response) => {
    window.ToggleModalMovementInsert();
    window.getMovementsFiltered();
  });
};

const date = new Date();
const inputToday = document.getElementById('modalMovementInsertDate');
inputToday.value = date.toISOString().split('T')[0];
