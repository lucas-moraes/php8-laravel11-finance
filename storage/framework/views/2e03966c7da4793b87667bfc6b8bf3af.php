<div id="modalInsertMovement" class="modal">
 <div class="modal-background"></div>
  <div class="modal-card" style="width: 535px;">
    <header class="modal-card-head has-background-warning">
      <p class="modal-card-title">Adicionar movimento</p>
      <button class="delete" aria-label="close" onclick="ToggleModalMovementInsert()"></button>
    </header>
    <form class="modal-card-body" id="formInsertMovement">
      <div class="columns">
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Data</label>
            <div class="constrol">
              <input id="modalMovementInsertDate" class="input is-small" type="date" name="date"/>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Categorias</label>
            <div class="control">
              <div class="select is-small">
                <select id="modalMovementInsertCategoria" name="category"></select>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Movimento</label>
            <div class="control">
              <div class="select is-small">
                <select id="tipo" name="movType">
                  <option value="entrada">Entrada de dinheiro</option>
                  <option value="saida">Saída de dinheiro</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns">
        <div class="column is-3">
          <div class="field">
            <label class="label is-small">Valor</label>
            <input type="text" class="input is-small" id="modalMovementInsertPreco" name="price"/>
          </div>
        </div>
        <div class="column is-9">
          <div class="field">
            <label class="label is-small">Descrição</label>
            <input class="input is-small" id="descricao" type="text" name="description"/>
          </div>
        </div>
      </div>
    </form>
    <footer class="modal-card-foot">
      <div>
        <button class="button is-success" onclick="RegisterMovement()">Registrar</button>
      </div>
     <button class="ml-2 button" onclick="ToggleModalMovementInsert()">Cancelar</button>
    </footer>
  </div>
 </div>
</div>



<script type="module" src="<?php echo e(asset('components/modal-movement-create/index.js')); ?>"></script>

<?php /**PATH /home/lucas/Documents/php/finance/resources/views/components/modal-movement-create.blade.php ENDPATH**/ ?>