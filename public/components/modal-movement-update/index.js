/*
 *  modal-movement-update
 */

window.ToggleModalMovementUpdate = function () {
  const modal = document.getElementById('modalUpdateMovement');
  if (modal.classList.contains('is-active')) {
    modal.classList.remove('is-active');
  } else {
    modal.classList.add('is-active');
  }
};

window.ConsultMovement = async function (id) {
  fetch(`/movement/${id}`)
    .then((response) => response.json())
    .then((movement) => {
      console.log(movement);
    });
};
