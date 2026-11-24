<?php
/**
 * Footer.
 *
 * @package Dara
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__grid">
			<div class="site-footer__about">
				<?php dara_logo( true ); ?>
				<?php if ( dara_mod( 'footer_about' ) ) : ?>
					<p><?php echo esc_html( dara_mod( 'footer_about' ) ); ?></p>
				<?php endif; ?>
				<?php dara_social_links(); ?>
			</div>

			<div class="site-footer__col">
				<h2 class="site-footer__title"><?php esc_html_e( 'Quick links', 'dara' ); ?></h2>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-links',
							'depth'          => 1,
						)
					);
				} else {
					echo '<ul class="footer-links">';
					wp_list_pages(
						array(
							'title_li' => '',
							'depth'    => 1,
							'number'   => 6,
						)
					);
					echo '</ul>';
				}
				?>
			</div>

			<?php
			$dara_types = dara_has_core() ? get_terms(
				array(
					'taxonomy' => 'property_type',
					'number'   => 6,
					'orderby'  => 'count',
					'order'    => 'DESC',
				)
			) : array();
			if ( $dara_types && ! is_wp_error( $dara_types ) ) :
				?>
				<div class="site-footer__col">
					<h2 class="site-footer__title"><?php esc_html_e( 'Property types', 'dara' ); ?></h2>
					<ul class="footer-links">
						<?php foreach ( $dara_types as $dara_type ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $dara_type ) ); ?>"><?php echo esc_html( $dara_type->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<div class="site-footer__col site-footer__contact">
				<?php if ( dara_mod( 'newsletter_show' ) && dara_has_core() ) : ?>
					<h2 class="site-footer__title"><?php esc_html_e( 'Newsletter', 'dara' ); ?></h2>
					<p><?php echo esc_html( dara_mod( 'newsletter_text' ) ); ?></p>
					<form class="newsletter" method="post" action="<?php echo esc_url( dara_lead_action() ); ?>">
						<?php dara_lead_hidden_fields( 'newsletter' ); ?>
						<label class="screen-reader-text" for="nl-email"><?php esc_html_e( 'Email address', 'dara' ); ?></label>
						<input id="nl-email" type="email" name="lead_email" required placeholder="<?php esc_attr_e( 'Your email', 'dara' ); ?>">
						<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Subscribe', 'dara' ); ?></button>
					</form>
				<?php endif; ?>
				<ul class="contact-list">
					<?php if ( dara_mod( 'phone' ) ) : ?>
						<li><?php dara_the_icon( 'phone', 18 ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dara_mod( 'phone' ) ) ); ?>" dir="ltr"><?php echo esc_html( dara_mod( 'phone' ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( dara_mod( 'email' ) ) : ?>
						<li><?php dara_the_icon( 'mail', 18 ); ?><a href="mailto:<?php echo esc_attr( antispambot( dara_mod( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( dara_mod( 'email' ) ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( dara_mod( 'address' ) ) : ?>
						<li><?php dara_the_icon( 'pin', 18 ); ?><span><?php echo esc_html( dara_mod( 'address' ) ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>

		<div class="site-footer__bottom">
			<span>
				<?php echo esc_html( dara_copyright() ); ?>
				<?php if ( dara_mod( 'designer_credit' ) ) : ?>
					<span class="site-footer__credit">
						<?php
						printf(
							/* translators: %s: designer company link. */
							esc_html__( 'Design: %s', 'dara' ),
							'<a href="https://mansourahost.com" rel="noopener">Mansoura Host</a>'
						);
						?>
					</span>
				<?php endif; ?>
			</span>
			<?php
			$dara_licenses = array();
			if ( dara_has_core() && dara_setting( 'office_fal' ) ) {
				/* translators: %s: license number. */
				$dara_licenses[] = sprintf( __( 'FAL license: %s', 'dara' ), dara_setting( 'office_fal' ) );
			}
			if ( dara_has_core() && dara_setting( 'office_cr' ) ) {
				/* translators: %s: CR number. */
				$dara_licenses[] = sprintf( __( 'CR: %s', 'dara' ), dara_setting( 'office_cr' ) );
			}
			if ( $dara_licenses ) {
				echo '<span>' . esc_html( implode( ' · ', $dara_licenses ) ) . '</span>';
			}
			?>
		</div>
	</div>
</footer>

<?php
// On a property page the bottom bar calls the property's agent, not the office,
// so visitors never see two different numbers for the same listing.
$dara_bar = array(
	'phone'    => dara_mod( 'phone' ),
	'whatsapp' => dara_mod( 'whatsapp' ) ? 'https://wa.me/' . preg_replace( '/\D+/', '', dara_mod( 'whatsapp' ) ) : '',
	'call'     => __( 'Call us', 'dara' ),
	'label'    => '',
);
if ( is_singular( 'dara_property' ) && function_exists( 'dara_get_agent' ) ) {
	$dara_bar_agent = dara_get_agent( get_queried_object_id() );
	if ( $dara_bar_agent['phone'] || $dara_bar_agent['whatsapp'] ) {
		$dara_bar = array(
			'phone'    => $dara_bar_agent['phone'],
			'whatsapp' => $dara_bar_agent['whatsapp'] ? dara_whatsapp_url( $dara_bar_agent['whatsapp'], sprintf( /* translators: 1: property title, 2: URL. */ __( 'Hello, I am interested in: %1$s %2$s', 'dara' ), get_the_title( get_queried_object_id() ), get_permalink( get_queried_object_id() ) ) ) : '',
			'call'     => __( 'Call', 'dara' ),
			/* translators: %s: agent name. */
			'label'    => sprintf( __( 'Contact %s', 'dara' ), $dara_bar_agent['name'] ),
		);
	}
}
?>
<?php if ( $dara_bar['phone'] || $dara_bar['whatsapp'] ) : ?>
	<div class="mobile-bar"<?php echo $dara_bar['label'] ? ' role="region" aria-label="' . esc_attr( $dara_bar['label'] ) . '"' : ''; ?>>
		<?php if ( $dara_bar['phone'] ) : ?>
			<a class="btn btn--primary" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $dara_bar['phone'] ) ); ?>"><?php dara_the_icon( 'phone', 18 ); ?><?php echo esc_html( $dara_bar['call'] ); ?></a>
		<?php endif; ?>
		<?php if ( $dara_bar['whatsapp'] ) : ?>
			<a class="btn btn--outline" href="<?php echo esc_url( $dara_bar['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php dara_the_icon( 'whatsapp', 18 ); ?><?php esc_html_e( 'WhatsApp', 'dara' ); ?></a>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
