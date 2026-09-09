<?php
/**
 * Email Footer — Grahamisu branded layout.
 *
 * Overrides: woocommerce/templates/emails/email-footer.php
 *
 * IMPORTANT: This file closes every HTML tag that email-header.php opened.
 * The structure must stay in sync with email-header.php.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;

$email = $email ?? null;
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- ── Gold divider ────────────────────────────────── -->
				<tr>
					<td style="background-color:#c8a56a;height:2px;font-size:0;line-height:0;">&nbsp;</td>
				</tr>

				<!-- ── Footer band ─────────────────────────────────── -->
				<!-- Dark background, matches the header band.          -->
				<!-- Footer text comes from WooCommerce → Settings →    -->
				<!-- Emails → "Footer text" field in WP admin.          -->
				<tr>
					<td style="background-color:#371613;padding:28px 48px;text-align:center;">
						<p style="color:#a0887e;font-family:Helvetica,Arial,sans-serif;font-size:12px;line-height:1.6;margin:0 0 8px;">
							<?php
							$footer_text = get_option( 'woocommerce_email_footer_text' );
							echo wp_kses_post(
								wpautop(
									wptexturize(
										apply_filters( 'woocommerce_email_footer_text', $footer_text, $email )
									)
								)
							);
							?>
						</p>
						<p style="color:#6b4e44;font-family:Helvetica,Arial,sans-serif;font-size:11px;margin:0;">
							&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
							<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
							All rights reserved.
						</p>
					</td>
				</tr>

			</table><!-- /email card -->

		</td>
	</tr>
</table><!-- /outer wrapper -->

</body>
</html>
