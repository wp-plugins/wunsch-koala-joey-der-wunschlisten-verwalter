<?php

add_action('admin_menu', array('Wunsch_Koala_Settings', 'menu'));
add_action('admin_init', array('Wunsch_Koala_Settings', 'init'));



class Wunsch_Koala_Settings {

	static public function menu() {
		add_options_page('Wunsch Koala', 'Wunsch Koala', 'manage_options', 'wunsch-koala', array('Wunsch_Koala_Settings', 'settingsPage'));
	}

	
	static public function init() {
		register_setting('wunsch-koala-options', 'wunsch-koala-options', array('Wunsch_Koala_Settings', 'optionsValidate'));
		
		add_settings_section('wunsch-koala-affiliate-section', 'Affiliate Einstellungen', array('Wunsch_Koala_Settings', 'affiliateSection'), 'wunsch-koala-options');
		add_settings_field('wunsch-koala-affiliate-id-field', 'Affiliate Id', array('Wunsch_Koala_Settings', 'affiliateIdField'), 'wunsch-koala-options', 'wunsch-koala-affiliate-section');
		
		
		add_settings_section('wunsch-koala-autolink-section', 'Autolinks', array('Wunsch_Koala_Settings', 'autolinkSection'), 'wunsch-koala-options');
		add_settings_field('wunsch-koala-autolink-active-field', 'Autolinks aktiv', array('Wunsch_Koala_Settings', 'autolinkActiveField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		add_settings_field('wunsch-koala-autolink-domains-field', 'Wunschlink nach Links zu folgenden Domains einfügen', array('Wunsch_Koala_Settings', 'autolinkDomainsField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		add_settings_field('wunsch-koala-autolink-html-before-field', 'HTML vor Wunschlink', array('Wunsch_Koala_Settings', 'autolinkHtmlBeforeField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		add_settings_field('wunsch-koala-autolink-linktext-field', 'Linktext für Wunschlink', array('Wunsch_Koala_Settings', 'autolinkLinktextField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		//add_settings_field('wunsch-koala-autolink-wish-name-field', 'Name des Wunsches', array('Wunsch_Koala_Settings', 'autolinkWishNameField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		//add_settings_field('wunsch-koala-autolink-wish-link-field', 'URL des Wunsches', array('Wunsch_Koala_Settings', 'autolinkWishLinkField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		add_settings_field('wunsch-koala-autolink-html-after-field', 'HTML nach Wunschlink', array('Wunsch_Koala_Settings', 'autolinkHtmlAfterField'), 'wunsch-koala-options', 'wunsch-koala-autolink-section');
		
	}
			
	static public function settingsPage() {
		?>
		<div class="wrap">
		<h2>Wunsch Koala - Joey der Wunschlisten Verwalter</h2>
		<p>Willkommen bei den Wunsch Koala Einstellungen.</p>
		<p>Zum Einfügen eines manuellen Links einfach den Tag <code>[wunsch-koala-link]Linktext[/wunsch-koala-link]</code> im Editor an der gewünschten Stelle eingeben. Als Name des Wunsches wird der aktuellen Seitentitel und als URL die aktuelle URL verwendet. Mit den folgenden optionalen Attributen kannst du das ändern: <code>[wunsch-koala-link name="Tolles Produkt" link="http://www.example.com"]Linktext[/wunsch-koala-link]</code></p>
		<p>Etwas funktioniert nicht richtig oder du hast einen Feature-Wunsch? Joey freut sich über jede <a href="http://www.wunsch-koala.de/kontakt" target="_blank">Nachricht</a> und antwortet garantiert.</p>
		
		<form action="options.php" method="post">
		<?php settings_fields('wunsch-koala-options'); ?>
		<?php do_settings_sections('wunsch-koala-options'); ?>
		<p><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
		</form>
		
		</div>
		<?php
	}
	
	static public function affiliateSection() {
		echo '<p>Wenn du am <a href="http://www.wunsch-koala.de/affiliates/infos" target="_blank">Wunsch Koala Affiliate Programm</a> teilnimmst, kannst du hier deine Affiliate Id eintragen. Du findest die Id auf der Übersicht Seite im Affiliate Bereich. Sowohl die Links per Shortcode, als auch die Autolinks werden dann automatisch in Affiliate Links umgewandelt.</p>';
	}
	
	static public function affiliateIdField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['affiliate-id'])) $options['affiliate-id'] = '';
		echo "<input type='text' name='wunsch-koala-options[affiliate-id]' value='".$options['affiliate-id']."' /> ";
	}
	
	
	static public function autolinkSection() {
		echo '<p>Die Autolinks sind vor allem für Affiliate interessant. So kannst du z.B. automatisch nach jedem Amazon Link einen Wunsch-Link einfügen.</p>';
	}
	
	static public function autolinkActiveField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-active'])) $options['autolink-active'] = 0;
		$checked = $options['autolink-active'] ? ' checked' : '';
		echo "<input type='checkbox' name='wunsch-koala-options[autolink-active]' value='1' ".$checked." /> ";
	}
	
