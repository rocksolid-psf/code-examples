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
            <li><a href="create-loan-and-user/nojs/4" class="use-ajax">
              <span class="ico ico-user-info-big"></span>
              <h3>Информация<br> о Заемщике</h3></a>
            </li>
            <li class="active">
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
          <h2>Данные учетной записи для доступа в систему</h2>
          <div class="tooltip-description">
            <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
          </div>
        </div>
      </div>
      <div class="form-content loan-data other">
        <div class="container">
          <form action="/">
            <div class="form-item e-mail">
              <div class="form-label">E-mail</div>
              <?php print render($form['email']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
              <div class="field-answer" style="display: none">e-mail уже зарегистрирован в системе</div>
              <!-- <div class="error">Ошибка</div> -->
            </div>
            <div class="form-item mob-phone">
              <div class="form-label">Моб. телефон</div>
              <span class="country-ico ru"></span>
              <?php print render($form['country_code']); ?> 
              <div class="delimiter">-</div>
              <?php print render($form['phone_code']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
              <div class="field-answer" style="display: none">Телефон уже зарегистрирован в системе</div>
            </div>
            <div class="form-item">
              <div class="form-label">Рабочий телефон</div>
              <?php print render($form['work_phone_code']); ?>
              <div class="delimiter">-</div>
              <?php print render($form['work_phone_num']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Кодовое слово</div>
              <?php print render($form['secret_word']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Пароль</div>
              <?php print render($form['password']); ?>
              <span class="required">*</span>
              <span class="ico"></span>
              <!-- <a href="#" class="ico show-password"></a> -->
            </div>
            <div class="form-item">
              <div class="form-label">Подтвердить пароль</div>
              <?php print render($form['password_confirm']); ?>
              <span class="required">*</span>
              <span class="ico show-password"></span>
            </div>
            <div class="form-item">
              <div class="form-label">Код партнера(не обязательно)</div>
              <?php print render($form['partner_code']); ?>
            </div>
            <div class="form-item-title">
              <h3>Пользовательское соглашение</h3>
              <div class="tooltip-description" style="overflow: hidden; display: none;">
                <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
              </div>
            </div>
            <div class="form-item ">
              <div class="form-label">&nbsp;</div>
              <?php print render($form['user_agreement']); ?>
            </div>
            <div class="form-item form-type-checkbox">
              <div class="form-label">&nbsp;</div>
              <?php print render($form['confirmation1']); ?>
            </div>
            <div class="form-item form-type-checkbox">
              <div class="form-label">&nbsp;</div>
              <?php print render($form['confirmation2']); ?>
            </div>
            <div class="form-item form-type-checkbox">
              <div class="form-label">&nbsp;</div>
              <?php print render($form['confirmation3']); ?>
            </div>
            <div class="form-actions">
              <?php print render($form['step_back']); ?>
              <?php print render($form['submit']); ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div style="display: none">
  <?php print drupal_render_children($form); ?>
</div>