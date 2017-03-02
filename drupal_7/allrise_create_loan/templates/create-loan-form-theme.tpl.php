<?php global $user;?>
<?php if (($user->uid) > 0) : ?>
<div class="profile-tab-pane active" style="display: block;">
  <div class="steps-nav">
    <ul>
      <li class="active">1. Тип займа</li>
      <li>2. Параметры займа</li>
      <li>3. Данные о залоге</li>
    </ul>
  </div>
  <div class="step-content">
    <div class="step active loan-info">
      <div class="main-content auto">
        <h2>Под залог автомобиля</h2>
        <table class="description">
          <tbody><tr>
            <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
            <td>Ставка <b>от <?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
            <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
          </tr>
        </tbody></table>
        <div class="info">Хранение автомобиля <strong><?php print $form['store_place']['#value'];?></strong></div>
        <div class="form-actions">
          <?php print render($form['submit']); ?>
          <a href="#" class="toggle-content show">Подробнее</a>
        </div>
      </div>
      <div class="other-content">
        <h3>Описание продукта</h3>
        <div class="description">
          <?php print $form['product_description']['#value'];?>
        </div>
        <div class="group">
          <div class="col">
            <h3>Подробные условия</h3>
            <div class="description">
              <ul>
                <li><?php print $form['detailed_conditions']['#value'];?></li>
              </ul>
            </div>
          </div>
          <div class="col">
            <h3>Требования к заемщику</h3>
            <div class="description">
              <ul>
                <li><?php print $form['borrower_requirements']['#value'];?></li>
              </ul>
            </div>
          </div>
        </div>
        <a href="#" class="toggle-content hide">Свернуть</a>
      </div>
      <!-- other -->
      <div class="main-content auto">
        <h2>Под залог недвижимости</h2>
        <table class="description">
          <tbody><tr>
            <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
            <td>Ставка <b>от <?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
            <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
          </tr>
        </tbody></table>
        <div class="info">Хранение автомобиля <strong><?php print $form['store_place']['#value'];?></strong></div>
        <div class="form-actions">
          <?php print render($form['submit_estate']); ?>
          <a href="#" class="toggle-content show">Подробнее</a>
        </div>
      </div>
      <div class="other-content">
        <h3>Описание продукта</h3>
        <div class="description">
          <?php print $form['product_description']['#value'];?>
        </div>
        <div class="group">
          <div class="col">
            <h3>Подробные условия</h3>
            <div class="description">
              <ul>
                <li><?php print $form['detailed_conditions']['#value'];?></li>
              </ul>
            </div>
          </div>
          <div class="col">
            <h3>Требования к заемщику</h3>
            <div class="description">
              <ul>
                <li><?php print $form['borrower_requirements']['#value'];?></li>
              </ul>
            </div>
          </div>
        </div>
        <a href="#" class="toggle-content hide">Свернуть</a>
      </div>
      <!-- other -->
      <div class="main-content auto">
        <h2>Финансирование сделки</h2>
        <table class="description">
          <tbody><tr>
            <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
            <td>Ставка <b>от <?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
            <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
          </tr>
        </tbody></table>
        <div class="info">Хранение автомобиля <strong><?php print $form['store_place']['#value'];?></strong></div>
        <div class="form-actions">
          <?php print render($form['submit_financial']); ?>
          <a href="#" class="toggle-content show">Подробнее</a>
        </div>
      </div>
      <div class="other-content">
        <h3>Описание продукта</h3>
        <div class="description">
          <?php print $form['product_description']['#value'];?>
        </div>
        <div class="group">
          <div class="col">
            <h3>Подробные условия</h3>
            <div class="description">
              <ul>
                <li><?php print $form['detailed_conditions']['#value'];?></li>
              </ul>
            </div>
          </div>
          <div class="col">
            <h3>Требования к заемщику</h3>
            <div class="description">
              <ul>
                <li><?php print $form['borrower_requirements']['#value'];?></li>
              </ul>
            </div>
          </div>
        </div>
        <a href="#" class="toggle-content hide">Свернуть</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if (($user->uid) == 0) : ?>
  <header class="form-nav">
    <div class="container">
      <div class="profile-application-nav-content">
        <div class="profile-tab-pane">
          <nav>
            <ul>
              <li class="active">
                <span class="ico ico-type-big"></span>
                <h3>Тип<br> Займа</h3>
              </li>
              <li>
                <span class="ico ico-options-big"></span>
                <h3>Параметры<br> Займа</h3>
              </li>
              <li>
                <span class="ico ico-loan-data-big"></span>
                <h3>Данные<br> о Залоге</h3>
              </li>
              <li>
                <span class="ico ico-user-info-big"></span>
                <h3>Информация<br> о Заемщике</h3>
              </li>
              <li>
                <span class="ico ico-profile-info-big"></span>
                <h3>Данные<br> Учетной Записи</h3>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <section class="form">
    <div class="profile-application-nav-content">
      <div class="form-step profile-tab-pane">
        <div class="container">
          <div class="form-title">
            <h2>Тип Займа <a href="#" class="tooltip"></a></h2>
            <div class="tooltip-description">
              <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
            </div>
          </div>
        </div>
        <div class="form-content">
          <div class="container">
            <div class="loan-info">
              <div class="main-content auto">
                <h2>Под залог автомобиля</h2>
                <table class="description">
                  <tr>
                    <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
                    <td>Ставка <b><?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
                    <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
                  </tr>
                </table>
                <div class="info">Хранение автомобиля <strong>на стоянке Allrise</strong></div>
                <div class="form-actions">
                  <?php print render($form['submit']); ?>
                  <a href="#" class="toggle-content show">Подробнее</a>
                </div>
              </div>
              <div class="other-content">
                <h3>Описание продукта</h3>
                <div class="description">
                  <?php print $form['product_description']['#value'];?>
                </div>
                <div class="group">
                  <div class="col">
                    <h3>Подробные условия</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['detailed_conditions']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col">
                    <h3>Требования к заемщику</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['borrower_requirements']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <a href="#" class="toggle-content hide">Свернуть</a>
              </div>
              <!-- other -->
              <div class="main-content auto">
                <h2>Под залог недвижимости</h2>
                <table class="description">
                  <tr>
                    <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
                    <td>Ставка <b><?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
                    <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
                  </tr>
                </table>
                <div class="info">Залог недвижимости Allrise</strong></div>
                <div class="form-actions">
                  <?php print render($form['submit_estate']); ?>
                  <a href="#" class="toggle-content show">Подробнее</a>
                </div>
              </div>
              <div class="other-content">
                <h3>Описание продукта</h3>
                <div class="description">
                  <?php print $form['product_description']['#value'];?>
                </div>
                <div class="group">
                  <div class="col">
                    <h3>Подробные условия</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['detailed_conditions']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col">
                    <h3>Требования к заемщику</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['borrower_requirements']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <a href="#" class="toggle-content hide">Свернуть</a>
              </div>
              <!-- other -->
              <div class="main-content auto">
                <h2>Финансирование сделки</h2>
                <table class="description">
                  <tr>
                    <td>Сумма до <b><?php print $form['end_sum']['#value'];?> &#8381;</b></td>
                    <td>Ставка <b><?php print $form['loan_percent']['#value'];?>% в месяц</b></td>
                    <td>Срок <b>до <?php print $form['loan_term']['#value'];?> месяцев</b></td>
                  </tr>
                </table>
                <div class="info">Залог финансовой сделки Allrise</strong></div>
                <div class="form-actions">
                  <?php print render($form['submit_financial']); ?>
                  <a href="#" class="toggle-content show">Подробнее</a>
                </div>
              </div>
              <div class="other-content">
                <h3>Описание продукта</h3>
                <div class="description">
                  <?php print $form['product_description']['#value'];?>
                </div>
                <div class="group">
                  <div class="col">
                    <h3>Подробные условия</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['detailed_conditions']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col">
                    <h3>Требования к заемщику</h3>
                    <div class="description">
                      <ul>
                        <li><?php print $form['borrower_requirements']['#value'];?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <a href="#" class="toggle-content hide">Свернуть</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<div style="display: none">
<?php print drupal_render_children($form); ?>
</div>