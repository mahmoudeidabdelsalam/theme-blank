<?php get_header(); ?>
<div class="container">
  <div class="row justify-content-center align-items-center hv-100">
    <div class="col-6 text-center my-5">
      <?php if(is_user_logged_in()): ?>
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn btn-outline-primary">log out</a>
      <?php else: ?>
      <h5>log in</h5>
      <div class="form-group w-100">
        <?php 
          $args = array(
            'echo' => true,
            'remember' => false,
          );
        ?>
        <?php wp_login_form($args); ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>