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
        <?php if (isset($items['diff_type']) && $items['diff_type'] == 1 )  : ?>
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
              <td><?php print number_format($data['sum']);?> ₽</td>
              <td><?php print number_format($data['percents']);?> ₽</td>
              <td><?php print number_format($data['main']);?> ₽</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td>Итого</td>
              <td></td>
              <td><?php print number_format($items['percents_all']);?> ₽</td>
              <td><?php print number_format($items['main_all']);?> ₽</td>
            </tr>
          </tfoot>
        <?php endif; ?>
        <?php if (isset($items['diff_type']) && $items['diff_type'] == 2 )  : ?>
           <thead>
            <tr>
              <th>Месяц</th>
              <!-- <th>Тело</th> -->
              <th>Проценты</th>
              <th>Итого</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items['table'] as $k => $data) :?>
            <tr>
              <td><?php print $k;?></td>
              <?php /*<td><?php print $data['sum'];?> ₽</td> */ ?>
              <td><?php print number_format($data['percents']);?> ₽</td>
              <td><?php print number_format($data['main']);?> ₽</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td>Итого</td>
              <!-- <td></td> -->
              <td><?php print number_format($items['percents_all']);?> ₽</td>
              <td><?php print number_format($items['main_all']);?> ₽</td>
            </tr>
          </tfoot>
        <?php endif; ?>
      </table>
    </div>
  </div>
</div>