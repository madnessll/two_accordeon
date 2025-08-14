<section class="recently-my container">
    <h2 class="recently-my__title">Вы уже смотрели</h2>
    <div class="recently-my__cards">
          <?php foreach ( $viewed_products as $product_id ) :
              $product = wc_get_product( $product_id );
              if ( ! $product ) continue;
              
              $image_url = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
              $title = $product->get_name();
              $price = $product->get_price_html();
              $link = $product->get_permalink();
          ?>
              <div class="custom-product-card">
                  <a href="<?php echo esc_url( $link ); ?>" class="custom-product-link">
                      <div class="custom-product-image">
                          <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                      </div>
                      <div class="custom-product-info">
                          <h4 class="custom-product-title"><?php echo esc_html( $title ); ?></h4>
                          <div class="custom-product-price"><?php echo $price; ?></div>
                      </div>
                  </a>
              </div>
          <?php endforeach; ?>
        </div>
</section>