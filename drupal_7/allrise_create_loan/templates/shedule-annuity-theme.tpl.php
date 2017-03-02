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
      <td><?php print $data['sum'];?> ₽</td>
      <td><?php print $data['percents'];?> ₽</td>
      <td><?php print $items['annuity'];?> ₽</td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td>Итого</td>
      <td></td>
      <td><?php print $items['percents_all'];?> ₽</td>
      <td><?php print $items['annuity_sum'];?> ₽</td>
    </tr>
  </tfoot>
</table>