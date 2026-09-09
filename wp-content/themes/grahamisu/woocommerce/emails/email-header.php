<?php
/**
 * Email Header — Grahamisu branded layout.
 *
 * Overrides: woocommerce/templates/emails/email-header.php
 *
 * IMPORTANT: This file opens the HTML document AND the body content div.
 * email-footer.php closes all of these open tags — they must stay in sync.
 *
 * Email layout rules:
 *  - Table-based layout only (flexbox/grid not supported in Outlook)
 *  - All styles must be inline (email clients strip <style> blocks)
 *  - No Google Fonts (email clients block external resources)
 *    → Georgia serif is the closest web-safe match to Playfair Display
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
</head>
<body style="background-color:#fdf2ec;margin:0;padding:0;text-align:center;">

<!-- Outer wrapper — cream background fills the full email canvas -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
       style="background-color:#fdf2ec;">
	<tr>
		<td align="center" style="padding:40px 20px;">

			<!-- Email card: 600px wide, white background -->
			<table width="600" cellpadding="0" cellspacing="0" border="0" role="presentation"
			       style="max-width:600px;width:100%;background-color:#ffffff;">

				<!-- ── Brand header band ───────────────────────────── -->
				<!-- Dark background with store name in serif font.     -->
				<!-- Georgia is used instead of Playfair Display because -->
				<!-- email clients cannot load Google Fonts.            -->
				<tr>
					<td style="background-color:#371613;padding:28px 48px;">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
						   style="color:#ffffff;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:normal;letter-spacing:1px;text-decoration:none;">
							<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
						</a>
					</td>
				</tr>

				<!-- ── Email heading ───────────────────────────────── -->
				<!-- $email_heading is passed in by WooCommerce:        -->
				<!--   e.g. "Thank you for your order"                  -->
				<!--   or   "Your order has been received"              -->
				<!-- The gold bottom border matches --color-gold brand token -->
				<tr>
					<td style="background-color:#ffffff;border-bottom:3px solid #c8a56a;padding:28px 48px 20px;">
						<h1 style="color:#371613;font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;margin:0;line-height:1.3;">
							<?php echo esc_html( $email_heading ); ?>
						</h1>
					</td>
				</tr>

				<!-- ── Body content ────────────────────────────────── -->
				<!-- WooCommerce inserts its order details, messages,   -->
				<!-- and tables BETWEEN this opening cell and the       -->
				<!-- matching closing tags in email-footer.php.         -->
				<tr>
					<td style="background-color:#ffffff;">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
							<tr>
								<td style="padding:24px 48px 32px;color:#61453a;font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:1.6;text-align:left;">
