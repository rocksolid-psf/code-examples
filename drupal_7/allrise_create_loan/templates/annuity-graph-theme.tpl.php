<div class="shedule" style="display: block;">
  <div class="container">
    <div class="wrap-table">
      <a href="#" class="close-shedule"></a>
      <div class="table-title">
        <h3>График платежей <a href="#" class="tooltip"></a></h3>
        <div class="tooltip-description">
          <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Месяц</th>
            <th>Тело</th>
            <th>Проценты</th>
            <th>Итого</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items['table'] as $k => $data) :?>
          <tr>
            <td><?php print $k;?></td>
            <td><?php print number_format($data['sum']); ?> ₽</td>
            <td><?php print number_format($data['percents']);?> ₽</td>
            <td><?php print number_format($items['annuity']);?> ₽</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td>Итого</td>
            <td></td>
            <td><?php print number_format($items['percents_all']);?> ₽</td>
            <td><?php print number_format($items['annuity_sum']);?> ₽</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>