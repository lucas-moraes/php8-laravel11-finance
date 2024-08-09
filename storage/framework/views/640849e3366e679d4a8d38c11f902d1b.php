<div id="modalBatch" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head has-background-warning">
        <p class="modal-card-title">Adicionar conjuto de movimentos</p>
        <button onclick="ToggleModalBatch()" class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
          <thread>
              <tr>
                  <th>Data</th>
                  <th>Categoria</th>
                  <th>Tipo</th>
                  <th class="has-text-right">Valor</th>
                  <th class="has-text-centered">Opções</th>
              </tr>
          </thread>
          <tbody id="batchMovements"></tbody>
        </table>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" onclick="saveBatch()">Lançar</button>
        <button class="ml-2 button" onclick="ToggleModalBatch()">Cancelar</button>
      </footer>
    </div>
</div>


<script type="module" src="<?php echo e(asset('components/modal-movement-batch/index.js')); ?>"></script>

<?php /**PATH /home/lucas/Documents/php/finance/resources/views/components/modal-movement-batch.blade.php ENDPATH**/ ?>