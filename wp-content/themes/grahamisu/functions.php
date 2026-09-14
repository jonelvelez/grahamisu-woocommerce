<?php

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_filter( 'woocommerce_show_page_title', '__return_false' );

add_filter( 'woocommerce_add_to_cart_fragments', 'grahamisu_cart_count_fragment' );
function grahamisu_cart_count_fragment( $fragments ) {
    $count   = WC()->cart->get_cart_contents_count();
    $content = $count > 0 ? esc_html( $count ) : '';
    $fragments['span.cart-count'] = '<span class="cart-count">' . $content . '</span>';
    return $fragments;
}

function grahamisu_assets() {
    wp_enqueue_style(
        'grahamisu-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Kaushan+Script&family=Cormorant+Garamond:ital,wght@1,500;1,700&family=Lato:ital,wght@0,400;0,600;0,700;1,700&family=Inter:wght@400;600&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'grahamisu-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array( 'grahamisu-fonts' ),
        '2.3.0'
    );

    wp_enqueue_script(
        'grahamisu-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'grahamisu_assets' );

// ── Performance: preconnect for Google Fonts ──────────────────────────────────
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

// ── Performance: non-blocking Google Fonts + defer theme script ───────────────
add_filter( 'style_loader_tag', function( $html, $handle ) {
    if ( is_admin() ) return $html;

    // Convert Google Fonts to preload so it doesn't block render
    if ( 'grahamisu-fonts' === $handle ) {
        preg_match( '/href=["\']([^"\']+)["\']/', $html, $m );
        if ( ! empty( $m[1] ) ) {
            $href = esc_url( $m[1] );
            return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n"
                 . '<noscript>' . $html . '</noscript>' . "\n";
        }
    }

    // Preload mobile-only WooCommerce CSS — not needed for initial render
    if ( 'woocommerce-smallscreen' === $handle ) {
        preg_match( '/href=["\']([^"\']+)["\']/', $html, $m );
        if ( ! empty( $m[1] ) ) {
            $href = esc_url( $m[1] );
            return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n"
                 . '<noscript>' . $html . '</noscript>' . "\n";
        }
    }

    // Preload WC layout CSS on non-WooCommerce pages (homepage, gallery, etc.)
    // WC pages (shop, cart, checkout, product) still load synchronously
    $wc_non_critical = [ 'woocommerce-layout', 'woocommerce-general' ];
    if ( in_array( $handle, $wc_non_critical, true ) && ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
        preg_match( '/href=["\']([^"\']+)["\']/', $html, $m );
        if ( ! empty( $m[1] ) ) {
            $href = esc_url( $m[1] );
            return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n"
                 . '<noscript>' . $html . '</noscript>' . "\n";
        }
    }

    return $html;
}, 10, 2 );

// Defer all frontend scripts — defer preserves execution order so jQuery → WC chain is safe
add_filter( 'script_loader_tag', function( $tag, $handle ) {
    if ( is_admin() ) return $tag;
    if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) return $tag;
    return str_replace( '<script ', '<script defer ', $tag );
}, 10, 2 );

// ── Performance: dequeue Rank Math frontend CSS — no breadcrumbs or contact widget used ──
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'rank-math' );
    wp_dequeue_style( 'rank-math-seo-score' );
}, 100 );

// ── Performance: dequeue WC Blocks CSS — theme uses classic templates ─────────
add_action( 'wp_enqueue_scripts', function() {
    if ( is_admin() ) return;
    $wc_block_styles = [
        'wc-blocks-style',
        'wc-blocks-vendors-style',
        'wc-blocks-editor-style',
    ];
    foreach ( $wc_block_styles as $handle ) {
        wp_dequeue_style( $handle );
        wp_deregister_style( $handle );
    }
}, 100 );

// Force woocommerce.php for checkout + order-received — bypasses WooCommerce Blocks / page.php
add_filter( 'template_include', function( $template ) {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_checkout() || is_account_page() ) ) {
        $wc_template = locate_template( 'woocommerce.php' );
        if ( $wc_template ) {
            return $wc_template;
        }
    }
    return $template;
}, 99 );

