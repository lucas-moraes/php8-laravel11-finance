window.categories;

async function getCategories() {
  window.categories = await fetch('/category/get-all')
    .then((response) => response.json())
    .then((data) => {
      return data;
    });
}

async function getMonthAndYearAndCategoryForFillSelects() {
  const month = window.GlobalStore.getData('month');
  const year = window.GlobalStore.getData('year');
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

  await fetch(
    '/movement/get-all?group-by-category=true&group-by-month=true&group-by-year=true'
  )
    .then((response) => response.json())
    .then((data) => {
      /* Category */
      const categoriesList = data?.categories?.map((item) => {
        window.categories.forEach((category) => {
          if (item.categoria === category.idCategory) {
            item.value = item.categoria;
            item.description = category.descricao;
          }
        });
        return ` <option value='${item.value}'>${item.description}</option>`;
      });
      categoriesList.unshift(`<option value='0' selected>Categoria</option>`);
      const categoryListHtml = categoriesList.join('');
      const categoriesOptionList =
        document.getElementById('categoryOptionList');
      categoryOptionList.innerHTML = categoryListHtml;
      categoriesOptionList.addEventListener('change', (event) => {
        window.GlobalStore.setData('category', event.target.value);
      });
      /*************/

      /* Month */
      const monthList = data?.months?.map((item) => {
        return ` <option value='${item.mes}'>${monthDictionary[item.mes]}</option>`;
      });
      const monthListHtml = monthList.join('');
      const monthsOptionList = document.getElementById('monthOptionList');
      monthsOptionList.innerHTML = monthListHtml;

      for (let i = 0; i < monthsOptionList.options.length; i++) {
        if (monthsOptionList.options[i].value == month) {
          monthsOptionList.options[i].selected = true;
          break;
        }
      }

      monthsOptionList.addEventListener('change', (event) => {
        window.GlobalStore.setData('month', event.target.value);
      });
      /***********/

      /* Year */
      const yearList = data?.years?.map((item) => {
        return ` <option value='${item.ano}'>${item.ano}</option>`;
      });
      const yearListHtml = yearList.join('');
      const yearsOptionList = document.getElementById('yearOptionList');
      yearsOptionList.innerHTML = yearListHtml;

      for (let i = 0; i < yearsOptionList.options.length; i++) {
        if (yearsOptionList.options[i].value == year) {
          yearsOptionList.options[i].selected = true;
          break;
        }
      }

      yearsOptionList.addEventListener('change', (event) => {
        window.GlobalStore.setData('year', event.target.value);
      });
      /***********/
    });
}

(async function () {
  await getCategories();
  await getMonthAndYearAndCategoryForFillSelects();
})();

class GlobalStore {
  constructor() {
    this.store = {};
  }

  setData(key, value) {
    this.store[key] = value;
  }

  getData(key) {
    return this.store[key];
  }

  static getInstance() {
    if (!GlobalStore.instance) {
      GlobalStore.instance = new GlobalStore();
    }

    return GlobalStore.instance;
  }
}

window.GlobalStore = GlobalStore.getInstance();

const date = new Date();
const month = date.getMonth() + 1;
const year = date.getFullYear();

window.GlobalStore.setData('month', month);
window.GlobalStore.setData('year', year);
window.GlobalStore.setData('category', 0);
