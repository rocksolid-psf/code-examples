<?php global $user;?>
<?php if (($user->uid) > 0) : ?>
  <div class="profile-tab-pane active" style="display: block;">
    <div class="steps-nav">
      <ul>
        <li>1. Тип займа</li>
        <li class="active">2. Параметры займа</li>
        <li>3. Данные о залоге</li>
      </ul>
    </div>
    <div id="loan-calculation-wrapper" class="step-content">
      <div class="step loan-options active">
        <form action="/">
          <div class="clearfix">
            <div class="set-content">
              <div class="title-block">Условия займа</div>
              <div class="set-content-wrapper">
                <table>
                  <tbody>
                    <tr>
                      <th>LTV
                        <a href="#" class="tooltip-hover"></a>
                        <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                          <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_ltv'); ?></div>
                        </div>
                      </th>
                      <td class="LTV"><b>0</b><b>%</b><span><span class="divide-text"> из </span><?php print render($form['LTVMax']['#value']); ?>%</span></td>
                    </tr>
                    <tr>
                      <th>Процент по займу
                        <a href="#" class="tooltip-hover"></a>
                        <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                          <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_percent'); ?></div>
                        </div>
                      </th>
                      <td class="percent-loan"><b>0</b><b>%</b></td>
                    </tr>
                    <tr>
                      <th>Размер займа
                        <a href="#" class="tooltip-hover"></a>
                        <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                          <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_right_sum'); ?></div>
                        </div>
                      </th>
                      <td class="returned"><span>0</span> <span>₽</span></td>
                    </tr>
                    <tr>
                      <th>Комиссия площадки
                        <a href="#" class="tooltip-hover"></a>
                        <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                          <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_commission'); ?></div>
                        </div>
                      </th>
                      <td class="commission"><span>0</span> <span>₽</span></td>
                    </tr>
                    <tr>
                      <th>Сумма к выдаче
                        <a href="#" class="tooltip-hover"></a>
                        <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                          <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_overall_sum'); ?></div>
                        </div>
                      </th>
                      <td class="you-get"><span>0</span><span> ₽</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="schedule-wrapper">
                <span><?php print render($form['shedule_graph']); ?></span>
                <span class="error" style="display:none">Заполните Размер займа и срок займа</span>
              </div>
              <?php print render($form['commision_percent']); ?>
            </div>
            <div class="form-content">
              <div class="form-item">
                <div class="form-label">Рыночная стоимость имущества</div>
                <a href="#" class="tooltip-hover"></a>
                <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                  <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_real_value'); ?></div>
                </div>
                <?php print render($form['market_price']);?>
                <!-- <input type="text" class="form-text" value="344,000 ₽"> -->
              </div>
              <div class="form-item form-item-slider form-item-slider-size">
                <div class="form-label">Размер займа</div>
                <a href="#" class="tooltip-hover"></a>
                <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                  <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_left_sum'); ?></div>
                </div>
                <div id="first_slider" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                  <?php print render($form['loan_size']);?>
                  <input type="hidden" class="pledge_type" value="<?php print render($form['pledge_type']['#value'])?>">
                  <input type="hidden" class="min-value" value="10000">
                  <input type="hidden" class="max-value" value="<?php print render($form['max_sum_value']['#value'])?>">
                  <input type="hidden" class="step" value="1000">
                  <input type="hidden" class="percent" value="2">
                  <input type="hidden" class="currency" value="₽">
                  <input type="hidden" class="max_sum_value" value="<?php print render($form['max_sum_value']['#value'])?>">
                  <input type="hidden" class="LTVMax" value="<?php print render($form['LTVMax']['#value'])?>">
                  <input type="hidden" class="commision_percent" value="<?php print render($form['commision_percent']['#value'])?>">
                  <input type="hidden" class="commision_fixed" value="<?php print render($form['commision_fixed']['#value'])?>">
                  <input type="hidden" class="product_code" value="<?php print render($form['product_code']['#value'])?>">
                <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"><span></span></span></div>
              </div>
              <div class="form-item form-item-slider form-item-slider-time">
                <div class="form-label">Срок займа</div>
                <a href="#" class="tooltip-hover"></a>
                <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                  <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_term'); ?></div>
                </div>
                <div id="second_slider" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                  <?php print render($form['loan_term']);?>
                  <input type="hidden" class="term-value" value="1">
                  <input type="hidden" class="min-value" value="1">
                  <input type="hidden" class="max-value" value="60">
                  <input type="hidden" class="step" value="1">
                  <input type="hidden" class="percent" value="2">
                  <input type="hidden" class="currency" value="мес.">
                <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"><span></span></span></div>
              </div>
              <div class="form-item form-type-select form-item-shedule-type">
                <div class="form-label">Способ погашения</div>
                <a href="#" class="tooltip-hover"></a>
                <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                  <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_payout_method'); ?></div>
                </div>
                <?php print render($form['payout_method']);?>             
              </div>
            </div>
          </div>
          <div id="shedule-graph-wrapper">

          </div>
          <div class="form-actions">
            <?php print render($form['step_back']); ?>
            <?php print render($form['submit']); ?>
          </div>
        </form>
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
              <li class="active">
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
      
      <div class="form-step profile-tab-pane" style="display: block;">
        <div class="container">
          <div class="form-title">
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
            <!-- <h2>Параметры Займа <a href="#" class="tooltip"></a></h2> -->
            <div class="tooltip-description">
              <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
            </div>
          </div>
        </div>
        <div id="loan-calculation-wrapper" class="form-content">
          <div class="loan-options">
            <div class="container">
              <div class="clearfix">
                <div class="set-content">
                  <div class="title-block">Условия займа</div>
                  <div class="set-content-wrapper">
                    <table>
                      <tbody>
                        <tr>
                          <th>LTV
                          <a href="#" class="tooltip-hover"></a>
                          <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                            <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_ltv'); ?></div>
                          </div>
                          </th>
                          <td class="LTV"><b>0</b><b>%</b><span><span class="divide-text"> из </span><?php print render($form['LTVMax']['#value']); ?>%</span></td>
                        </tr>
                        <tr>
                          <th>Процент по займу
                            <a href="#" class="tooltip-hover"></a>
                            <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                              <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_percent'); ?></div>
                            </div>
                          </th>
                          
                          <td class="percent-loan"><b><?php print render($form['loan_percent_id']['#value']);?></b><b>% в год.</b>

                          </td>
                        </tr>
                        <tr>
                          <th>Размер займа
                            <a href="#" class="tooltip-hover"></a>
                            <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                              <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_right_sum'); ?></div>
                            </div>
                          </th>
                          <td class="returned"><span>0</span> <span>₽</span></td>
                        </tr>
                        
                        <tr>
                          <th>Комиссия площадки
                            <a href="#" class="tooltip-hover"></a>
                            <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                              <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_commission'); ?></div>
                            </div>
                          </th>
                          <td class="commission"><span>0</span> <span>₽</span></td>
                        </tr>

                        <tr>
                          <th>Сумма к выдаче
                            <a href="#" class="tooltip-hover"></a>
                            <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                              <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_overall_sum'); ?></div>
                            </div>
                          </th>
                          <td class="you-get"><span>0</span><span> ₽</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="schedule-wrapper">
                    <span><?php print render($form['shedule_graph']); ?></span>
                    <span class="error" style="display:none">Заполните Размер займа и срок займа</span>
               
                  </div>
                  <?php print render($form['commision_percent']); ?>
                </div>
                <div class="form-content">
                  <div class="form-item">
                    <div class="form-label">Рыночная стоимость залогового имущества</div>
                    <a href="#" class="tooltip-hover"></a>
                    <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                      <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_real_value'); ?></div>
                    </div>
                    <?php print render($form['market_price']);?>
                  </div>
                  <div class="form-item form-item-slider form-item-slider-size">
                    <div class="form-label">Размер займа</div>
                    <a href="#" class="tooltip-hover"></a>
                    <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                      <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_left_sum'); ?></div>
                    </div>
                    <div id="first_slider" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                      <?php print render($form['loan_size']);?>
                      <input type="hidden" class="pledge_type" value="<?php print render($form['pledge_type']['#value'])?>">
                      <input type="hidden" class="min-value" value="10000">
                      <input type="hidden" class="max-value" value="<?php print render($form['max_sum_value']['#value'])?>">
                      <input type="hidden" class="step" value="1000">
                      <input type="hidden" class="percent" value="2">
                      <input type="hidden" class="currency" value="₽">
                      <input type="hidden" class="max_sum_value" value="<?php print render($form['max_sum_value']['#value'])?>">
                      <input type="hidden" class="LTVMax" value="<?php print render($form['LTVMax']['#value'])?>">
                      <input type="hidden" class="commision_percent" value="<?php print render($form['commision_percent']['#value'])?>">
                      <input type="hidden" class="commision_fixed" value="<?php print render($form['commision_fixed']['#value'])?>">
                      <input type="hidden" class="product_code" value="<?php print render($form['product_code']['#value'])?>">

                    <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"><span></span></span></div>
                  </div>
                  <div class="form-item form-item-slider form-item-slider-time">
                    <div class="form-label">Срок займа</div>
                    <a href="#" class="tooltip-hover"></a>
                    <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                      <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_term'); ?></div>
                    </div>
                    <div id="second_slider" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                      <?php print render($form['loan_term']);?>
                      <input type="hidden" class="term-value" value="1">
                      <input type="hidden" class="min-value" value="1">
                      <input type="hidden" class="max-value" value="60">
                      <input type="hidden" class="step" value="1">
                      <input type="hidden" class="percent" value="2">
                      <input type="hidden" class="currency" value="мес.">
                    <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"><span></span></span></div>
                  </div>
                  <div class="form-item form-type-select form-item-shedule-type">
                    <div class="form-label">Способ погашения</div>
                    <a href="#" class="tooltip-hover"></a>
                    <div class="tooltip-description-hover" style="overflow: hidden; display: none;">
                      <div class="inner"><?php print allrise_faq_page_info_block_content('create_loan_payout_method'); ?></div>
                    </div>
                    <?php print render($form['payout_method']);?>             
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="shedule-graph-wrapper">

          </div>
          <div class="container">
            <div class="form-actions">
              <?php print render($form['step_back']); ?>
              <?php print render($form['submit']); ?>
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