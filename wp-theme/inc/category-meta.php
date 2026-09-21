<?php
/**
 * "Cover image" for each category — a term meta field with its own media
 * picker on the Categories admin screens, since categories don't have a
 * built-in featured image the way posts do. Drives the Category Showcase
 * block and the category archive banner (archive.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hg_register_category_meta() {
	register_meta( 'term', 'hg_category_image', array(
		'object_subtype'    => 'category',
		'type'              => 'integer',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'absint',
		'auth_callback'     => function () {
			return current_user_can( 'manage_categories' );
		},
	) );
}
add_action( 'init', 'hg_register_category_meta' );

/**
 * "Add Category" screen: an empty picker (no term_id yet to attach media to
 * until the term is saved, so this just renders the control — the value is
 * picked up by hg_save_category_image() on create).
 */
function hg_category_add_image_field() {
	?>
	<div class="form-field">
		<label for="hg_category_image_id"><?php esc_html_e( 'Cover Image', 'healthgists' ); ?></label>
		<div id="hg-category-image-preview" style="margin-bottom:8px;"></div>
		<input type="hidden" name="hg_category_image_id" id="hg_category_image_id" value="" />
		<button type="button" class="button" id="hg-category-image-select"><?php esc_html_e( 'Select Image', 'healthgists' ); ?></button>
		<button type="button" class="button" id="hg-category-image-remove" style="display:none;"><?php esc_html_e( 'Remove', 'healthgists' ); ?></button>
		<p><?php esc_html_e( 'Shown on the category archive banner and in the Category Showcase block.', 'healthgists' ); ?></p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'hg_category_add_image_field' );

/**
 * "Edit Category" screen: same control, pre-filled with the current image.
 */
function hg_category_edit_image_field( $term ) {
	$image_id  = get_term_meta( $term->term_id, 'hg_category_image', true );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'hg-card' ) : '';
	?>
	<tr class="form-field">
		<th scope="row"><label for="hg_category_image_id"><?php esc_html_e( 'Cover Image', 'healthgists' ); ?></label></th>
		<td>
			<div id="hg-category-image-preview" style="margin-bottom:8px;">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" style="max-width:300px;height:auto;display:block;" />
				<?php endif; ?>
			</div>
			<input type="hidden" name="hg_category_image_id" id="hg_category_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
			<button type="button" class="button" id="hg-category-image-select"><?php esc_html_e( 'Select Image', 'healthgists' ); ?></button>
			<button type="button" class="button" id="hg-category-image-remove" <?php echo $image_url ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'healthgists' ); ?></button>
			<p class="description"><?php esc_html_e( 'Shown on the category archive banner and in the Category Showcase block.', 'healthgists' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'hg_category_edit_image_field' );

function hg_save_category_image( $term_id ) {
	if ( ! isset( $_POST['hg_category_image_id'] ) ) {
		return;
	}
	// created_category/edited_category only fire after core has already
	// verified that screen's own nonce (add-tag, or update-category_$id) —
	// the capability check here is the same belt-and-braces check core's
	// own term meta boxes use, not a substitute for that already-done check.
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$image_id = absint( $_POST['hg_category_image_id'] );
	if ( $image_id ) {
		update_term_meta( $term_id, 'hg_category_image', $image_id );
	} else {
		delete_term_meta( $term_id, 'hg_category_image' );
	}
}
add_action( 'created_category', 'hg_save_category_image' );
add_action( 'edited_category', 'hg_save_category_image' );

/**
 * wp.media picker JS for the two screens above — same UX pattern as a
 * featured-image button, just targeting a hidden input instead of post meta.
 */
function hg_category_media_script( $hook ) {
	if ( 'edit-tags.php' !== $hook && 'term.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'category' !== $screen->taxonomy ) {
		return;
	}
	wp_enqueue_media();
	$inline = <<<JS
	jQuery(function ($) {
		var frame;
		$('#hg-category-image-select').on('click', function (e) {
			e.preventDefault();
			if (frame) { frame.open(); return; }
			frame = wp.media({
				title: 'Select Cover Image',
				multiple: false,
				library: { type: 'image' },
				button: { text: 'Use this image' },
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				var url = (attachment.sizes && attachment.sizes.medium) ? attachment.sizes.medium.url : attachment.url;
				$('#hg_category_image_id').val(attachment.id);
				$('#hg-category-image-preview').html('<img src="' + url + '" style="max-width:300px;height:auto;display:block;" />');
				$('#hg-category-image-remove').show();
			});
			frame.open();
		});
		$('#hg-category-image-remove').on('click', function (e) {
			e.preventDefault();
			$('#hg_category_image_id').val('');
			$('#hg-category-image-preview').html('');
			$(this).hide();
		});
	});
JS;
	wp_add_inline_script( 'media-editor', $inline );
}
add_action( 'admin_enqueue_scripts', 'hg_category_media_script' );
