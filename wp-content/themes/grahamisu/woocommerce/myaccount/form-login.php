<?php
/**
 * My Account login / register form — Grahamisu branded.
 *
 * Rendered when a customer visits /my-account/ while NOT logged in.
 * Two-column layout on desktop if registration is enabled.
 *
 * IMPORTANT: All WooCommerce form hooks, nonces, and name attributes
 * must be preserved — they are required for login/register to work.
 *
 * Input styling matches the checkout form (rust border, Lato font).
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="max-w-[1440px] mx-auto px-4 lg:px-[141px] py-14">

    <?php if ( $registration_enabled ) : ?>
    <div class="flex flex-col lg:flex-row gap-0 lg:gap-0 items-stretch">
    <?php endif; ?>

        <!-- ── Login ─────────────────────────────────────────────── -->
        <div class="<?php echo $registration_enabled ? 'w-full lg:w-1/2 lg:pr-14' : 'max-w-[480px]'; ?> pb-12 lg:pb-0">

            <h2 class="font-primary font-semibold text-2xl text-[--color-dark] mb-6">
                <?php esc_html_e( 'Login', 'woocommerce' ); ?>
            </h2>

            <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <!-- Username / Email -->
                <div class="mb-4">
                    <label for="username"
                           class="block font-['Lato',sans-serif] text-xs font-semibold text-[--color-muted] uppercase tracking-widest mb-1.5">
                        <?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>
                        <span class="text-[--color-rust]" aria-hidden="true">*</span>
                        <span class="sr-only"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                    </label>
                    <div class="border border-[#b8542c] rounded-[10px] h-[40px] overflow-hidden">
                        <input type="text" name="username" id="username"
                               autocomplete="username" required aria-required="true"
                               value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                               class="woocommerce-Input woocommerce-Input--text input-text w-full h-full bg-transparent border-none outline-none px-3 font-['Lato',sans-serif] text-sm text-[--color-dark]">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password"
                           class="block font-['Lato',sans-serif] text-xs font-semibold text-[--color-muted] uppercase tracking-widest mb-1.5">
                        <?php esc_html_e( 'Password', 'woocommerce' ); ?>
                        <span class="text-[--color-rust]" aria-hidden="true">*</span>
                        <span class="sr-only"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                    </label>
                    <div class="border border-[#b8542c] rounded-[10px] h-[40px] overflow-hidden">
                        <input type="password" name="password" id="password"
                               autocomplete="current-password" required aria-required="true"
                               class="woocommerce-Input woocommerce-Input--text input-text w-full h-full bg-transparent border-none outline-none px-3 font-['Lato',sans-serif] text-sm text-[--color-dark]">
                    </div>
                </div>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="rememberme" id="rememberme" value="forever"
                               class="woocommerce-form__input woocommerce-form__input-checkbox accent-[--color-rust]">
                        <span class="font-['Lato',sans-serif] text-sm text-[--color-muted]">
                            <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
                        </span>
                    </label>
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
                       class="font-['Lato',sans-serif] text-sm text-[--color-muted] underline underline-offset-2 hover:text-[--color-brown] transition-colors">
                        <?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?>
                    </a>
                </div>

                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

                <button type="submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"
                        class="woocommerce-button button woocommerce-form-login__submit w-full h-[45px] bg-[--color-rust] text-white font-['Lato',sans-serif] font-semibold text-sm rounded-[50px] border-none cursor-pointer hover:opacity-90 transition-opacity">
                    <?php esc_html_e( 'Log in', 'woocommerce' ); ?>
                </button>

                <?php do_action( 'woocommerce_login_form_end' ); ?>

            </form>
        </div><!-- /login -->

    <?php if ( $registration_enabled ) : ?>

        <!-- vertical divider (desktop only) -->
        <div class="hidden lg:block w-px bg-[#e8ddd0] self-stretch"></div>

        <!-- ── Register ──────────────────────────────────────────── -->
        <div class="w-full lg:w-1/2 lg:pl-14 pt-12 lg:pt-0 border-t border-[#e8ddd0] lg:border-t-0">

            <h2 class="font-primary font-semibold text-2xl text-[--color-dark] mb-6">
                <?php esc_html_e( 'Register', 'woocommerce' ); ?>
            </h2>

            <form method="post" class="woocommerce-form woocommerce-form-register register"
                  <?php do_action( 'woocommerce_register_form_tag' ); ?>>

                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                <div class="mb-4">
                    <label for="reg_username"
                           class="block font-['Lato',sans-serif] text-xs font-semibold text-[--color-muted] uppercase tracking-widest mb-1.5">
                        <?php esc_html_e( 'Username', 'woocommerce' ); ?>
                        <span class="text-[--color-rust]" aria-hidden="true">*</span>
                    </label>
                    <div class="border border-[#b8542c] rounded-[10px] h-[40px] overflow-hidden">
                        <input type="text" name="username" id="reg_username"
                               autocomplete="username" required aria-required="true"
                               value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                               class="woocommerce-Input woocommerce-Input--text input-text w-full h-full bg-transparent border-none outline-none px-3 font-['Lato',sans-serif] text-sm text-[--color-dark]">
                    </div>
                </div>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="reg_email"
                           class="block font-['Lato',sans-serif] text-xs font-semibold text-[--color-muted] uppercase tracking-widest mb-1.5">
                        <?php esc_html_e( 'Email address', 'woocommerce' ); ?>
                        <span class="text-[--color-rust]" aria-hidden="true">*</span>
                    </label>
                    <div class="border border-[#b8542c] rounded-[10px] h-[40px] overflow-hidden">
                        <input type="email" name="email" id="reg_email"
                               autocomplete="email" required aria-required="true"
                               value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
                               class="woocommerce-Input woocommerce-Input--text input-text w-full h-full bg-transparent border-none outline-none px-3 font-['Lato',sans-serif] text-sm text-[--color-dark]">
                    </div>
                </div>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                <div class="mb-5">
                    <label for="reg_password"
                           class="block font-['Lato',sans-serif] text-xs font-semibold text-[--color-muted] uppercase tracking-widest mb-1.5">
                        <?php esc_html_e( 'Password', 'woocommerce' ); ?>
                        <span class="text-[--color-rust]" aria-hidden="true">*</span>
                    </label>
                    <div class="border border-[#b8542c] rounded-[10px] h-[40px] overflow-hidden">
                        <input type="password" name="password" id="reg_password"
                               autocomplete="new-password" required aria-required="true"
                               class="woocommerce-Input woocommerce-Input--text input-text w-full h-full bg-transparent border-none outline-none px-3 font-['Lato',sans-serif] text-sm text-[--color-dark]">
                    </div>
                </div>
                <?php else : ?>
                <p class="font-['Lato',sans-serif] text-sm text-[--color-muted] mb-5">
                    <?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?>
                </p>
                <?php endif; ?>

                <?php do_action( 'woocommerce_register_form' ); ?>

                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

                <button type="submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"
                        class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit mt-6 w-full h-[45px] bg-[--color-dark] text-white font-['Lato',sans-serif] font-semibold text-sm rounded-[50px] border-none cursor-pointer hover:opacity-90 transition-opacity">
                    <?php esc_html_e( 'Register', 'woocommerce' ); ?>
                </button>

                <?php do_action( 'woocommerce_register_form_end' ); ?>

            </form>
        </div><!-- /register -->

    </div><!-- /two-column -->

    <?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