// Remove default WooCommerce order-details table on thank-you page — we render our own
add_action( 'wp', function() {
    remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
} );

// Apply Tailwind classes to primary nav links and list items
add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {
    $link_class = "font-['Inter'] font-semibold text-xl text-white no-underline whitespace-nowrap hover:text-gold";
    if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
        $atts['class'] = $link_class;
    }
    return $atts;
}, 10, 4 );

add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
        return array( 'list-none p-0 m-0' );
    }
    return $classes;
}, 10, 4 );

function grahamisu_setup() {
    add_theme_support( 'custom-logo' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'navigation-widgets' ) );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'grahamisu' ),
        'footer'  => __( 'Footer Menu', 'grahamisu' ),
    ) );
}
add_action( 'after_setup_theme', 'grahamisu_setup' );

function grahamisu_widgets() {
    register_sidebar( array(
        'name'          => __( 'Footer', 'grahamisu' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget__title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'grahamisu_widgets' );

// ── Email Preview Admin Page ──────────────────────────────────────────────────
// Adds "Email Preview" under WooCommerce admin menu.
// Uses WooCommerce's built-in preview endpoint (?preview_woocommerce_mail=true)
// which renders the actual email HTML in the browser — nonce-protected, admin only.
add_action( 'admin_menu', 'grahamisu_email_preview_menu' );
function grahamisu_email_preview_menu() {
    add_submenu_page(
        'woocommerce',
        'Email Preview',
        '📧 Email Preview',
        'manage_woocommerce',
        'grahamisu-email-preview',
        'grahamisu_email_preview_page'
    );
}

function grahamisu_email_preview_page() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Access denied.' );
    }

    // Each entry: [ 'class' => WC email class name, 'label' => display name, 'audience' => who receives it ]
    $emails = [
        [ 'class' => 'WC_Email_Customer_Processing_Order', 'label' => 'Order Received',         'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Completed_Order',  'label' => 'Order Complete',          'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_On_Hold_Order',    'label' => 'Order On Hold',           'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Cancelled_Order',  'label' => 'Order Cancelled',         'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Refunded_Order',   'label' => 'Order Refunded',          'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Invoice',          'label' => 'Customer Invoice',        'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Note',             'label' => 'Order Note',              'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_New_Account',      'label' => 'New Account',             'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_Customer_Reset_Password',   'label' => 'Reset Password',          'audience' => 'Customer' ],
        [ 'class' => 'WC_Email_New_Order',                 'label' => 'New Order',               'audience' => 'Admin'    ],
        [ 'class' => 'WC_Email_Cancelled_Order',           'label' => 'Cancelled Order',         'audience' => 'Admin'    ],
        [ 'class' => 'WC_Email_Failed_Order',              'label' => 'Failed Order',            'audience' => 'Admin'    ],
    ];

    $nonce       = wp_create_nonce( 'preview-mail' );
    $base_url    = admin_url( '?preview_woocommerce_mail=true&_wpnonce=' . $nonce );
    $active_type = isset( $_GET['email_type'] ) ? sanitize_text_field( $_GET['email_type'] ) : $emails[0]['class'];
    $preview_url = $base_url . '&type=' . urlencode( $active_type );

    ?>
    <div style="display:flex;height:calc(100vh - 32px);gap:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">

        <!-- ── Sidebar: email type list ── -->
        <div style="width:240px;flex-shrink:0;background:#1e1e1e;overflow-y:auto;padding:16px 0;">
            <div style="padding:14px 20px 10px;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#888;">
                Customer
            </div>
            <?php foreach ( $emails as $email ) :
                if ( $email['audience'] !== 'Customer' ) continue;
                $is_active = $active_type === $email['class'];
                $item_url  = admin_url( 'admin.php?page=grahamisu-email-preview&email_type=' . urlencode( $email['class'] ) );
            ?>
            <a href="<?php echo esc_url( $item_url ); ?>"
               style="display:block;padding:9px 20px;font-size:13px;color:<?php echo $is_active ? '#ffffff' : '#aaa'; ?>;text-decoration:none;background:<?php echo $is_active ? '#6c290f' : 'transparent'; ?>;border-left:3px solid <?php echo $is_active ? '#c8a56a' : 'transparent'; ?>;">
                <?php echo esc_html( $email['label'] ); ?>
            </a>
            <?php endforeach; ?>

            <div style="padding:18px 20px 10px;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#888;margin-top:8px;border-top:1px solid #333;">
                Admin
            </div>
            <?php foreach ( $emails as $email ) :
                if ( $email['audience'] !== 'Admin' ) continue;
                $is_active = $active_type === $email['class'];
                $item_url  = admin_url( 'admin.php?page=grahamisu-email-preview&email_type=' . urlencode( $email['class'] ) );
            ?>
            <a href="<?php echo esc_url( $item_url ); ?>"
               style="display:block;padding:9px 20px;font-size:13px;color:<?php echo $is_active ? '#ffffff' : '#aaa'; ?>;text-decoration:none;background:<?php echo $is_active ? '#6c290f' : 'transparent'; ?>;border-left:3px solid <?php echo $is_active ? '#c8a56a' : 'transparent'; ?>;">
                <?php echo esc_html( $email['label'] ); ?>
            </a>
            <?php endforeach; ?>

            <!-- Open in new tab link -->
            <div style="padding:20px 20px 0;border-top:1px solid #333;margin-top:16px;">
                <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank"
                   style="display:block;text-align:center;padding:8px 12px;border:1px solid #555;border-radius:4px;color:#ccc;font-size:12px;text-decoration:none;">
                    ↗ Open in new tab
                </a>
            </div>
        </div>

        <!-- ── Main: iframe preview ── -->
        <div style="flex:1;display:flex;flex-direction:column;background:#f0f0f0;">
            <div style="padding:12px 20px;background:#fff;border-bottom:1px solid #ddd;display:flex;align-items:center;gap:12px;">
                <span style="font-size:13px;font-weight:600;color:#1e1e1e;">
                    <?php
                    foreach ( $emails as $e ) {
                        if ( $e['class'] === $active_type ) {
                            echo esc_html( $e['label'] . ' — ' . $e['audience'] . ' Email' );
                            break;
                        }
                    }
                    ?>
                </span>
                <code style="font-size:11px;color:#888;background:#f5f5f5;padding:2px 8px;border-radius:3px;">
                    <?php echo esc_html( $active_type ); ?>
                </code>
            </div>
            <iframe src="<?php echo esc_url( $preview_url ); ?>"
                    style="flex:1;border:none;width:100%;"
                    title="Email Preview">
            </iframe>
        </div>

    </div>
    <?php
}

// ── Email Logging ─────────────────────────────────────────────────────────────
// Uses two built-in WordPress hooks (added in WP 5.9):
//   wp_mail_succeeded — fires after every successful wp_mail() call
//   wp_mail_failed    — fires when wp_mail() fails, with WP_Error data

// 1. Create the log table on first load — dbDelta() is safe to call repeatedly;
//    it only creates or alters, never drops data.
add_action( 'admin_init', 'grahamisu_email_log_table' );
function grahamisu_email_log_table() {
    if ( get_option( 'grahamisu_email_log_version' ) === '1' ) return;

    global $wpdb;
    $table   = $wpdb->prefix . 'grahamisu_email_log';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id        BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        to_email  VARCHAR(255) NOT NULL,
        subject   VARCHAR(500) NOT NULL,
        status    VARCHAR(10)  NOT NULL DEFAULT 'sent',
        error     TEXT,
        sent_at   DATETIME     NOT NULL,
        PRIMARY KEY (id)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
    update_option( 'grahamisu_email_log_version', '1' );
}

// 2. Log every successful email
add_action( 'wp_mail_succeeded', 'grahamisu_log_email_success' );
function grahamisu_log_email_success( $mail_data ) {
    global $wpdb;
    $to = is_array( $mail_data['to'] ) ? implode( ', ', $mail_data['to'] ) : $mail_data['to'];
    $wpdb->insert(
        $wpdb->prefix . 'grahamisu_email_log',
        [
            'to_email' => sanitize_text_field( $to ),
            'subject'  => sanitize_text_field( $mail_data['subject'] ),
            'status'   => 'sent',
            'error'    => null,
            'sent_at'  => current_time( 'mysql' ),
        ],
        [ '%s', '%s', '%s', '%s', '%s' ]
    );
}

// 3. Log every failed email — wp_mail_failed passes a WP_Error whose
//    error_data contains the original mail args (to, subject, etc.)
add_action( 'wp_mail_failed', 'grahamisu_log_email_failed' );
function grahamisu_log_email_failed( $wp_error ) {
    global $wpdb;
    $data    = $wp_error->get_error_data();
    $to      = '';
    $subject = '(unknown)';
    if ( is_array( $data ) ) {
        if ( isset( $data['to'] ) ) {
            $to = is_array( $data['to'] ) ? implode( ', ', $data['to'] ) : $data['to'];
        }
        if ( isset( $data['subject'] ) ) {
            $subject = $data['subject'];
        }
    }
    $wpdb->insert(
        $wpdb->prefix . 'grahamisu_email_log',
        [
            'to_email' => sanitize_text_field( $to ),
            'subject'  => sanitize_text_field( $subject ),
            'status'   => 'failed',
            'error'    => $wp_error->get_error_message(),
            'sent_at'  => current_time( 'mysql' ),
        ],
        [ '%s', '%s', '%s', '%s', '%s' ]
    );
}

// 4. Register the admin log viewer page under WooCommerce
add_action( 'admin_menu', 'grahamisu_email_log_menu' );
function grahamisu_email_log_menu() {
    add_submenu_page(
        'woocommerce',
        'Email Log',
        '📋 Email Log',
        'manage_woocommerce',
        'grahamisu-email-log',
        'grahamisu_email_log_page'
    );
}

function grahamisu_email_log_page() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Access denied.' );
    }

    global $wpdb;
    $table = $wpdb->prefix . 'grahamisu_email_log';

    // Handle clear log action
    if ( isset( $_POST['clear_log'] ) && check_admin_referer( 'grahamisu_clear_log' ) ) {
        $wpdb->query( "TRUNCATE TABLE $table" ); // phpcs:ignore
        echo '<div class="notice notice-success is-dismissible"><p>Email log cleared.</p></div>';
    }

    $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" ); // phpcs:ignore
    $sent  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'sent'" ); // phpcs:ignore
    $failed = $total - $sent;
    $logs  = $wpdb->get_results( "SELECT * FROM $table ORDER BY sent_at DESC LIMIT 200" ); // phpcs:ignore
    ?>
    <div class="wrap">
        <h1>📋 Email Log</h1>

        <!-- Stats row -->
        <div style="display:flex;gap:16px;margin:16px 0 20px;">
            <div style="padding:16px 24px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#1e1e1e;"><?php echo esc_html( $total ); ?></div>
                <div style="font-size:12px;color:#777;margin-top:2px;">Total</div>
            </div>
            <div style="padding:16px 24px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#155724;"><?php echo esc_html( $sent ); ?></div>
                <div style="font-size:12px;color:#777;margin-top:2px;">Sent</div>
            </div>
            <div style="padding:16px 24px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#<?php echo $failed > 0 ? '721c24' : '999'; ?>;">
                    <?php echo esc_html( $failed ); ?>
                </div>
                <div style="font-size:12px;color:#777;margin-top:2px;">Failed</div>
            </div>
        </div>

        <!-- Clear button -->
        <?php if ( $total > 0 ) : ?>
        <form method="post" style="margin-bottom:16px;">
            <?php wp_nonce_field( 'grahamisu_clear_log' ); ?>
            <button type="submit" name="clear_log" class="button"
                    onclick="return confirm('Clear all <?php echo esc_js( $total ); ?> email log entries?')">
                Clear Log
            </button>
            <span style="margin-left:8px;color:#888;font-size:12px;">Showing last 200 entries</span>
        </form>
        <?php endif; ?>

        <!-- Log table -->
        <?php if ( empty( $logs ) ) : ?>
        <div style="padding:60px 0;text-align:center;color:#999;background:#fff;border:1px solid #e0e0e0;border-radius:6px;">
            <div style="font-size:32px;margin-bottom:8px;">📭</div>
            No emails logged yet.<br>
            <span style="font-size:13px;">Logs will appear here the next time WooCommerce sends an email.</span>
        </div>
        <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width:150px;">Date &amp; Time</th>
                    <th style="width:220px;">To</th>
                    <th>Subject</th>
                    <th style="width:80px;text-align:center;">Status</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $logs as $log ) : ?>
                <tr>
                    <td style="font-size:12px;color:#555;">
                        <?php echo esc_html( date_i18n( 'M j, Y g:i a', strtotime( $log->sent_at ) ) ); ?>
                    </td>
                    <td style="font-size:12px;word-break:break-all;">
                        <?php echo esc_html( $log->to_email ); ?>
                    </td>
                    <td style="font-size:13px;">
                        <?php echo esc_html( $log->subject ); ?>
                    </td>
                    <td style="text-align:center;">
                        <?php if ( $log->status === 'sent' ) : ?>
                        <span style="display:inline-block;padding:2px 10px;border-radius:100px;background:#d4edda;color:#155724;font-size:11px;font-weight:700;letter-spacing:.04em;">
                            Sent
                        </span>
                        <?php else : ?>
                        <span style="display:inline-block;padding:2px 10px;border-radius:100px;background:#f8d7da;color:#721c24;font-size:11px;font-weight:700;letter-spacing:.04em;">
                            Failed
                        </span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12px;color:#cc0000;">
                        <?php echo esc_html( $log->error ?: '—' ); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php
}

