<?php
if ( ! class_exists( 'Wpx_Image_Bg' ) && defined( 'ABSPATH' ) ) {

	/**
	 * Class Wpx_Image_Bg
	 */
	class Wpx_Image_Bg {

		function __construct() {
			self::set_filters();
		}

		function set_filters() {
			//Init Function
			add_action( 'init', array( $this, 'wpx_imagebg_init' ) );

			//Enqueue Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'wpx_imagebg_scripts' ) );

			//Add the opening div to the img
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wpx_add_img_wrapper_start' ), 5, 2 );
			//Close the div that we just added
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wpx_add_img_wrapper_close' ), 12, 2 );

			//Cart Image Filter
			add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'wpx_cart_image_to_bg' ), 10, 3 );

			//Resize Thumbnail Images
			add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array(
				$this,
				'wpx_get_image_size_gallery_thumbnail'
			) );
			// Donate notice.
			add_action( 'admin_notices', array( $this, 'wpx_donate_notice' ) );

		}
		function wpx_donate_notice() {
			$wc_page = array( 'wc-admin', 'wc-reports', 'wc-settings', 'wc-status', 'wc-addons' );
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $wc_page, true )
			|| isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], array( 'shop_order',  ) ) ) {
				?>	
				<div class="notice notice-success is-dismissible">
					<p>
					<?php esc_html_e( 'Thanks for installing  WC Catalog images to DIV convertor. You can support my work by donating.', 'gf-retrigger' ); ?>
					<a target="__blank" href="https://wpspins.com/support-our-work/"><?php esc_html_e( 'Click this banner to see donation form', 'gf-retrigger' ); ?></a>
					</p>
				</div>
				<?php
			}
		}
		/**
		 * Init Function
		 */
		function wpx_imagebg_init() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				function wpx_ibg_admin_notice__error() {
					$class   = 'notice notice-error';
					$message = __( 'WooCommerce is not found. Please install/activate WooCommerce to use "WPX - Images to BG" plugin', 'wpxhouston' );

					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}

				add_action( 'admin_notices', 'wpx_ibg_admin_notice__error' );
			}
		}

		/**
		 * Enqueue Scripts
		 */
		function wpx_imagebg_scripts() {
			wp_enqueue_style( 'wpx-image-bg', plugins_url( '../assets/wpx-image-bg.css', __FILE__ ) );
			if ( is_product() ) {
				if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
					wp_enqueue_script( "jquery" );
				}
				wp_enqueue_script( 'wpx-image-bg', plugins_url( '../assets/wpx-image-bg.js', __FILE__ ), array(), false, true );
			}
		}

		/**
		 * Add the opening div to the img
		 */
		function wpx_add_img_wrapper_start() {
			global $product, $woocommerce_loop;
			$product_id = $product->get_id();
			if ( empty( $woocommerce_loop['columns'] ) ) {
				$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 5 );
			}
			$wpx_class = 'wpx-shop-' . $woocommerce_loop['columns'] . '-image';
			echo '<div class="' . $wpx_class . '" style="background-image:url(' . wp_get_attachment_image_src( get_post_thumbnail_id( $product_id  ), 'single-post-thumbnail' )[0] . ')">';
		}

		/**
		 * Close the div that we just added
		 */
		function wpx_add_img_wrapper_close() {
			echo '</div>';
		}

		/**
		 * Cart Image Filter
		 *
		 * @param $image
		 * @param $cart_item
		 * @param $cart_item_key
		 *
		 * @return string
		 */
		function wpx_cart_image_to_bg( $image, $cart_item, $cart_item_key ) {
			$product_id = $cart_item['variation_id'] == 0 ? $cart_item['product_id'] : $cart_item['variation_id'];

			return '<div class="wpx-cart-image" style="background-image:url(' . get_the_post_thumbnail_url( $product_id ) . ')">' . $image . '</div>';
		}

		/**
		 * Resize Thumbnail Images
		 *
		 * @param $size
		 *
		 * @return int[]
		 */
		function wpx_get_image_size_gallery_thumbnail( $size ) {
			return array(
				'width'  => 1000,
				'height' => 1000,
				'crop'   => 0,
			);
		}

	}

	/**
	 * Instantiate the Wpx_Image_Bg loader class.
	 *
	 * @since 2.0
	 */
	new Wpx_Image_Bg();
}