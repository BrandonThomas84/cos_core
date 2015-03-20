<?php

function paupress_helper_state() {
	$states = apply_filters( 'paupress_helper_state', paupress_get_helper_states() );
	$countries = paupress_helper_country();
	foreach ( $states as $key => $val ) {
		if ( isset( $countries[$key] ) )
			$new_states[$countries[$key]] = $val;
	}
	return $new_states;

}

function paupress_merge_helper_locs() {
	$states = apply_filters( 'paupress_merge_helper_states', paupress_get_helper_states() );
	$countries = paupress_helper_country();
	foreach ( $countries as $key => $val ) {
		if ( isset( $states[$key] ) ) {
			foreach( $states[$key] as $skey => $sval ) {
				if ( is_array( $sval ) ) {
					foreach ( $sval as $svalkey => $svalval ) {
						$new_states[$key.'_'.$svalkey] = $svalval;
					}
				} else {
					$new_states[$key.'_'.$skey] = $sval;
				}
			}
			$new_locs[$key] = $new_states;
			unset($new_states);
		} else {
			$new_locs[$key] = $val;
		}
	}
	return $new_locs;
}

function paupress_get_helper_states() {
	
	// OZ -- NOTE: WE'VE MODIFIED SOUTH AUSTRALIA, WESTERN AUSTRALIA AND NORTHERN TERRITORY TO PREVENT CONFLICTS AND STANDARDIZE THE ABBREVIATIONS
	return apply_filters( 'paupress_get_helper_states', array(
		'AU' => array(
			'ACT' => __('Australian Capital Territory', 'paupress'), 
			'NSW' => __('New South Wales', 'paupress'), 
			'NTA' => __('Northern Territory', 'paupress'), 
			'QLD' => __('Queensland', 'paupress'), 
			'SAA' => __('South Australia', 'paupress'), 
			'TAS' => __('Tasmania', 'paupress'), 
			'VIC' => __('Victoria', 'paupress'), 
			'WAA' => __('Western Australia', 'paupress'),
		),
		'CA' => array(
			'AB' => __('Alberta', 'paupress'), 
			'BC' => __('British Columbia', 'paupress'), 
			'MB' => __('Manitoba', 'paupress'), 
			'NB' => __('New Brunswick', 'paupress'), 
			'NF' => __('Newfoundland', 'paupress'), 
			'NT' => __('Northwest Territories', 'paupress'), 
			'NS' => __('Nova Scotia', 'paupress'), 
			'NU' => __('Nunavut', 'paupress'), 
			'ON' => __('Ontario', 'paupress'), 
			'PE' => __('Prince Edward Island', 'paupress'), 
			'QC' => __('Quebec', 'paupress'), 
			'SK' => __('Saskatchewan', 'paupress'), 
			'YT' => __('Yukon Territory', 'paupress'),
		),
		'US' => array(
			'AL' => __('Alabama', 'paupress'), 
			'AK' => __('Alaska', 'paupress'), 
			'AZ' => __('Arizona', 'paupress'), 
			'AR' => __('Arkansas', 'paupress'), 
			'CA' => __('California', 'paupress'), 
			'CO' => __('Colorado', 'paupress'), 
			'CT' => __('Connecticut', 'paupress'), 
			'DE' => __('Delaware', 'paupress'), 
			'DC' => __('District Of Columbia', 'paupress'), 
			'FL' => __('Florida', 'paupress'), 
			'GA' => __('Georgia', 'paupress'), 
			'HI' => __('Hawaii', 'paupress'), 
			'ID' => __('Idaho', 'paupress'), 
			'IL' => __('Illinois', 'paupress'), 
			'IN' => __('Indiana', 'paupress'), 
			'IA' => __('Iowa', 'paupress'), 
			'KS' => __('Kansas', 'paupress'), 
			'KY' => __('Kentucky', 'paupress'), 
			'LA' => __('Louisiana', 'paupress'), 
			'ME' => __('Maine', 'paupress'), 
			'MD' => __('Maryland', 'paupress'), 
			'MA' => __('Massachusetts', 'paupress'), 
			'MI' => __('Michigan', 'paupress'), 
			'MN' => __('Minnesota', 'paupress'), 
			'MS' => __('Mississippi', 'paupress'), 
			'MO' => __('Missouri', 'paupress'), 
			'MT' => __('Montana', 'paupress'), 
			'NE' => __('Nebraska', 'paupress'), 
			'NV' => __('Nevada', 'paupress'), 
			'NH' => __('New Hampshire', 'paupress'), 
			'NJ' => __('New Jersey', 'paupress'), 
			'NM' => __('New Mexico', 'paupress'), 
			'NY' => __('New York', 'paupress'), 
			'NC' => __('North Carolina', 'paupress'), 
			'ND' => __('North Dakota', 'paupress'), 
			'OH' => __('Ohio', 'paupress'), 
			'OK' => __('Oklahoma', 'paupress'), 
			'OR' => __('Oregon', 'paupress'), 
			'PA' => __('Pennsylvania', 'paupress'), 
			'RI' => __('Rhode Island', 'paupress'), 
			'SC' => __('South Carolina', 'paupress'), 
			'SD' => __('South Dakota', 'paupress'), 
			'TN' => __('Tennessee', 'paupress'), 
			'TX' => __('Texas', 'paupress'), 
			'UT' => __('Utah', 'paupress'), 
			'VT' => __('Vermont', 'paupress'), 
			'VA' => __('Virginia', 'paupress'), 
			'WA' => __('Washington', 'paupress'), 
			'WV' => __('West Virginia', 'paupress'), 
			'WI' => __('Wisconsin', 'paupress'), 
			'WY' => __('Wyoming', 'paupress'),
			'AA' => __('Armed Forces Americas', 'paupress'), 
			'AE' => __('Armed Forces Europe', 'paupress'), 
			'AP' => __('Armed Forces Pacific', 'paupress'),
			'AS' => __('American Samoa', 'paupress'),
			'FM' => __('Micronesia', 'paupress'),
			'GU' => __('Guam', 'paupress'),
			'MH' => __('Marshall Islands', 'paupress'),
			'PR' => __('Puerto Rico', 'paupress'),
			'VI' => __('U.S. Virgin Islands', 'paupress'),
		),
	) );
	
}

