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
        <td><?php print $data['sum'];?> ₽</td>
        <td><?php print $data['percents'];?> ₽</td>
        <td><?php print $data['main'];?> ₽</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td>Итого</td>
        <td></td>
        <td><?php print $items['percents_all'];?> ₽</td>
        <td><?php print $items['main_all'];?> ₽</td>
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
        <td><?php print $data['percents'];?> ₽</td>
        <td><?php print $data['main'];?> ₽</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td>Итого</td>
        <!-- <td></td> -->
        <td><?php print $items['percents_all'];?> ₽</td>
        <td><?php print $items['main_all'];?> ₽</td>
      </tr>
    </tfoot>
  <?php endif; ?>
</table>