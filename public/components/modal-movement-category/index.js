/*
 *  modal-movement-category.js
 */

window.ToggleModalCategory = function () {
  const modal = document.getElementById('modalCategory');
  if (modal.classList.contains('is-active')) {
    modal.classList.remove('is-active');
  } else {
    modal.classList.add('is-active');
    GetCategory();
  }
};

async function GetCategory() {
  fetch('/category/get-all', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      const newList = data.map((category) => {
        return {
          category: `<tr><td>${category.descricao}</td></tr>`,
        };
      });
      const listHtml = newList.map((item) => item.category).join('');
      const modalCategoryList = document.getElementById('modalCategoryList');
      modalCategoryList.innerHTML = listHtml;
    });
}

window.RegisterCategory = function () {
  const form = document.getElementById('formInsertCategory');
  const formData = new FormData(form);
  const formDescription = formData.get('description');

  const category = {
    descricao: formDescription,
  };

  fetch('/category/create', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(category),
  }).then(async (response) => {
    await GetCategory();
    document.getElementById('description').value = '';
  });
};