function paupress_helper_country() {
	
	return apply_filters( 'paupress_helper_country', array(
				'AF' => __('Afghanistan', 'paupress'),
				'AX' => __('&#197;land Islands', 'paupress'),
				'AL' => __('Albania', 'paupress'),
				'DZ' => __('Algeria', 'paupress'),
				'AD' => __('Andorra', 'paupress'),
				'AO' => __('Angola', 'paupress'),
				'AI' => __('Anguilla', 'paupress'),
				'AQ' => __('Antarctica', 'paupress'),
				'AG' => __('Antigua and Barbuda', 'paupress'),
				'AR' => __('Argentina', 'paupress'),
				'AM' => __('Armenia', 'paupress'),
				'AW' => __('Aruba', 'paupress'),
				'AU' => __('Australia', 'paupress'),
				'AT' => __('Austria', 'paupress'),
				'AZ' => __('Azerbaijan', 'paupress'),
				'BS' => __('Bahamas', 'paupress'),
				'BH' => __('Bahrain', 'paupress'),
				'BD' => __('Bangladesh', 'paupress'),
				'BB' => __('Barbados', 'paupress'),
				'BY' => __('Belarus', 'paupress'),
				'BE' => __('Belgium', 'paupress'),
				'BZ' => __('Belize', 'paupress'),
				'BJ' => __('Benin', 'paupress'),
				'BM' => __('Bermuda', 'paupress'),
				'BT' => __('Bhutan', 'paupress'),
				'BO' => __('Bolivia', 'paupress'),
				'BA' => __('Bosnia and Herzegovina', 'paupress'),
				'BW' => __('Botswana', 'paupress'),
				'BR' => __('Brazil', 'paupress'),
				'IO' => __('British Indian Ocean Territory', 'paupress'),
				'VG' => __('British Virgin Islands', 'paupress'),
				'BN' => __('Brunei', 'paupress'),
				'BG' => __('Bulgaria', 'paupress'),
				'BF' => __('Burkina Faso', 'paupress'),
				'BI' => __('Burundi', 'paupress'),
				'KH' => __('Cambodia', 'paupress'),
				'CM' => __('Cameroon', 'paupress'),
				'CA' => __('Canada', 'paupress'),
				'CV' => __('Cape Verde', 'paupress'),
				'KY' => __('Cayman Islands', 'paupress'),
				'CF' => __('Central African Republic', 'paupress'),
				'TD' => __('Chad', 'paupress'),
				'CL' => __('Chile', 'paupress'),
				'CN' => __('China', 'paupress'),
				'CX' => __('Christmas Island', 'paupress'),
				'CC' => __('Cocos (Keeling) Islands', 'paupress'),
				'CO' => __('Colombia', 'paupress'),
				'KM' => __('Comoros', 'paupress'),
				'CG' => __('Congo (Brazzaville)', 'paupress'),
				'CD' => __('Congo (Kinshasa)', 'paupress'),
				'CK' => __('Cook Islands', 'paupress'),
				'CR' => __('Costa Rica', 'paupress'),
				'HR' => __('Croatia', 'paupress'),
				'CU' => __('Cuba', 'paupress'),
				'CY' => __('Cyprus', 'paupress'),
				'CZ' => __('Czech Republic', 'paupress'),
				'DK' => __('Denmark', 'paupress'),
				'DJ' => __('Djibouti', 'paupress'),
				'DM' => __('Dominica', 'paupress'),
				'DO' => __('Dominican Republic', 'paupress'),
				'EC' => __('Ecuador', 'paupress'),
				'EG' => __('Egypt', 'paupress'),
				'SV' => __('El Salvador', 'paupress'),
				'GQ' => __('Equatorial Guinea', 'paupress'),
				'ER' => __('Eritrea', 'paupress'),
				'EE' => __('Estonia', 'paupress'),
				'ET' => __('Ethiopia', 'paupress'),
				'FK' => __('Falkland Islands', 'paupress'),
				'FO' => __('Faroe Islands', 'paupress'),
				'FJ' => __('Fiji', 'paupress'),
				'FI' => __('Finland', 'paupress'),
				'FR' => __('France', 'paupress'),
				'GF' => __('French Guiana', 'paupress'),
				'PF' => __('French Polynesia', 'paupress'),
				'TF' => __('French Southern Territories', 'paupress'),
				'GA' => __('Gabon', 'paupress'),
				'GM' => __('Gambia', 'paupress'),
				'GE' => __('Georgia', 'paupress'),
				'DE' => __('Germany', 'paupress'),
				'GH' => __('Ghana', 'paupress'),
				'GI' => __('Gibraltar', 'paupress'),
				'GR' => __('Greece', 'paupress'),
				'GL' => __('Greenland', 'paupress'),
				'GD' => __('Grenada', 'paupress'),
				'GP' => __('Guadeloupe', 'paupress'),
				'GT' => __('Guatemala', 'paupress'),
				'GG' => __('Guernsey', 'paupress'),
				'GN' => __('Guinea', 'paupress'),
				'GW' => __('Guinea-Bissau', 'paupress'),
				'GY' => __('Guyana', 'paupress'),
				'HT' => __('Haiti', 'paupress'),
				'HN' => __('Honduras', 'paupress'),
				'HK' => __('Hong Kong', 'paupress'),
				'HU' => __('Hungary', 'paupress'),
				'IS' => __('Iceland', 'paupress'),
				'IN' => __('India', 'paupress'),
				'ID' => __('Indonesia', 'paupress'),
				'IR' => __('Iran', 'paupress'),
				'IQ' => __('Iraq', 'paupress'),
				'IE' => __('Republic of Ireland', 'paupress'),
				'IM' => __('Isle of Man', 'paupress'),
				'IL' => __('Israel', 'paupress'),
				'IT' => __('Italy', 'paupress'),
				'CI' => __('Ivory Coast', 'paupress'),
				'JM' => __('Jamaica', 'paupress'),
				'JP' => __('Japan', 'paupress'),
				'JE' => __('Jersey', 'paupress'),
				'JO' => __('Jordan', 'paupress'),
				'KZ' => __('Kazakhstan', 'paupress'),
				'KE' => __('Kenya', 'paupress'),
				'KI' => __('Kiribati', 'paupress'),
				'KW' => __('Kuwait', 'paupress'),
				'KG' => __('Kyrgyzstan', 'paupress'),
				'LA' => __('Laos', 'paupress'),
				'LV' => __('Latvia', 'paupress'),
				'LB' => __('Lebanon', 'paupress'),
				'LS' => __('Lesotho', 'paupress'),
				'LR' => __('Liberia', 'paupress'),
				'LY' => __('Libya', 'paupress'),
				'LI' => __('Liechtenstein', 'paupress'),
				'LT' => __('Lithuania', 'paupress'),
				'LU' => __('Luxembourg', 'paupress'),
				'MO' => __('Macao S.A.R., China', 'paupress'),
				'MK' => __('Macedonia', 'paupress'),
				'MG' => __('Madagascar', 'paupress'),
				'MW' => __('Malawi', 'paupress'),
				'MY' => __('Malaysia', 'paupress'),
				'MV' => __('Maldives', 'paupress'),
				'ML' => __('Mali', 'paupress'),
				'MT' => __('Malta', 'paupress'),
				'MQ' => __('Martinique', 'paupress'),
				'MR' => __('Mauritania', 'paupress'),
				'MU' => __('Mauritius', 'paupress'),
				'YT' => __('Mayotte', 'paupress'),
				'MX' => __('Mexico', 'paupress'),
				'MD' => __('Moldova', 'paupress'),
				'MC' => __('Monaco', 'paupress'),
				'MN' => __('Mongolia', 'paupress'),
				'ME' => __('Montenegro', 'paupress'),
				'MS' => __('Montserrat', 'paupress'),
				'MA' => __('Morocco', 'paupress'),
				'MZ' => __('Mozambique', 'paupress'),
				'MM' => __('Myanmar', 'paupress'),
				'NA' => __('Namibia', 'paupress'),
				'NR' => __('Nauru', 'paupress'),
				'NP' => __('Nepal', 'paupress'),
				'NL' => __('Netherlands', 'paupress'),
				'AN' => __('Netherlands Antilles', 'paupress'),
				'NC' => __('New Caledonia', 'paupress'),
				'NZ' => __('New Zealand', 'paupress'),
				'NI' => __('Nicaragua', 'paupress'),
				'NE' => __('Niger', 'paupress'),
				'NG' => __('Nigeria', 'paupress'),
				'NU' => __('Niue', 'paupress'),
				'NF' => __('Norfolk Island', 'paupress'),
				'KP' => __('North Korea', 'paupress'),
				'MP' => __('Northern Mariana Islands', 'paupress'),
				'NO' => __('Norway', 'paupress'),
				'OM' => __('Oman', 'paupress'),
				'PK' => __('Pakistan', 'paupress'),
				'PW' => __('Palau', 'paupress'),
				'PS' => __('Palestinian Territory', 'paupress'),
				'PA' => __('Panama', 'paupress'),
				'PG' => __('Papua New Guinea', 'paupress'),
				'PY' => __('Paraguay', 'paupress'),
				'PE' => __('Peru', 'paupress'),
				'PH' => __('Philippines', 'paupress'),
				'PN' => __('Pitcairn', 'paupress'),
				'PL' => __('Poland', 'paupress'),
				'PT' => __('Portugal', 'paupress'),
				'QA' => __('Qatar', 'paupress'),
				'RE' => __('Reunion', 'paupress'),
				'RO' => __('Romania', 'paupress'),
				'RU' => __('Russia', 'paupress'),
				'RW' => __('Rwanda', 'paupress'),
				'BL' => __('Saint Barth&eacute;lemy', 'paupress'),
				'SH' => __('Saint Helena', 'paupress'),
				'KN' => __('Saint Kitts and Nevis', 'paupress'),
				'LC' => __('Saint Lucia', 'paupress'),
				'MF' => __('Saint Martin (French part)', 'paupress'),
				'PM' => __('Saint Pierre and Miquelon', 'paupress'),
				'VC' => __('Saint Vincent and the Grenadines', 'paupress'),
				'WS' => __('Samoa', 'paupress'),
				'SM' => __('San Marino', 'paupress'),
				'ST' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'paupress'),
				'SA' => __('Saudi Arabia', 'paupress'),
				'SN' => __('Senegal', 'paupress'),
				'RS' => __('Serbia', 'paupress'),
				'SC' => __('Seychelles', 'paupress'),
				'SL' => __('Sierra Leone', 'paupress'),
				'SG' => __('Singapore', 'paupress'),
				'SK' => __('Slovakia', 'paupress'),
				'SI' => __('Slovenia', 'paupress'),
				'SB' => __('Solomon Islands', 'paupress'),
				'SO' => __('Somalia', 'paupress'),
				'ZA' => __('South Africa', 'paupress'),
				'GS' => __('South Georgia/Sandwich Islands', 'paupress'),
				'KR' => __('South Korea', 'paupress'),
				'ES' => __('Spain', 'paupress'),
				'LK' => __('Sri Lanka', 'paupress'),
				'SD' => __('Sudan', 'paupress'),
				'SR' => __('Suriname', 'paupress'),
				'SJ' => __('Svalbard and Jan Mayen', 'paupress'),
				'SZ' => __('Swaziland', 'paupress'),
				'SE' => __('Sweden', 'paupress'),
				'CH' => __('Switzerland', 'paupress'),
				'SY' => __('Syria', 'paupress'),
				'TW' => __('Taiwan', 'paupress'),
				'TJ' => __('Tajikistan', 'paupress'),
				'TZ' => __('Tanzania', 'paupress'),
				'TH' => __('Thailand', 'paupress'),
				'TL' => __('Timor-Leste', 'paupress'),
				'TG' => __('Togo', 'paupress'),
				'TK' => __('Tokelau', 'paupress'),
				'TO' => __('Tonga', 'paupress'),
				'TT' => __('Trinidad and Tobago', 'paupress'),
				'TN' => __('Tunisia', 'paupress'),
				'TR' => __('Turkey', 'paupress'),
				'TM' => __('Turkmenistan', 'paupress'),
				'TC' => __('Turks and Caicos Islands', 'paupress'),
				'TV' => __('Tuvalu', 'paupress'),
				'UM' => __('US Minor Outlying Islands', 'paupress'),
				'UG' => __('Uganda', 'paupress'),
				'UA' => __('Ukraine', 'paupress'),
				'AE' => __('United Arab Emirates', 'paupress'),
				'GB' => __('United Kingdom', 'paupress'),
				'US' => __('United States', 'paupress'),
				'UY' => __('Uruguay', 'paupress'),
				'UZ' => __('Uzbekistan', 'paupress'),
				'VU' => __('Vanuatu', 'paupress'),
				'VA' => __('Vatican', 'paupress'),
				'VE' => __('Venezuela', 'paupress'),
				'VN' => __('Vietnam', 'paupress'),
				'WF' => __('Wallis and Futuna', 'paupress'),
				'EH' => __('Western Sahara', 'paupress'),
				'YE' => __('Yemen', 'paupress'),
				'ZM' => __('Zambia', 'paupress'),
				'ZW' => __('Zimbabwe', 'paupress'),
	) );
	
}


