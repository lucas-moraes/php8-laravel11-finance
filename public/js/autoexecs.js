window.categories;

async function getCategories() {
  window.categories = await fetch('/category/get-all')
    .then((response) => response.json())
    .then((data) => {
      return data;
    });
}

async function getMonthAndYearAndCategoryForFillSelects() {
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
      /*************/

      /* Month */
      const monthList = data?.months?.map((item) => {
        return ` <option value='${item.mes}'>${monthDictionary[item.mes]}</option>`;
      });
      monthList.unshift(`<option value='0' selected>Mês</option>`);
      const monthListHtml = monthList.join('');
      const monthsOptionList = document.getElementById('monthOptionList');
      monthsOptionList.innerHTML = monthListHtml;
      /***********/

      /* Year */
      const yearList = data?.years?.map((item) => {
        return ` <option value='${item.ano}'>${item.ano}</option>`;
      });
      yearList.unshift(`<option value='0' selected>Ano</option>`);
      const yearListHtml = yearList.join('');
      const yearsOptionList = document.getElementById('yearOptionList');
      yearsOptionList.innerHTML = yearListHtml;
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
