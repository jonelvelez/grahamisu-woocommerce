<?php
/**
 * Email Header — Grahamisu branded layout.
 *
 * Overrides: woocommerce/templates/emails/email-header.php
 *
 * ── HTML Email Best Practices applied here ──────────────────────────────────
 *
 * 1. DOCTYPE + xmlns — Plain HTML5 DOCTYPE works best. The xmlns attributes
 *    enable VML (Vector Markup Language) for Outlook button backgrounds later.
 *
 * 2. Meta tags — Four critical ones:
 *    · Content-Type       → tells clients to use UTF-8 charset
 *    · viewport           → enables mobile scaling on iOS/Android
 *    · X-UA-Compatible    → forces IE/Outlook to use the latest rendering engine
 *    · x-apple-disable    → stops iOS Mail from auto-resizing small text
 *    · format-detection   → prevents iOS from converting phone numbers to links
 *
 * 3. MSO conditional comments (<!--[if mso]>…<![endif]-->) — Outlook uses
 *    Microsoft Word's HTML renderer, not a real browser engine. Conditional
 *    comments let us send Outlook-specific CSS that other clients ignore.
 *    The PixelsPerInch setting stops Outlook from scaling at 120dpi monitors.
 *
 * 4. <style> block — Gmail strips <style> tags; other clients (Apple Mail,
 *    Outlook 2019+, Yahoo) support them. Use as a progressive enhancement:
 *    · Email resets clean up inconsistencies across clients
 *    · Media queries enable a mobile-responsive layout
 *    · Never rely on <style> alone — always have inline fallbacks.
 *
 * 5. Key reset rules:
 *    · #outlook a { padding:0 }         — removes Outlook's default link padding
 *    · mso-table-lspace/rspace:0pt      — removes invisible Outlook table gutters
 *    · -webkit-text-size-adjust:100%    — prevents iOS from auto-zooming text
 *    · a[x-apple-data-detectors]        — neutralises iOS auto-detected links
 *    · u + #body a                      — Gmail blue link fix
 *
 * 6. Preheader text — The ~100-char snippet shown in inbox previews BEFORE the
 *    email is opened. Hidden visually via display:none + zero dimensions.
 *    Always write a human preheader; never leave it empty or inbox apps will
 *    pull the first visible text (often "View in browser" or table header junk).
 *
 * 7. Table layout rules (apply to EVERY table in the email):
 *    · role="presentation"      — tells screen readers it is layout, not data
 *    · border="0" cellpadding="0" cellspacing="0" — removes default spacing
 *    · width="" as HTML attribute AND style — old Outlook ignores CSS width
 *
 * 8. No external fonts — Email clients block Google Fonts requests.
 *    Georgia is the closest web-safe serif to Playfair Display.
 *    Helvetica / Arial is the safe sans-serif stack for body copy.
 *
 * email-footer.php closes every tag opened here — keep them in sync.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<!-- BEST PRACTICE 2: Meta tags for compatibility -->
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="x-apple-disable-message-reformatting">
	<meta name="format-detection" content="telephone=no,date=no,address=no,email=no,url=no">
	<title><?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>

	<!-- BEST PRACTICE 3: MSO conditional — Outlook 96dpi fix -->
	<!--[if mso]>
	<noscript>
		<xml>
			<o:OfficeDocumentSettings>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
	</noscript>
	<![endif]-->

	<!-- BEST PRACTICE 4 & 5: <style> for resets + responsive (progressive enhancement) -->
	<style type="text/css">
		/* Reset: Outlook link padding */
		#outlook a { padding: 0; }

		/* Reset: body defaults */
		body {
			margin: 0;
			padding: 0;
			background-color: #fdf2ec;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
		}

		/* Reset: table gutters (Outlook-specific via mso- props) */
		table, td {
			border-collapse: collapse;
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
		}

		/* Reset: image defaults */
		img {
			border: 0;
			outline: none;
			text-decoration: none;
			-ms-interpolation-mode: bicubic;
			display: block;
		}

		/* Reset: paragraph spacing */
		p { margin: 0; padding: 0; }

		/* iOS auto-detected links (phone/address/email) — remove blue styling */
		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		/* Gmail blue link override (u + #body targets only Gmail) */
		u + #body a {
			color: inherit;
			text-decoration: none;
			font-size: inherit;
			font-family: inherit;
			font-weight: inherit;
			line-height: inherit;
		}

		/* BEST PRACTICE 4: Responsive via media query (supported by most clients except old Gmail) */
		@media only screen and (max-width: 620px) {
			.email-card   { width: 100% !important; }
			.email-padded { padding-left: 24px !important; padding-right: 24px !important; }
			.email-h1     { font-size: 22px !important; line-height: 1.3 !important; }
		}
	</style>
</head>

<!-- BEST PRACTICE 5: id="body" enables the Gmail u + #body selector above -->
<body id="body" style="background-color:#fdf2ec;margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

	<!-- BEST PRACTICE 6: Preheader — edit this text to write inbox preview copy -->
	<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;font-size:1px;color:#fdf2ec;line-height:1px;">
		<?php echo esc_html( get_bloginfo( 'name' ) ); ?> — your order is confirmed. Thank you for ordering with us!
		<!-- Spacer characters pad out the preheader so inbox apps don't pull in body text -->
		&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
	</div>

	<!-- BEST PRACTICE 7: Outer wrapper table — cream bg fills the full email canvas -->
	<!-- width="100%" as HTML attribute (old Outlook ignores CSS-only width)         -->
	<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
	       style="background-color:#fdf2ec;width:100%;">
		<tr>
			<td align="center" style="padding:40px 20px;">

				<!-- Email card: 600px is the standard safe width for desktop + mobile -->
				<!-- BEST PRACTICE 7: width set both as attribute AND in style         -->
				<table class="email-card" width="600" cellpadding="0" cellspacing="0" border="0" role="presentation"
				       style="max-width:600px;width:600px;background-color:#ffffff;">

					<!-- ── Brand header band ── -->
					<tr>
						<td class="email-padded" style="background-color:#371613;padding:28px 48px;">
							<!-- BEST PRACTICE 8: Georgia = closest web-safe serif to Playfair Display -->
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
							   style="color:#ffffff;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:normal;letter-spacing:1px;text-decoration:none;display:block;">
								<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
							</a>
						</td>
					</tr>

					<!-- ── Email heading ── -->
					<tr>
						<td class="email-padded" style="background-color:#ffffff;border-bottom:3px solid #c8a56a;padding:28px 48px 20px;">
							<h1 class="email-h1"
							    style="color:#371613;font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;margin:0;padding:0;line-height:1.3;">
								<?php echo esc_html( $email_heading ); ?>
							</h1>
						</td>
					</tr>

					<!-- ── Body content — WooCommerce fills this cell ── -->
					<tr>
						<td style="background-color:#ffffff;">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
								<tr>
									<td class="email-padded"
									    style="padding:24px 48px 32px;color:#61453a;font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:1.6;text-align:left;">