	static public function autolinkDomainsField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-domains'])) $options['autolink-domains'] = '';
		echo "<input type='text' name='wunsch-koala-options[autolink-domains]' value='".$options['autolink-domains']."' /> ";
	}
	
	static public function autolinkHtmlBeforeField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-html-before'])) $options['autolink-html-before'] = '';
		echo "<input type='text' name='wunsch-koala-options[autolink-html-before]' value='".esc_attr($options['autolink-html-before'])."' /> ";
	}
	
	static public function autolinkLinktextField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-linktext'])) $options['autolink-linktext'] = '';
		echo "<input type='text' name='wunsch-koala-options[autolink-linktext]' value='".esc_attr($options['autolink-linktext'])."' /> ";
	}
	
	static public function autolinkWishNameField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-wish-name'])) $options['autolink-wish-name'] = 'target';
		echo "
			<select name='wunsch-koala-options[autolink-wish-name]' >
				<option value='target' ".selected($options['autolink-wish-name'], "target", false).">Titel der Zielseite</option>
				<option value='source' ".selected($options['autolink-wish-name'], "source", false).">Titel der aktuellen Seite</option>
			</select>
		";
	}
	
	static public function autolinkWishLinkField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-wish-link'])) $options['autolink-wish-link'] = 'target';
		echo "
			<select name='wunsch-koala-options[autolink-wish-link]' >
				<option value='target' ".selected($options['autolink-wish-link'], "target", false).">URL der Zielseite</option>
				<option value='source' ".selected($options['autolink-wish-link'], "source", false).">URL der aktuellen Seite</option>
			</select>
		";
	}

	static public function autolinkHtmlAfterField() {
		$options = get_option('wunsch-koala-options');
		if (!isset($options['autolink-html-after'])) $options['autolink-html-after'] = '';
		echo "<input type='text' name='wunsch-koala-options[autolink-html-after]' value='".esc_attr($options['autolink-html-after'])."' /> ";
	}
	
	
	
	
	static public function optionsValidate($input) {
	
		if (is_numeric($input['affiliate-id'])) $whitelist['affiliate-id'] = $input['affiliate-id'];
		elseif (!empty($input['affiliate-id'])) add_settings_error('wunsch-koala-options', 'wunsch-koala-error-affiliate-id', 'Ungültige Affiliate Id. Die Id darf nur aus Zahlen bestehen. Du findest die Id auf der Übersicht-Seite im Affiliate Bereich.', 'error');
		
		$whitelist['autolink-active'] = $input['autolink-active'];
		if ($whitelist['autolink-active'] != 1) $whitelist['autolink-active'] = 0;
		
		/*
		$whitelist['autolink-wish-name'] = $input['autolink-wish-name'];
		if ($whitelist['autolink-wish-name'] != 'target') $whitelist['autolink-wish-name'] = 'source';
		
		$whitelist['autolink-wish-link'] = $input['autolink-wish-link'];
		if ($whitelist['autolink-wish-link'] != 'target') $whitelist['autolink-wish-link'] = 'source';
		*/
		
		if (!empty($input['autolink-domains'])) $whitelist['autolink-domains'] = esc_html($input['autolink-domains']);
		if (!empty($input['autolink-html-before'])) $whitelist['autolink-html-before'] = $input['autolink-html-before'];
		if (!empty($input['autolink-linktext'])) $whitelist['autolink-linktext'] = $input['autolink-linktext'];
		if (!empty($input['autolink-html-after'])) $whitelist['autolink-html-after'] = $input['autolink-html-after'];
		
	
		return $whitelist;
	}
		
	
	
	
		
}