<?php
/**
 * Email Footer — Grahamisu branded layout.
 *
 * Overrides: woocommerce/templates/emails/email-footer.php
 *
 * ── Additional best practices applied here ──────────────────────────────────
 *
 * 9.  Gold divider — A 2px table row used instead of <hr> because <hr> renders
 *     inconsistently across clients (different heights, colours, margins).
 *     The "ghost" technique: height:2px on <td> + font-size:0 + line-height:0
 *     collapses the cell to exactly 2px even in Outlook.
 *
 * 10. Footer unsubscribe / legal — Required by CAN-SPAM and GDPR. Always include
 *     a physical mailing address and (for marketing emails) an unsubscribe link.
 *     WooCommerce transactional emails are exempt from CAN-SPAM opt-out rules,
 *     but it is still good practice to include your business address.
 *
 * 11. Closing tags — Every table, tr, td, body, and html opened in
 *     email-header.php must be explicitly closed here.
 *     Never rely on browser auto-close; many email parsers are strict.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;

$email = $email ?? null;
?>
								</td><!-- /body content cell -->
							</tr>
						</table><!-- /inner body table -->
					</td>
				</tr>

				<!-- BEST PRACTICE 9: Gold divider via table row — never use <hr> in email -->
				<tr>
					<td style="background-color:#c8a56a;height:2px;font-size:0;line-height:0;mso-line-height-rule:exactly;">&nbsp;</td>
				</tr>

				<!-- ── Footer band ── -->
				<!-- BEST PRACTICE 10: Footer with business info (CAN-SPAM / GDPR)          -->
				<tr>
					<td class="email-padded" style="background-color:#371613;padding:28px 48px;text-align:center;">

						<!-- Footer text from WP Admin → WooCommerce → Settings → Emails -->
						<?php
						$footer_text = get_option( 'woocommerce_email_footer_text' );
						if ( $footer_text ) :
						?>
						<p style="color:#a0887e;font-family:Helvetica,Arial,sans-serif;font-size:12px;line-height:1.6;margin:0 0 8px;padding:0;">
							<?php
							echo wp_kses_post(
								wpautop(
									wptexturize(
										apply_filters( 'woocommerce_email_footer_text', $footer_text, $email )
									)
								)
							);
							?>
						</p>
						<?php endif; ?>

						<p style="color:#6b4e44;font-family:Helvetica,Arial,sans-serif;font-size:11px;margin:0;padding:0;line-height:1.6;">
							&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
							<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
							All rights reserved.
						</p>

					</td>
				</tr>

			</table><!-- BEST PRACTICE 11: closing the 600px email card table -->

		</td>
	</tr>
</table><!-- /outer wrapper table -->

</body>
</html>
