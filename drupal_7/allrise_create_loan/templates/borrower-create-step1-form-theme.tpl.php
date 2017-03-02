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
                <li class="">
                  <?php print render($form['type_legal_person']); ?>
                </li>
                <li class="active">
                  <b><?php print render($form['type_physical_person']['#value']); ?></b>
                </li>
              </ul>
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
            <div class="form-item form-type-radio other">
              <div class="form-label">Пол</div>
              <?php print render($form['gender']); ?>
            </div>
            <div class="form-item form-type-select">
              <div class="form-label">Место рождения</div>
              <?php print render($form['birthplace_country']); ?>
              <div class="delimiter"></div>
              <?php print render($form['birthplace']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item form-type-select">
              <div class="form-label">Дата рождения</div>
              <?php print render($form['birthdate']); ?>
              <span class="required">*</span>
            </div>
            <div class="form-item">
              <div class="form-label">Идентификационный номер</div>
              <?php print render($form['identification_number']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Серия и номер паспорта</div>
              <?php print render($form['passport_series']); ?>
              <div class="delimiter"></div>
              <?php print render($form['passport_data']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item form-type-select">
              <div class="form-label">Дата выдачи</div>
              <?php print render($form['issue_date']); ?>
              <span class="required">*</span>
            </div>
            <div class="form-item form-type-file">
              <div class="form-label small">Скан-копия или фото паспорта</div>
              <?php print render($form['allfiles']); ?>
              <div class="description">Пожалуйста, сфотографируйте и прикрепите<br> изображения всех заполненных страниц паспорта</div>
            </div>
            <div class="form-item-title">
              <h3>Водительское удостоверение</h3>
              <div class="tooltip-description">
                <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
              </div>
            </div>
            <div class="form-item">
              <div class="form-label">Номер водительского удостоверения</div>
              <?php print render($form['license_number']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item form-type-file">
              <div class="form-label small">Фото водительского удостоверения </div>
              <?php print render($form['second_section']); ?>
            </div>

            <h3>Адрес регистрации и проживания</h3>
            <div class="form-item form-type-checkbox">
              <div class="form-label">&nbsp;</div>
              <label>
                <?php print render($form['reg_place_check']); ?>
                <span class="form-name">Адрес прописки совпадает с адресом проживания</span>
              </label>
            </div>

            <div class="registered-address-wrapper">
              <h4>Адрес регистрации</h4>
              <div class="form-item">
                <div class="form-label">Улица, № дома</div>
                <?php print render($form['reg_street_name']); ?>
                <div class="delimiter"></div>
                <?php print render($form['reg_house_number']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Корпус, квартира</div>
                <?php print render($form['reg_housing']); ?>
                <div class="delimiter"></div>
                <?php print render($form['reg_appartment_adress']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Город</div>
                <?php print render($form['reg_city_adress']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Область</div>
                <?php print render($form['reg_region']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Почтовый индекс</div>
                <?php print render($form['reg_postcode']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Страна</div>
                <?php print render($form['reg_country_list']); ?>
                <span class="required">*</span>
              </div>
            </div>

            <div class="residence-address-wrapper">
              <h4>Адрес проживания</h4>
              <div class="form-item">
                <div class="form-label">Улица, № дома</div>
                <?php print render($form['street_name']); ?>
                <div class="delimiter"></div>
                <?php print render($form['house_number']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Корпус, квартира</div>
                <?php print render($form['housing']); ?>
                <div class="delimiter"></div>
                <?php print render($form['appartment_adress']); ?>
                <span class="required">*</span>
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
              </div>
            </div>



            <div class="form-actions">
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
