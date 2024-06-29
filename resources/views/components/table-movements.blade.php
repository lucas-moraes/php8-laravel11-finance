<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
   <thread>
       <tr>
           <th>Data</th>
           <th>Categoria</th>
           <th>Tipo</th>
           <th>Descrição</th>
           <th class="has-text-centered">Opções</th>
           <th class="has-text-right">Valor</th>
       </tr>
   </thread>
   <tbody id="tableMovements"></tbody>
</table>
<table style="display: flex;justify-content: flex-end;">
   <tfoot class="table is-hoverable is-narrow is-bordered" id="tableSumary"></tfoot>
</table>

<link rel="stylesheet" href="{{ asset('components/table-movements/index.css') }}">

<script type="module" src="{{ asset('components/table-movements/index.js') }}"></script>
