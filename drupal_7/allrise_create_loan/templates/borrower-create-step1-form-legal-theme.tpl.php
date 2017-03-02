<header class="form-nav">
  <div class="container">
    <div class="profile-application-nav-content">
      <div class="profile-tab-pane" style="display: block;">
        <nav>
          <ul>
            <li><a href="create-loan-and-user/nojs/1" class="use-ajax">
              <span class="ico ico-type-big"></span>
              <h3>Тип<br> Займа</h3></a>
            </li>
            <li><a href="create-loan-and-user/nojs/2" class="use-ajax">
              <span class="ico ico-options-big"></span>
              <h3>Параметры<br> Займа</h3></a>
            </li>
            <li><a href="create-loan-and-user/nojs/3" class="use-ajax">
              <span class="ico ico-loan-data-big"></span>
              <h3>Данные<br> о Залоге</h3></a>
            </li>
            <li class="active">
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
    <div class="form-step profile-tab-pane" style="display: block;">
      <div class="container">
        <div class="form-title">
          <h2>Информация о Заемщике <a href="#" class="tooltip"></a></h2>
          <div class="tooltip-description">
            <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_borrower_info'); ?></div>
          </div>
        </div>
      </div>
      <div class="form-content loan-data other">
        <div class="container">
          <div class="form-item form-type-tabs">
            <div class="form-label">Организационно-правовая форма</div>
            <div class="nav-tabs">
              <ul>
                <li class="active">
                  <b><?php print render($form['type_legal_person']['#value']); ?></b>
                </li>
                <li class="">
                  <?php print render($form['type_physical_person']); ?>
                </li>
              </ul>
            </div>
          </div>
          
            <div class="form-item-title first">
              <h3>Руководитель <a href="#" class="tooltip"></a></h3>
              <div class="tooltip-description">
                <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_legal_chief'); ?></div>
              </div>
            </div>
            <div class="form-item">
              <div class="form-label">Фамилия</div>
              <?php print render($form['surname']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Имя</div>
              <?php print render($form['name']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Отчество</div>
              <?php print render($form['patronymic']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Должность</div>
              <?php print render($form['position']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item ">
              <div class="form-label">Действует на основании</div>
              <?php print render($form['basis']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>

            <div class="form-item-title">
              <h3>Реквизиты компании <a href="#" class="tooltip"></a></h3>
              <div class="tooltip-description">
                <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_legal_company'); ?></div>
              </div>
            </div>
            <h4>Регистрационные данные</h4>
            <div class="form-item">
              <div class="form-label">Название компании согласно устава</div>
              <?php print render($form['corp_name']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">ОРГН</div>
              <?php print render($form['corp_orgn']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">ИНН</div>
              <?php print render($form['corp_inn']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>

            <h4>Юридический адрес</h4>
            <div class="form-item">
              <div class="form-label">Улица, № дома, корпус</div>
              <?php print render($form['street_name']); ?>
              <div class="delimiter"></div>
              <?php print render($form['house_number']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Номер офиса (кабинета, квартиры)</div>
              <?php print render($form['housing']); ?>
              <div class="delimiter"></div>
              <?php print render($form['appartment_adress']); ?>
              <!-- <span class="required">*</span> -->
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Город</div>
              <?php print render($form['city_adress']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Область</div>
              <?php print render($form['region']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Почтовый индекс</div>
              <?php print render($form['postcode']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Страна</div>
              <?php print render($form['country_list']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>

            <h4>Банковский счет</h4>
            <div class="form-item">
              <div class="form-label">Номер счета</div>
              <?php print render($form['account_number']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">ИНН получателя (при наличии)</div>
              <?php print render($form['bank_inn']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">БИК</div>
              <?php print render($form['corp_mfo']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Наименование банка</div>
              <?php print render($form['bank']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Корреспондентский счет банка</div>
              <?php print render($form['bank_account']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>

            <div class="form-actions">
             <!--  <a href="#" class="form-cancel">« Назад</a>
              <input type="submit" class="form-submit" value="Продолжить"> -->
              <?php print render($form['step_back']); ?>
              <?php print render($form['submit']); ?>
            </div>
          
        </div>
      </div>
    </div>
  </div>
</section>

<div style="display: none">
  <?php print drupal_render_children($form); ?>
</div>