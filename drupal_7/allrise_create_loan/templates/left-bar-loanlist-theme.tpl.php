<?php if (isset($items['menu_type']) && $items['menu_type'] == 0) : ?>
  <?php if (isset($items['top_link'])) : ?>
    <?php if ($items['top_link'] == 0) : ?>
      <div class="active new top-link">
        <b>
          <table>
            <tbody><tr>
              <td><b>Новая заявка на займ</b></td>
            </tr>
            <tr>
              <td><strong>заполняется</strong></td>
            </tr>
          </tbody></table>
        </b>
      </div>
    <?php endif;?>
    <?php if ($items['top_link'] == 1) : ?>
      <div class="top-link"><?php print $link = l('Получить новый займ', 'create-loan',  array('attributes' => array('class' => 'new-loan')));?></div>
    <?php endif;?>
    <?php if ($items['top_link'] == 2) : ?>
      <div class="top-link"><b class="new-loan disabled">Получить новый займ</b></div>
    <?php endif;?>
  <?php endif;?>
  <ul>
  <?php foreach ($items['val'] as $key => $val) : ?>
    
    <?php if (isset($val['active_link']) && $val['active_link'] === $val['loan_reference']) : ?>
      <li class="active">
    <?php else :?>
      <li>
    <?php endif; ?>
        <a href="<?php print $val['link'] ;?>">
          <table>
            <tbody><tr>
              <td class="type <?php print $val['pledge_type'] ;?>" rowspan="2">
                <span></span>
              </td>
              <th>Заявка</th>
              <td><b>№ <?php print $val['loan_reference'] ;?></b></td>
            </tr>
            <tr>
              <th>Сумма</th>
              <td><strong><?php print $val['loan_sum'] ;?> ₽</strong></td>
            </tr>
            <tr>
              <td colspan="3" class="description <?php print $val['description_class']?>"><?php print $val['loan_message'] ;?></td>
            </tr>
          </tbody></table>
        </a>
      </li>
  <?php endforeach; ?>
  </ul>
<?php else : ?>
  <?php if (isset($items['top_link'])) : ?>
    <?php if ($items['top_link'] == 0) : ?>
      <div class="active new top-link">
        <b>
          <table>
            <tbody><tr>
              <td><b>Новая заявка на займ</b></td>
            </tr>
            <tr>
              <td><strong>заполняется</strong></td>
            </tr>
          </tbody></table>
        </b>
      </div>
    <?php endif;?>
    <?php if ($items['top_link'] == 1) : ?>
      <div class="top-link"><?php print $link = l('Получить новый займ', 'create-loan',  array('attributes' => array('class' => 'new-loan')));?></div>
    <?php endif;?>
    <?php if ($items['top_link'] == 2) : ?>
      <div class="top-link"><b class="new-loan disabled">Получить новый займ</b></div>
    <?php endif;?>
  <?php endif;?>
<?php endif; ?>