function paupress_dropdown_roles( $selected = false ) {
	$p = '';
	$r = '';

	$editable_roles = get_editable_roles();

	foreach ( $editable_roles as $role => $details ) {
		$name = translate_user_role( $details['name'] );
		if ( is_array( $selected ) ) {
			if ( in_array( $role, $selected ) ) // preselect specified role
				$p .= "\n\t<option selected='selected' value='" . esc_attr($role) . "'>$name</option>";
			else
				$r .= "\n\t<option value='" . esc_attr($role) . "'>$name</option>";
		} else {
			if ( $selected == $role ) // preselect specified role
				$p = "\n\t<option selected='selected' value='" . esc_attr($role) . "'>$name</option>";
			else
				$r .= "\n\t<option value='" . esc_attr($role) . "'>$name</option>";
		}
	}
	echo $p . $r;
}


function paupress_get_days() {
	return array( '01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31' );
}

function paupress_get_months() {
	return array( 
					'01' => __( 'January' ), 
					'02' => __( 'February' ), 
					'03' => __( 'March' ), 
					'04' => __( 'April' ), 
					'05' => __( 'May' ), 
					'06' => __( 'June' ), 
					'07' => __( 'July' ), 
					'08' => __( 'August' ), 
					'09' => __( 'September' ), 
					'10' => __( 'October' ), 
					'11' => __( 'November' ), 
					'12' => __( 'December' ),  
	);
}