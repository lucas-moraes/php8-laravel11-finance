window.categories;

async function getCategories() {
  window.categories = await fetch('/category/get-all')
    .then((response) => response.json())
    .then((data) => {
      return data;
    });
}

(async function () {
  await getCategories();
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
