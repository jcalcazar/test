<div class="<?php print $classes ?>" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['top_left'] || $content['top_right']): ?>
    <div class="row top_container">
	<div class="v-container">
	  <article class="col-sm-8">
      <?php print $content['top_left']; ?>
	  </article>

	  <article class="col-sm-4">
      <?php print $content['top_right']; ?>
	  </article>	  
	  </div>
    </div>
  <?php endif ?>

  <?php if ($content['middle_left'] || $content['middle_center'] || $content['middle_right']): ?>
    <div class="row middle_container"> <!-- @TODO: Add extra classes -->
	<div class="v-container">
	<article class="col-sm-4">
      <?php print $content['middle_left']; ?>
	  </article>
	  <article class="col-sm-4">
      <?php print $content['middle_center']; ?>
	  </article>
	  <article class="col-sm-4">
      <?php print $content['middle_right']; ?>
	  </article>
    </div>
  <?php endif ?>

  <?php if ($content['bottom']): ?>
    <div class="row">
	<div class="v-container">
	<article class="col-sm-12">
      <?php print $content['bottom']; ?>
	</article>
    </div>
	</div>
  <?php endif ?>
</div>
