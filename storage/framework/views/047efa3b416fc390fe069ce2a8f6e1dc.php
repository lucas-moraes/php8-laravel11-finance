<div id="modalUpdateMovement" class="modal">
 <div class="modal-background"></div>
  <div class="modal-card" style="width: 535px;">
    <header class="modal-card-head has-background-info">
      <p class="modal-card-title has-text-light">Atualizar movimento</p>
      <button class="delete" aria-label="close" onclick="ToggleModalMovementUpdate()"></button>
    </header>
    <form class="modal-card-body" id="formUpdateMovement">
      <div class="columns">
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Data</label>
            <div class="constrol">
              <input id="modalMovementUpdateDate" class="input is-small" type="date" name="date"/>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Categorias</label>
            <div class="control">
              <div class="select is-small">
                <select id="modalMovementUpdateCategoria" name="category"></select>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="field">
            <label class="label is-small">Movimento</label>
            <div class="control">
              <div class="select is-small">
                <select id="modalMovementUpdateTipo" name="movType">
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
            <input type="text" class="input is-small" id="modalMovementUpdatePreco" name="price"/>
          </div>
        </div>
        <div class="column is-9">
          <div class="field">
            <label class="label is-small">Descrição</label>
            <input class="input is-small" id="modalMovementUpdateDescricao" type="text" name="description"/>
          </div>
        </div>
      </div>
      <input type="hidden" id="modalMovementUpdateId" name="id"/>
    </form>
    <footer class="modal-card-foot">
      <div>
        <button class="button is-success" onclick="UpdateMovement()">Alterar</button>
      </div>
     <button class="ml-2 button" onclick="ToggleModalMovementUpdate()">Cancelar</button>
    </footer>
  </div>
 </div>
</div>

<script type="module" src="<?php echo e(asset('components/modal-movement-update/index.js')); ?>"></script>
<?php /**PATH /home/lucas/Documents/php/finance/resources/views/components/modal-movement-update.blade.php ENDPATH**/ ?>