<?php global $user;?>

<!-- (0-Авто,1-Недвижимость,2-Бизнес) -->
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
      <?php foreach ($form['bproduct'] as $k => $val) : ?>
				<div class="step active loan-info">
	        <?php if (is_numeric($k)) : ?>
	        	<?php if ($val['pledge_type']['#value'] == 0) : ?>
	        		<div class="main-content auto">
	              <h2><?php print $val['product_title']['#value'];?></h2>
	        	<?php endif; ?>
	        	<?php if ($val['pledge_type']['#value'] == 1) : ?>
	        		<div class="main-content estate">
	              <h2><?php print $val['product_title']['#value'];?></h2>
	        	<?php endif; ?>
	        	<?php if ($val['pledge_type']['#value'] == 2) : ?>
	        		<div class="main-content finance">
	              <h2><?php print $val['product_title']['#value'];?></h2>
	        	<?php endif; ?>

	              <table class="description">
	                <tbody><tr>
	                  <td>Сумма до <b><?php print $val['end_sum']['#value'];?> &#8381;</b></td>
	                  <td>Ставка <b>от <?php print $val['loan_percent']['#value'];?>% в год</b></td>
	                  <td>Срок <b>до <?php print $val['loan_term']['#value'];?> месяцев</b></td>
	                </tr>
	              </tbody></table>
	              	<?php if ($val['pledge_type']['#value'] == 0) : ?>
	              		<div class="info">Хранение<strong><?php print $val['store_place']['#value'];?></strong></div>
	              	<?php endif; ?>
	              <div class="form-actions">
	                <?php print render($val['submit']); ?>
	                <a href="#" class="toggle-content show">Подробнее</a>
	              	<a href="#" class="toggle-content hide" style="display:none;">Свернуть</a>
	              </div>
	          </div>
	          <div class="other-content">
	              <h3>Описание продукта</h3>
	              <div class="description">
	                <?php print $val['product_description']['#value'];?>
	              </div>
	              <div class="group">
	                <div class="col">
	                  <h3>Требования к заемщику</h3>
	                  <div class="description">
	                    <ul>
	                      <li><?php print $val['borrower_requirements']['#value'];?></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	          </div>
	        <?php endif; ?>
				</div>
      <?php endforeach; ?>
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
            <h2>Тип Займа</h2>
            <div class="tooltip-description">
              <div class="inner"><?php print allrise_faq_page_info_block_content(); ?></div>
            </div>
          </div>
        </div>
        <div class="form-content">
          <div class="container">
	          <?php foreach ($form['bproduct'] as $k => $val) : ?>
	          	<div class="loan-info">
			        <?php if (is_numeric($k)) : ?>
			        	<?php if ($val['pledge_type']['#value'] == 0) : ?>
			        		<div class="main-content auto">
			              <h2><?php print $val['product_title']['#value'];?></h2>
			        	<?php endif; ?>
			        	<?php if ($val['pledge_type']['#value'] == 1) : ?>
			        		<div class="main-content estate">
			              <h2><?php print $val['product_title']['#value'];?></h2>
			        	<?php endif; ?>
			        	<?php if ($val['pledge_type']['#value'] == 2) : ?>
			        		<div class="main-content finance">
			              <h2><?php print $val['product_title']['#value'];?></h2>
			        	<?php endif; ?>

	                <table class="description">
	                  <tr>
	                    <td>Сумма до <b><?php print $val['end_sum']['#value'];?> &#8381;</b></td>
	                    <td>Ставка <b><?php print $val['loan_percent']['#value'];?>% в год</b></td>
	                    <td>Срок <b>до <?php print $val['loan_term']['#value'];?> месяцев</b></td>
	                  </tr>
	                </table>
	                	<?php if ($val['pledge_type']['#value'] == 0) : ?>
	                		<div class="info">Хранение<strong><?php print $val['store_place']['#value'];?></strong></div>
	                	<?php endif; ?>
	                <div class="form-actions">
	                  <?php print render($val['submit']); ?>
	                  <a href="#" class="toggle-content show">Подробнее</a>
	                  <a href="#" class="toggle-content hide" style="display:none;">Свернуть</a>
	                </div>
	              </div>
	              <div class="other-content">
	                <h3>Описание продукта</h3>
	                <div class="description">
	                  <?php print $val['product_description']['#value'];?>
	                </div>
	                <div class="group">
	                  <div class="col">
	                    <h3>Требования к заемщику</h3>
	                    <div class="description">
	                      <ul>
	                        <li><?php print $val['borrower_requirements']['#value'];?></li>
	                      </ul>
	                    </div>
	                  </div>
	                </div>
	              </div>
	              </div>
	            <?php endif; ?>
		     		<?php endforeach; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<div style="display: none">
<?php print drupal_render_children($form); ?>
</div>
