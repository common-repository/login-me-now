<?php
/**
 * Extension Popup.
 *
 * @package Login Me Now
 * @since 1.3.3
 */

$current_user = wp_get_current_user();
$do_not_show  = get_user_meta( $current_user->ID, 'login_me_now_hide_save_to_browser_extension', true );

if ( $do_not_show ) {
	return;
}

$html = '<div class="lmnExt" id="lmnExt">';
$html .= sprintf( '<div id="lmnEmail" data-email="%s"></div>', esc_attr( $current_user->user_email ) );
$html .= sprintf( '<div id="lmnSiteUrl" data-siteurl="%s"></div>', esc_url( get_site_url() ) );
$html .= sprintf( '<div id="lmnSecurity" data-security="%s"></div>', esc_attr( wp_create_nonce( 'login_me_now_generate_token_nonce' ) ) );
$html .= sprintf( '<div id="lmnAjaxUrl" data-ajaxurl="%s"></div>', esc_url( admin_url( 'admin-ajax.php' ) ) );

$html .= '<div class="popup-content-wrapper">';
$html .= '<div style="display: flex; align-items: center; justify-content: center">';
$html .= '<p>';
$html .= esc_html( __( 'To enjoy effortless and quick access, save this dashboard login', 'login-me-now' ) );
$html .= '</p>';
$html .= sprintf(
	'<input type="datetime-local" id="lmnExpiration" name="lmnExpiration" min="%s" max="%s">',
	date( 'Y-m-d\TH:i' ),
	date( 'Y-m-d\TH:i', strtotime( '+5 year' ) )
);
$html .= sprintf(
	'<button id="lmn-save">%s %s</button>',
	__( 'Save Now', 'login-me-now' ),
	'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="18px" height="18px" fill-rule="nonzero"><g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,2c-12.6907,0 -23,10.3093 -23,23c0,12.69071 10.3093,23 23,23c12.69071,0 23,-10.30929 23,-23c0,-12.6907 -10.30929,-23 -23,-23zM25,4c7.98798,0 14.92565,4.44503 18.47656,11h-18.47656c-0.03784,-0.00019 -0.07566,0.00176 -0.11328,0.00586c-5.14785,0.05824 -9.37505,4.02951 -9.83984,9.07227l-7.01172,-11.42773c3.81644,-5.2367 9.97965,-8.65039 16.96484,-8.65039zM6.82422,14.50195l9.65234,15.72852c0.03315,0.05638 0.07174,0.10936 0.11523,0.1582c0.00065,0.0013 0.0013,0.00261 0.00195,0.00391c1.78145,2.76595 4.88117,4.60742 8.40625,4.60742c1.41306,0 2.75633,-0.29827 3.97656,-0.83008l-6.65625,11.6543c-10.34035,-1.31441 -18.32031,-10.12311 -18.32031,-20.82422c0,-3.83026 1.03757,-7.40847 2.82422,-10.49805zM25,17c4.43012,0 8,3.56988 8,8c0,1.42117 -0.37032,2.75159 -1.01562,3.90625c-0.00065,0.00065 -0.0013,0.0013 -0.00195,0.00195c-0.01238,0.01913 -0.02411,0.03867 -0.03516,0.05859c-1.37555,2.41054 -3.96378,4.0332 -6.94727,4.0332c-4.43012,0 -8,-3.56988 -8,-8c0,-4.43012 3.56988,-8 8,-8zM30.97266,17h13.44922c1.01492,2.46534 1.57813,5.16577 1.57813,8c0,11.60982 -9.39018,21 -21,21c-0.15783,0 -0.31371,-0.00827 -0.4707,-0.01172l9.1543,-16.0293c0.02207,-0.03774 0.04165,-0.07689 0.05859,-0.11719c0.79908,-1.43612 1.25781,-3.08613 1.25781,-4.8418c0,-3.27139 -1.58894,-6.17407 -4.02734,-8z"></path></g></g></svg>'
);

$html .= '</div>';
$html .= '<a id="lmn-later">' . esc_html__( "Don't show this again", 'login-me-now' ) . '</a>';

$html .= '</div>';
$html .= '</div>';
echo $html;
?>

<script>
	let lmnLater = document.getElementById('lmn-later');
	let lmnExt = document.querySelector("#lmnExt");

	setTimeout(() => {
		lmnExt.style.bottom = 0;
	}, 1000);

	if (lmnLater) {
		lmnLater.addEventListener('click', function (e) {

			const formData = new window.FormData();
			formData.append("action", "login_me_now_hide_save_to_browser_extension");
			postJSON(formData);

			lmnExt.style.bottom = '-60px';
			setTimeout(() => {
				lmnExt.style.setProperty('display', 'none', 'important');
			}, 1000);
		});

		async function postJSON(data) {
			try {
				const response = await fetch("<?php echo admin_url( 'admin-ajax.php' ) ?>", {
				method: "POST",
				body: data,
			});
				const result = await response.json();
			} catch (error) {
				console.error("Error:", error);
			}
		}
	}
</script>

<style>
	.lmnExt {
		display: none;
		position: fixed;
		width: 100%;
		height: auto;
		left: 0;
		bottom: -70px;
		background: #3A86FF;
		color: #fff;
		text-align: center;
		z-index: 999999999;
		justify-content: center;
		align-items: center;
		transition: bottom 1.0s ease;
	}
	.lmnExt p,
	.lmnExt a {
		font-size: 16px;
		margin-right: 10px;
		color: #fff;
		margin: 1em 0;
	}
	#lmn-later {
		cursor: pointer;
		border-bottom: 1px solid #fff;
		padding-bottom: 5px;
	}

	#lmnExt button {
		padding: 5px 15px;
		margin: 0 5px;
		cursor: pointer;
		border: 0;
		background: #1A3B66;
		text-transform: uppercase;
		border-radius: 5px;
		color: #fff;
		transition: .3s;
	}
	#lmnExt button:hover {
		background: #062247;
	}
	#lmnExt #lmn-save {
		color: #26FF75;
		display: flex;
		align-items: center;
		justify-content: center;
		columns: #26FF75;
		transition: 1.7s;
	}
	#lmn-save svg {
		margin-left: 5px;
	}
	#lmnExpiration {
		border: none;
		margin-left: 5px;
		color-scheme: dark;
		background: #1a3b66;
		color: white;
	}

	.popup-content-wrapper {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 90%;
	}
</style>