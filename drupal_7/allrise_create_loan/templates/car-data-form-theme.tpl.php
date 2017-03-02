<?php global $user;?>
<?php if (($user->uid) > 0) : ?>
<div class="profile-tab-pane active" style="display: block;">
  <div class="steps-nav">
    <ul>
      <li>1. Тип займа</li>
      <li>2. Параметры займа</li>
      <li class="active">3. Данные о залоге</li>
    </ul>
  </div>
  <div class="step-content">
    <div class="step loan-data active">
    <!-- other -->
    <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 0) : ?>
      <form action="/">
        <h3>Общая информация</h3>

        <div class="form-content">
          <div class="form-item form-type-select">
            <div class="form-label">Марка</div>
            <?php print render($form['mark_auto']); ?>
          </div>
          <div class="form-item form-type-select">
            <div class="form-label">Модель</div>
            <?php print render($form['model_auto']); ?>
          </div>
          <div class="form-item form-type-select">
            <div class="form-label">Объем двигателя</div>
            <?php print render($form['engine_volume']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Тип топлива</div>
            <?php print render($form['fuel_type']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Пробег (км)</div>
            <?php print render($form['mileage']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Год выпуска</div>
            <?php print render($form['production_year']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Цвет</div>
            <?php print render($form['auto_color']); ?>            
          </div>
          <div class="form-item">
            <div class="form-label">VIN</div>
            <?php print render($form['VIN']); ?>       
          </div>
          <div class="form-item">
            <div class="form-label">Госномер автомобиля</div>
            <?php print render($form['auto_number']); ?>               
          </div>
          <div class="form-item">
            <div class="form-label">Номер техпаспорта</div>
            <?php print render($form['tp_number']); ?>            
          </div>
          <div class="form-item">
            <div class="form-label">Описание состояния автомобиля</div>
            <?php print render($form['auto_description']); ?>    
          </div>
        </div>

        <h3>Страховка</h3>
        <div class="form-content">
          <div class="form-item">
            <div class="form-label">Наличие КАСКО</div>
            <?php print render($form['casco']); ?> 
          </div>
          <div class="form-item">
            <div class="form-label">Наличие ОСАГО</div>
            <?php print render($form['osago']); ?>  
          </div>
        </div>

        <h3>Опционально</h3>
        <div class="text-content">Седан Linea выпускается с 2007 года, а в 2012-м обновился (на фото рестайлинговый вариант). Производство было налажено в Турции, Бразилии и Индии.Некоторое время дореформенную модель собирали и в России (Набережные Челны), но у нас она, что называется, не пошла.</div>
        <div class="form-content">
          <div class="form-item form-type-file">
            <div class="form-label small">Фотография автомобиля (интерьер, внешний вид)</div>
              <?php print render($form['allfiles']); ?>
            <div class="form-label small">&nbsp;</div>            
          </div>
          <div class="form-item form-type-file">
            <div class="form-label small">Фото техпаспорта</div>
            <?php print render($form['second_section']); ?>            
          </div>
        </div>

        <div class="form-actions">
          <?php print render($form['step_back']); ?>
          <?php print render($form['submit']); ?>
        </div>
      </form>
    <?php endif; ?>
    <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 1) : ?>
      <h3>Общая информация</h3>

        <div class="form-content">
          <div class="form-item form-type-select">
            <div class="form-label">Тип недвижимости</div>
            <?php print render($form['estate_type']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Метраж помещения</div>
            <?php print render($form['footage_premises']); ?>    
          </div>

          <h3>Адрес помещения</h3>
          <div class="form-item">
            <div class="form-label">Улица, № дома</div>
            <?php print render($form['estate_street_name']); ?>
            <div class="delimiter"></div>
            <?php print render($form['estate_house_number']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Корпус, квартира</div>
            <?php print render($form['estate_housing']); ?>
            <div class="delimiter"></div>
            <?php print render($form['estate_appartment_adress']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Город</div>
            <?php print render($form['estate_city_adress']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Область</div>
            <?php print render($form['estate_region']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Почтовый индекс</div>
            <?php print render($form['estate_postcode']); ?>
          </div>
          <div class="form-item">
            <div class="form-label">Страна</div>
            <?php print render($form['estate_country_list']); ?>
          </div>

          <div class="form-item form-type-file">
            <div class="form-label">Фотография недвижимости (интерьер, экстерьер, прилегающая територия)</div>
              <?php print render($form['allfiles']); ?>
            <div class="form-label small">&nbsp;</div>            
          </div>
        </div>


        <div class="form-actions">
          <?php print render($form['step_back']); ?>
          <?php print render($form['submit']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 2) : ?>
      <h3>Общая информация</h3>

      <div class="form-content">
        <div class="form-item form-type-select">
          <div class="form-label">Описание сделки</div>
          <?php print render($form['deal_description']); ?>
        </div>
        <div class="form-item">
          <div class="form-label">Описание залога</div>
          <?php print render($form['loan_estate_description']); ?>    
        </div>
        <div class="form-item form-type-file">
          <div class="form-label">Прикрепите файлы</div>
          <?php print render($form['second_section']); ?>
        </div>
      </div>
      <div class="form-actions">
        <?php print render($form['step_back']); ?>
        <?php print render($form['submit']); ?>
      </div>
    <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (($user->uid) == 0) : ?>
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
              <li class="active">
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
      <div class="form-step profile-tab-pane" style="display: block;">
        <div class="container">
          <div class="form-title">
            <!-- <h2>Данные о Залоге <a href="#" class="tooltip"></a></h2> -->
            <?php if(isset($_SESSION['loan']['bank_product'])) :?>
            <?php if ($_SESSION['loan']['bank_product'] == 0) : ?>
              <h2>Тип займа: <?php print $_SESSION['create_loan']['bank_product_title']; ?></h2>
            <?php endif; ?>
            <?php if ($_SESSION['loan']['bank_product'] == 1) : ?>
              <h2>Тип займа: <?php print $_SESSION['create_loan']['bank_product_title']; ?></h2>
            <?php endif; ?>
            <?php if ($_SESSION['loan']['bank_product'] == 2) : ?>
              <h2>Тип займа: <?php print $_SESSION['create_loan']['bank_product_title']; ?></h2>
            <?php endif; ?>
          <?php endif; ?>
            <div class="tooltip-description">
              <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
            </div>
          </div>
        </div>
        <div class="form-content loan-data">
          <!-- other -->
          <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 0) : ?>
          <div class="container">
            <form action="/">
              <div class="form-item form-type-select">
                <div class="form-label">Марка</div>
                <?php print render($form['mark_auto']); ?>
              </div>
              <div class="form-item form-type-select">
                <div class="form-label">Модель</div>
                <?php print render($form['model_auto']); ?>
                <?php print render($form['model_auto2']); ?>
              </div>
              <div class="form-item form-type-select">
                <div class="form-label">Объем двигателя</div>
                <?php print render($form['engine_volume']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Тип топлива</div>
                <?php print render($form['fuel_type']); ?>
              </div>
              <div class="form-item">
                <div class="form-label">Пробег (км)</div>
                <?php print render($form['mileage']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Год выпуска</div>
                <?php print render($form['production_year']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Цвет</div>
                <?php print render($form['auto_color']); ?>   
                <span class="required">*</span>
                <span class="ico"></span>         
              </div>
              <div class="form-item">
                <div class="form-label">VIN</div>
                <?php print render($form['VIN']); ?>  
                <span class="required">*</span>
                <span class="ico"></span>     
              </div>
              <div class="form-item">
                <div class="form-label">Госномер автомобиля</div>
                <?php print render($form['auto_number']); ?>   
                <span class="required">*</span>
                <span class="ico"></span>            
              </div>
              <div class="form-item">
                <div class="form-label">Номер техпаспорта</div>
                <?php print render($form['tp_number']); ?>    
                <span class="required">*</span>
                <span class="ico"></span>        
              </div>
              <div class="form-item">
                <div class="form-label">Описание состояния автомобиля</div>
                <?php print render($form['auto_description']); ?>    
               <!--  <span class="required">*</span>
                <span class="ico"></span> -->
              </div>
              <div class="form-item">
                <div class="form-label">Наличие КАСКО</div>
                <?php print render($form['casco']); ?> 
              </div>
              <div class="form-item">
                <div class="form-label">Наличие ОСАГО</div>
                <?php print render($form['osago']); ?>  
              </div>
              <div class="form-item form-type-file">
                <div class="form-label small">Фото Автомобиля</div>
                <?php print render($form['allfiles']); ?>   
                <!-- <div class="description">Пожалуйста, сфотографируйте и прикрепите изображения вашего автомобиля<br> (интерьер, экстерьер, основные отличительные черты)</div> -->
              </div>
              <div class="form-item form-type-file">
                <div class="form-label small">Фото техпаспорта</div>
                <?php print render($form['second_section']); ?>
                <!-- <div class="description">Пожалуйста, сфотографируйте и прикрепите изображения вашего автомобиля<br> (интерьер, экстерьер, основные отличительные черты)</div> -->
              </div>
              <div class="form-actions">
                <!-- <a href="#" class="form-cancel">« Назад</a>
                <input type="submit" class="form-submit" value="Продолжить"> -->
                <?php print render($form['step_back']); ?>
                <?php print render($form['create_and_register']); ?>
              </div>
            </form>
          </div>
          <?php endif; ?>
          <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 1) : ?>
            <div class="container">
              <div class="form-item form-type-select">
                <div class="form-label">Тип недвижимости</div>
                <?php print render($form['estate_type']); ?>
              </div>
              <div class="form-item">
                <div class="form-label">Метраж помещения</div>
                <?php print render($form['footage_premises']); ?>    
              </div>

              <h4>Адрес помещения</h4>
              <div class="form-item">
                <div class="form-label">Улица, № дома</div>
                <?php print render($form['estate_street_name']); ?>
                <div class="delimiter"></div>
                <?php print render($form['estate_house_number']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Корпус, квартира</div>
                <?php print render($form['estate_housing']); ?>
                <div class="delimiter"></div>
                <?php print render($form['estate_appartment_adress']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Город</div>
                <?php print render($form['estate_city_adress']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Область</div>
                <?php print render($form['estate_region']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Почтовый индекс</div>
                <?php print render($form['estate_postcode']); ?>
                <span class="required">*</span>
                <span class="ico"></span>
              </div>
              <div class="form-item">
                <div class="form-label">Страна</div>
                <?php print render($form['estate_country_list']); ?>
                <span class="required">*</span>
              </div>
              <div class="form-item form-type-file">
                <div class="form-label estate">Фотография недвижимости (интерьер, экстерьер, прилегающая територия)</div>
                  <?php print render($form['allfiles']); ?>
                <div class="form-label small">&nbsp;</div>            
              </div>
              <div class="form-actions">
                <?php print render($form['step_back']); ?>
                <?php print render($form['create_and_register']); ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if(isset($_SESSION['loan']['bank_product']) && $_SESSION['loan']['bank_product'] == 2) : ?>
            <div class="container">
              <div class="form-item form-type-select">
                <div class="form-label">Описание сделки</div>
                <?php print render($form['deal_description']); ?>
              </div>
              <div class="form-item">
                <div class="form-label">Описание залога</div>
                <?php print render($form['loan_estate_description']); ?>    
              </div>
              <div class="form-item form-type-file">
                <div class="form-label small">Прикрепите файлы</div>
                <?php print render($form['second_section']); ?>
              </div>
              <div class="form-actions">
                <?php print render($form['step_back']); ?>
                <?php print render($form['create_and_register']); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<div style="display: none">
  <?php print drupal_render_children($form); ?>
</div>