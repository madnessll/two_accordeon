<?php

add_action( 'template_redirect', 'truemisha_recently_viewed_product_cookie', 20 );
function truemisha_recently_viewed_product_cookie() {
    if ( ! is_product() || wp_doing_ajax() ) {
        return;
    }
    
    global $post;
    $current_product_id = $post->ID;
    
    // Получаем текущие просмотренные товары
    if ( empty( $_COOKIE[ 'woocommerce_recently_viewed_2' ] ) ) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode( '|', wp_unslash( $_COOKIE[ 'woocommerce_recently_viewed_2' ] ) );
        $viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
    }
    
    // Убираем текущий товар из массива если он там есть (чтобы переместить в конец)
    $viewed_products = array_diff( $viewed_products, array( $current_product_id ) );
    
    // Добавляем текущий товар в конец
    $viewed_products[] = $current_product_id;
    
    // Ограничиваем количество
    if ( sizeof( $viewed_products ) > 15 ) {
        $viewed_products = array_slice( $viewed_products, -15 );
    }
    
    // Устанавливаем куки на 30 дней
    wc_setcookie( 'woocommerce_recently_viewed_2', implode( '|', $viewed_products ), time() + ( 30 * 24 * 60 * 60 ) );
}



add_shortcode( 'recently_viewed_products', 'truemisha_recently_viewed_products' );
 
function truemisha_recently_viewed_products() {
    if( empty( $_COOKIE[ 'woocommerce_recently_viewed_2' ] ) ) {
        return '';
    }
    
    $viewed_products = (array) explode( '|', wp_unslash( $_COOKIE[ 'woocommerce_recently_viewed_2' ] ) );
    $viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
    
    if ( empty( $viewed_products ) ) {
        return '';
    }
    
    // Убираем текущий товар из списка (если находимся на странице товара)
    if ( is_product() ) {
        global $post;
        $viewed_products = array_diff( $viewed_products, array( $post->ID ) );
    }
    
    // Берем последние 5 (массив уже в правильном порядке - последние в конце)
    $viewed_products = array_slice( $viewed_products, -5 );
    
    // Переворачиваем для отображения (последние первыми)
    $viewed_products = array_reverse( $viewed_products );
    
    if ( empty( $viewed_products ) ) {
        return '';
    }
    
   ob_start(); ?>
    
    
<section class="recently-my">
    <h2 class="recently-my__title">Вы уже смотрели</h2>
    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider>
        <div class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
            <?php foreach( $viewed_products as $product_id ) :
                $product = wc_get_product( $product_id );
                if( ! $product ) continue;

                $image_url = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
                $title = $product->get_name();
                $price = $product->get_price_html();
                $link = $product->get_permalink();
            ?>
                <li class="products columns-3 li single-product product">
                    <a href="<?php echo esc_url( $link ); ?>" class="product-card__link">
                        <div class="product-card__media">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                        </div>
                        <div class="product-card__content">
                            <h2 class="product-card__title"><?php echo esc_html( $title ); ?></h2>
                            <div class="product-card__price"><?php echo $price; ?></div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
</section>


    
    <?php
    return ob_get_clean();

            }

?>

<?php /* ?>


<div class="filter-accordion">
            <div class="filter-accordion__header">Категории</div>
            <div class="filter-accordion__panel">
              <?php
              $parents = get_terms([ 'taxonomy'=>'product_cat','parent'=>0,'hide_empty'=>true ]);
              $sel_cat = (array) ( $_GET['filter_cat'] ?? [] );
              foreach ( $parents as $pc ) : ?>
                <label>
                  <input type="checkbox" name="filter_cat[]"
                         value="<?php echo esc_attr( $pc->term_id ); ?>"
                         <?php checked( in_array( $pc->term_id, $sel_cat, true ) ); ?>>
                  <?php echo esc_html( $pc->name ); ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

<?php */ ?>

