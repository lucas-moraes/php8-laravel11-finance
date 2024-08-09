<div id="modalCategory" class="modal">
  <div class="modal-background"></div>
  <div class="modal-card" style="width: 535px;">
    <header class="modal-card-head has-background-warning">
      <p class="modal-card-title">Categorias</p>
      <button onclick="ToggleModalCategory()" class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <div class="content is-centered">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth" ng-show="!isLoading">
          <tbody id="modalCategoryList"></tbody>
          </table>
      </div>
    </section>
    <footer class="modal-card-foot">
        <form id="formInsertCategory">
            <input class="input mr-2" type="text" placeholder="Categoria" name="description" id="description">
        </form>
      <button class="button is-success" onclick="RegisterCategory()">Adicionar</button>
      <button class="button" onclick="ToggleModalCategory()">Cancelar</button>
    </footer>
  </div>
</div>

<script type="module" src="<?php echo e(asset('components/modal-movement-category/index.js')); ?>"></script>
<?php /**PATH /home/lucas/Documents/php/finance/resources/views/components/modal-movement-category.blade.php ENDPATH**/ ?>