// ── Checkout: save fulfillment meta (date, time, notes) on order creation ────
add_action( 'woocommerce_checkout_create_order', 'grahamisu_save_fulfillment_meta', 10, 2 );
function grahamisu_save_fulfillment_meta( WC_Order $order, array $data ) {
    $date  = sanitize_text_field( wp_unslash( $_POST['gc_delivery_date']  ?? '' ) );
    $time  = sanitize_text_field( wp_unslash( $_POST['gc_delivery_time']  ?? '' ) );
    $notes = sanitize_textarea_field( wp_unslash( $_POST['gc_order_notes'] ?? '' ) );

    if ( $date )  $order->update_meta_data( '_gc_delivery_date',  $date );
    if ( $time )  $order->update_meta_data( '_gc_delivery_time',  $time );
    if ( $notes ) $order->update_meta_data( '_gc_order_notes',    $notes );
}

// Display fulfillment meta in WP Admin → Order detail page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'grahamisu_admin_order_fulfillment' );
function grahamisu_admin_order_fulfillment( WC_Order $order ) {
    $date  = $order->get_meta( '_gc_delivery_date' );
    $time  = $order->get_meta( '_gc_delivery_time' );
    $notes = $order->get_meta( '_gc_order_notes' );
    if ( ! $date && ! $time && ! $notes ) return;
    ?>
    <div style="margin-top:12px;padding:10px 12px;background:#fdf5f0;border:1px solid #e2c5b0;border-radius:4px;">
        <strong style="display:block;margin-bottom:6px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#61453a;">
            Fulfillment Details
        </strong>
        <?php if ( $date ) : ?>
            <p style="margin:2px 0;font-size:13px;"><strong>Date:</strong> <?php echo esc_html( $date ); ?></p>
        <?php endif; ?>
        <?php if ( $time ) : ?>
            <p style="margin:2px 0;font-size:13px;"><strong>Time:</strong> <?php echo esc_html( $time ); ?></p>
        <?php endif; ?>
        <?php if ( $notes ) : ?>
            <p style="margin:2px 0;font-size:13px;"><strong>Notes:</strong> <?php echo esc_html( $notes ); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

// ── Billing address fields: make optional in WC (custom validation below handles it)
add_filter( 'woocommerce_checkout_fields', function( $fields ) {
    $fields['billing']['billing_city']['required']      = false;
    $fields['billing']['billing_state']['required']     = false;
    $fields['billing']['billing_address_1']['required'] = false;
    $fields['billing']['billing_postcode']['required']  = false;
    return $fields;
} );

// Require address fields only when delivery (flat_rate) is selected.
// Pickup customers don't need to provide an address.
add_action( 'woocommerce_after_checkout_validation', 'grahamisu_validate_delivery_address', 10, 2 );
function grahamisu_validate_delivery_address( array $data, WP_Error $errors ) {
    $method      = sanitize_text_field( wp_unslash( $_POST['shipping_method'][0] ?? '' ) );
    $is_delivery = str_starts_with( $method, 'flat_rate' );
    if ( ! $is_delivery ) return;

    if ( empty( $_POST['billing_address_1'] ) ) {
        $errors->add( 'billing_address_required', __( '<strong>Street address</strong> is a required field for delivery.', 'grahamisu' ) );
    }
    if ( empty( $_POST['billing_postcode'] ) ) {
        $errors->add( 'billing_postcode_required', __( '<strong>Postal code</strong> is a required field for delivery.', 'grahamisu' ) );
    }
}

// ── Security Hardening ────────────────────────────────────────────────────────

// 1. Hide /wp-json/wp/v2/users — prevents username enumeration via REST API
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( ! is_user_logged_in() ) {
        unset( $endpoints['/wp/v2/users'] );
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );

// 2. Block author enumeration via /?author=1 redirects
add_action( 'template_redirect', function() {
    if ( ! is_admin() && isset( $_GET['author'] ) && ! is_user_logged_in() ) {
        wp_redirect( home_url( '/' ), 301 );
        exit;
    }
} );

// 3. Remove WordPress version from <head> and all feeds
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// 4. Strip ?ver= query strings from enqueued scripts and styles
add_filter( 'style_loader_src',  'grahamisu_strip_ver_query', 9999 );
add_filter( 'script_loader_src', 'grahamisu_strip_ver_query', 9999 );
function grahamisu_strip_ver_query( $src ) {
    return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
}

// 5. Disable XML-RPC — not used by this store; common brute-force target
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// 6. Send 403 for readme.html and license.txt (expose WP version)
add_action( 'init', function() {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if ( preg_match( '#/(readme\.html|license\.txt)$#i', $uri ) ) {
        status_header( 403 );
        nocache_headers();
        exit;
    }
} );

// ── Checkout: AJAX shipping method update ─────────────────────────────────────
// Updates the WC session's chosen shipping method and returns fresh totals.
// Called by JS when the customer switches between Pickup and Delivery tabs.
// Uses wc_ajax_* hooks so the endpoint is available to non-logged-in users
// at /?wc-ajax=gc_update_shipping (same pattern as WC's own coupon endpoint).
add_action( 'wc_ajax_gc_update_shipping',        'grahamisu_update_shipping' );
add_action( 'wc_ajax_nopriv_gc_update_shipping', 'grahamisu_update_shipping' );
function grahamisu_update_shipping() {
    check_ajax_referer( 'gc-update-shipping', 'nonce' );

    $rate_id = sanitize_text_field( wp_unslash( $_POST['rate_id'] ?? '' ) );
    if ( ! $rate_id ) {
        wp_send_json_error( 'Missing rate_id.' );
    }

    WC()->session->set( 'chosen_shipping_methods', [ $rate_id ] );
    WC()->cart->calculate_shipping();
    WC()->cart->calculate_totals();

    $shipping_total = WC()->cart->get_shipping_total();
    $order_total    = (float) WC()->cart->get_total( 'edit' );

    wp_send_json_success( [
        'shipping'           => $shipping_total,
        'total'              => $order_total,
        'shipping_formatted' => html_entity_decode( strip_tags( wc_price( $shipping_total ) ), ENT_HTML5, 'UTF-8' ),
        'total_formatted'    => html_entity_decode( strip_tags( wc_price( $order_total ) ),    ENT_HTML5, 'UTF-8' ),
    ] );
}
