<?php
/**
 * One command, full first-time site setup: creates the 4 categories (with
 * cover photos), the 4 sample posts, the Home/About/Contact/Blog pages,
 * builds Home + About content from the theme's custom blocks, and sets
 * Reading options. Safe to re-run — everything is create-if-missing except
 * Home/About content, which overwrites every run (same pattern as
 * phf-ogun's bin/seed-site.php).
 *
 * Run on your host over SSH, from the WordPress root:
 *   wp eval-file wp-content/themes/healthgists/bin/seed-site.php
 *
 * Not autoloaded by the theme — a one-off setup utility.
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit( "Run this with WP-CLI: wp eval-file bin/seed-site.php\n" );
}

$theme_dir = get_template_directory();

/* ------------------------------------------------------------------ *
 * Small builders for Gutenberg block markup — every string here is what
 * an admin would get by inserting these blocks by hand in the editor;
 * this just does it programmatically for the starter content. Ported
 * from phf-ogun's bin/seed-content.php helpers.
 * ------------------------------------------------------------------ */

function hg_seed_json( $data ) {
	return wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}

function hg_seed_paragraph( $text ) {
	return "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $text ) . "</p>\n<!-- /wp:paragraph -->\n";
}

function hg_seed_heading( $text, $level = 2 ) {
	return "<!-- wp:heading {\"level\":{$level}} -->\n<h{$level} class=\"wp-block-heading\">" . esc_html( $text ) . "</h{$level}>\n<!-- /wp:heading -->\n";
}

function hg_seed_quote( $text ) {
	return "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\">\n<!-- wp:paragraph -->\n<p>" . wp_kses_post( $text ) . "</p>\n<!-- /wp:paragraph -->\n</blockquote>\n<!-- /wp:quote -->\n";
}

function hg_seed_list( $items ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= '<!-- wp:list-item --><li>' . wp_kses_post( $item ) . "</li><!-- /wp:list-item -->\n";
	}
	return "<!-- wp:list -->\n<ul class=\"wp-block-list\">\n{$li}</ul>\n<!-- /wp:list -->\n";
}

function hg_seed_table( $rows ) {
	$html = '';
	foreach ( $rows as $i => $row ) {
		$tag   = 0 === $i ? 'th' : 'td';
		$cells = '';
		foreach ( $row as $cell ) {
			$cells .= "<{$tag}>" . esc_html( $cell ) . "</{$tag}>";
		}
		$html .= "<tr>{$cells}</tr>\n";
	}
	return "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><tbody>\n{$html}</tbody></table></figure>\n<!-- /wp:table -->\n";
}

function hg_seed_hero_slide_manual( $eyebrow, $title, $subtitle, $image_id, $image_url ) {
	$attrs = hg_seed_json( array(
		'linkedPostId' => 0,
		'eyebrow'      => $eyebrow,
		'title'        => $title,
		'subtitle'     => $subtitle,
		'imageId'      => $image_id,
		'imageUrl'     => $image_url,
	) );
	return "<!-- wp:healthgists/hero-slide {$attrs} /-->\n";
}

function hg_seed_hero_slide_linked( $post_id ) {
	$attrs = hg_seed_json( array( 'linkedPostId' => $post_id ) );
	return "<!-- wp:healthgists/hero-slide {$attrs} /-->\n";
}

function hg_seed_hero_slider( $slides ) {
	return "<!-- wp:healthgists/hero-slider -->\n<div class=\"bg-ink p-6 rounded-2xl\">\n" . implode( '', $slides ) . "</div>\n<!-- /wp:healthgists/hero-slider -->\n";
}

function hg_seed_category_card( $term_id ) {
	$attrs = hg_seed_json( array( 'termId' => $term_id ) );
	return "<!-- wp:healthgists/category-card {$attrs} /-->\n";
}

function hg_seed_category_showcase( $cards, $args = array() ) {
	$attrs = hg_seed_json( array_merge( array(
		'columns'  => 4,
		'eyebrow'  => '',
		'heading'  => '',
		'variant'  => 'light',
		'linkText' => '',
		'linkUrl'  => '',
	), $args ) );
	return "<!-- wp:healthgists/category-showcase {$attrs} -->\n" . implode( '', $cards ) . "<!-- /wp:healthgists/category-showcase -->\n";
}

function hg_seed_value_prop_item( $icon, $title, $body, $tint ) {
	$attrs = hg_seed_json( array( 'icon' => $icon, 'title' => $title, 'body' => $body, 'tint' => $tint ) );
	return "<!-- wp:healthgists/value-prop-item {$attrs} /-->\n";
}

function hg_seed_value_prop_grid( $items, $args = array() ) {
	$attrs = hg_seed_json( array_merge( array(
		'eyebrow' => '',
		'heading' => '',
		'variant' => 'light',
	), $args ) );
	return "<!-- wp:healthgists/value-prop-grid {$attrs} -->\n" . implode( '', $items ) . "<!-- /wp:healthgists/value-prop-grid -->\n";
}

function hg_seed_process_step( $number, $title, $body ) {
	$attrs = hg_seed_json( array( 'number' => $number, 'title' => $title, 'body' => $body ) );
	return "<!-- wp:healthgists/process-step {$attrs} /-->\n";
}

function hg_seed_process_steps( $steps ) {
	return "<!-- wp:healthgists/process-steps -->\n" . implode( '', $steps ) . "<!-- /wp:healthgists/process-steps -->\n";
}

/**
 * Import an image from the theme's bundled assets/images/covers/ into the
 * Media Library, deduped by title match on re-run. Ported from phf-ogun's
 * phf_seed_get_or_import_image().
 */
function hg_seed_get_or_import_image( $filename, $theme_dir ) {
	$existing_query = new WP_Query( array(
		'post_type'              => 'attachment',
		'post_status'            => 'inherit',
		'title'                  => $filename,
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );
	if ( $existing_query->have_posts() ) {
		$existing_id = $existing_query->posts[0]->ID;
		return array( $existing_id, wp_get_attachment_url( $existing_id ) );
	}
	$path = $theme_dir . '/assets/images/covers/' . $filename;
	if ( ! file_exists( $path ) ) {
		return array( 0, '' );
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$tmp        = wp_tempnam( $filename );
	copy( $path, $tmp );
	$file_array = array( 'name' => $filename, 'tmp_name' => $tmp );
	$id         = media_handle_sideload( $file_array, 0, $filename );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( 'Could not import ' . $filename . ': ' . $id->get_error_message() );
		return array( 0, '' );
	}
	return array( $id, wp_get_attachment_url( $id ) );
}

/* ------------------------------------------------------------------ *
 * 1. Categories — 4 topics, each with a description (used as the
 *    category-archive/card blurb) and a cover photo (hg_category_image
 *    term meta, used by archive.php + the Category Showcase block).
 * ------------------------------------------------------------------ */

$categories = array(
	'preventive-care'          => array(
		'name'   => 'Preventive Care',
		'blurb'  => 'Screenings, checkups, and the early-warning signs worth acting on.',
		'cover'  => 'checkup-photo.jpg',
	),
	'genetics-family-health'   => array(
		'name'  => 'Genetics & Family Health',
		'blurb' => 'Genotype, hereditary risk, and what to know before starting a family.',
		'cover' => 'genotype-photo.jpg',
	),
	'mental-health'            => array(
		'name'  => 'Mental Health',
		'blurb' => 'Mind and mood, treated with the same seriousness as the body.',
		'cover' => 'mentalhealth-photo.jpg',
	),
	'nutrition-wellness'       => array(
		'name'  => 'Nutrition & Wellness',
		'blurb' => 'Food, habits, and everyday choices that move your numbers in the right direction.',
		'cover' => 'nutrition-photo.jpg',
	),
);

$category_ids = array();

foreach ( $categories as $slug => $data ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( ! $term ) {
		$result = wp_insert_term( $data['name'], 'category', array(
			'slug'        => $slug,
			'description' => $data['blurb'],
		) );
		if ( is_wp_error( $result ) ) {
			WP_CLI::warning( "Could not create category '{$slug}': " . $result->get_error_message() );
			continue;
		}
		$term_id = $result['term_id'];
		WP_CLI::success( "Created category '{$slug}' (#{$term_id})." );
	} else {
		$term_id = $term->term_id;
		WP_CLI::log( "Category '{$slug}' already exists (#{$term_id}) — leaving it alone." );
	}
	$category_ids[ $slug ] = $term_id;

	if ( ! get_term_meta( $term_id, 'hg_category_image', true ) ) {
		list( $image_id ) = hg_seed_get_or_import_image( $data['cover'], $theme_dir );
		if ( $image_id ) {
			update_term_meta( $term_id, 'hg_category_image', $image_id );
		}
	}
}

/* ------------------------------------------------------------------ *
 * 2. Posts — the 4 sample articles, content ported from the static
 *    prototype's bin/generate.py POSTS list into Gutenberg blocks.
 * ------------------------------------------------------------------ */

$posts = array(
	'5-early-warning-signs-you-shouldnt-ignore'                 => array(
		'title'    => "5 Early Warning Signs You Shouldn’t Ignore",
		'excerpt'  => "Fatigue, thirst, and a few other “minor” symptoms are often the body’s earliest way of flagging something worth a proper test.",
		'category' => 'preventive-care',
		'cover'    => 'checkup-photo.jpg',
		'content'  =>
			hg_seed_paragraph( "Most serious health conditions don’t announce themselves loudly. They start as symptoms easy to explain away — tiredness blamed on a busy week, thirst blamed on the heat, a headache blamed on screen time. The problem is that some of the most common chronic conditions in Nigeria today, from diabetes to hypertension to kidney disease, tend to be caught late precisely because their early signs look so ordinary." )
			. hg_seed_paragraph( 'Here are five symptoms worth taking seriously enough to get checked, not diagnosed by guesswork.' )
			. hg_seed_heading( "1. Persistent fatigue that doesn’t improve with rest" )
			. hg_seed_paragraph( 'Everyone gets tired. But fatigue that lingers for weeks, doesn’t improve after a good night’s sleep, or shows up even on light days can point to anaemia, thyroid issues, or early-stage diabetes. A Full Blood Count and fasting blood sugar test are usually the first, most affordable places to start.' )
			. hg_seed_heading( '2. Unusual thirst and frequent urination' )
			. hg_seed_paragraph( 'Drinking more water than usual and needing the bathroom more often — especially at night — is one of the earliest and most reliable signs of elevated blood sugar. This is often dismissed until symptoms become severe, by which point management is harder.' )
			. hg_seed_heading( '3. Unexplained weight change' )
			. hg_seed_paragraph( 'Losing or gaining weight without a change in diet or activity is the body signalling that something metabolic or hormonal is off. Thyroid function tests and a basic hormonal panel can usually narrow down the cause quickly.' )
			. hg_seed_heading( '4. Persistent headaches or dizziness' )
			. hg_seed_paragraph( 'Occasional headaches are normal. Headaches that are frequent, worse in the morning, or paired with dizziness can be an early sign of high blood pressure — a condition that, left unchecked, is one of the leading causes of stroke and kidney damage in Nigeria.' )
			. hg_seed_heading( '5. Slow-healing cuts or frequent infections' )
			. hg_seed_paragraph( "If small wounds are taking noticeably longer to heal, or you’re catching infections more often than usual, it’s worth having your blood sugar and immune markers checked. This is often one of the first visible signs of undiagnosed diabetes." )
			. hg_seed_heading( 'The bottom line' )
			. hg_seed_paragraph( "None of these symptoms are a diagnosis on their own — and that’s exactly the point. A simple panel of tests (often under ₦10,000) can turn a vague symptom into a clear answer, months or years before it becomes a harder problem to solve. Early detection remains the single most effective tool in preventive healthcare." )
			. hg_seed_quote( "If something in your body has felt “off” for more than two weeks, that’s reason enough to get it looked at — not a reason to wait until it gets worse." ),
	),
	'genotype-and-marriage-what-nigerian-couples-should-know'   => array(
		'title'    => "Genotype and Marriage: What Every Nigerian Couple Should Know Before Saying “I Do”",
		'excerpt'  => "A genotype test costs a fraction of what a wedding does — and it’s one of the few premarital checks that can change the course of a family’s health for generations.",
		'category' => 'genetics-family-health',
		'cover'    => 'genotype-photo.jpg',
		'content'  =>
			hg_seed_paragraph( "In much of Nigeria, genotype compatibility is a familiar phrase — mentioned at introductions, half-joked about, occasionally taken as seriously as it should be. But for a country with one of the highest burdens of sickle cell disease in the world, it deserves more than a passing mention." )
			. hg_seed_heading( 'What genotype actually measures' )
			. hg_seed_paragraph( "Your genotype describes the type of haemoglobin genes you carry — commonly AA, AS, AC, or SS. AA is considered the “normal” genotype with no sickle cell trait. AS and AC are carriers — generally healthy themselves, but able to pass the trait to children. SS is sickle cell disease itself, a lifelong condition involving pain crises, organ strain, and reduced life expectancy without proper management." )
			. hg_seed_heading( 'Why it matters most as a couple' )
			. hg_seed_paragraph( 'Genotype only becomes a serious risk in combination. Two AS carriers — each individually healthy — have a 25% chance, with every pregnancy, of having a child with SS (sickle cell disease). Neither partner may show any symptoms themselves, which is exactly why so many couples only discover compatibility risk after a child is already affected.' )
			. hg_seed_table( array(
				array( 'Combination', 'Risk to children' ),
				array( 'AA + AA', 'No risk of sickle cell disease' ),
				array( 'AA + AS', 'No SS risk, but 50% chance of carrying the trait' ),
				array( 'AS + AS', '25% chance of SS per pregnancy' ),
				array( 'AS + SS', '50% chance of SS per pregnancy' ),
				array( 'SS + SS', 'All children will have SS' ),
			) )
			. hg_seed_heading( "It’s not about stopping a marriage" )
			. hg_seed_paragraph( "Genotype testing isn’t about telling couples who they can or can’t marry — that’s a personal and often deeply emotional decision. It’s about removing guesswork from it. Couples who know their combined risk ahead of time can make informed choices: family planning options, early prenatal screening, or simply going in with full information instead of finding out after the fact." )
			. hg_seed_heading( 'The test itself is simple' )
			. hg_seed_paragraph( "A genotype test requires a small blood sample and typically returns results same-day. It’s one of the most affordable, highest-impact tests available — a single visit that can shape the health of an entire family line." )
			. hg_seed_quote( "Knowing your genotype doesn’t decide your relationship for you. It just makes sure the decision is an informed one." )
			. hg_seed_paragraph( "If you’re planning a wedding, or already married and haven’t had this conversation, there’s no better time than now — for you, and for whoever comes after you." ),
	),
	'why-mental-health-checkins-matter'                          => array(
		'title'    => 'Why Regular Mental Health Check-Ins Matter as Much as Physical Ones',
		'excerpt'  => 'We routinely check blood pressure and blood sugar. Mental health deserves the same rhythm of attention — not just a reaction to crisis.',
		'category' => 'mental-health',
		'cover'    => 'mentalhealth-photo.jpg',
		'content'  =>
			hg_seed_paragraph( "It’s common to schedule a physical checkup once a year — blood pressure, blood sugar, a general once-over. It’s far less common to give mental health the same routine attention, even though the evidence linking the two is strong: chronic stress raises blood pressure, poor sleep disrupts metabolism, and untreated anxiety or depression measurably worsens physical recovery from illness." )
			. hg_seed_heading( "Mental health isn’t just the absence of crisis" )
			. hg_seed_paragraph( 'A lot of us only think about mental health when something goes visibly wrong — a breakdown, a diagnosis, a crisis point. But mental health, like physical health, exists on a spectrum, and it responds well to the same principle: catching small shifts early is easier than managing a full-blown episode later.' )
			. hg_seed_heading( 'What a check-in can look like' )
			. hg_seed_list( array(
				'<strong>A conversation with a professional</strong> — even one session with a counsellor or therapist, unconnected to any specific crisis, can surface patterns worth addressing.',
				'<strong>Honest self-tracking</strong> — noticing patterns in sleep, appetite, irritability, or motivation over weeks, not just days.',
				'<strong>Physical markers</strong> — persistent fatigue, appetite changes, and sleep disruption are often physical symptoms with a mental health root.',
			) )
			. hg_seed_heading( 'The stigma is loosening, slowly' )
			. hg_seed_paragraph( "Mental healthcare in Nigeria still carries stigma that physical healthcare mostly doesn’t. But that’s changing, and normalising a regular check-in — the same way you’d normalise an annual blood test — is part of how it changes faster." )
			. hg_seed_quote( "You don’t need to be in crisis to justify checking in on your mental health. That’s exactly the point of a check-in." )
			. hg_seed_paragraph( "If it’s been a while since you’ve genuinely asked yourself how you’re doing — not the reflexive “fine,” but a real answer — that’s worth a conversation with someone qualified to help you unpack it." ),
	),
	'nigerian-foods-for-healthy-blood-pressure'                  => array(
		'title'    => '5 Nigerian Foods That Naturally Support Healthy Blood Pressure',
		'excerpt'  => "Managing blood pressure doesn’t require giving up local food — it requires knowing which everyday ingredients are already working in your favour.",
		'category' => 'nutrition-wellness',
		'cover'    => 'nutrition-photo.jpg',
		'content'  =>
			hg_seed_paragraph( 'Hypertension is one of the most common — and most under-diagnosed — conditions in Nigeria. The good news is that diet is one of the most controllable levers available, and several ingredients already common in Nigerian kitchens are genuinely useful for managing blood pressure, not just folklore.' )
			. hg_seed_heading( '1. Ugu (fluted pumpkin leaf)' )
			. hg_seed_paragraph( 'Rich in potassium and magnesium, both of which help the body regulate sodium balance and relax blood vessel walls. A regular pot of ugu soup, prepared with modest salt, is a genuinely solid dietary habit.' )
			. hg_seed_heading( '2. Garden egg' )
			. hg_seed_paragraph( 'Garden egg is high in fibre and contains compounds that support healthy cholesterol levels, which works alongside blood pressure management rather than against it.' )
			. hg_seed_heading( '3. Unripe plantain' )
			. hg_seed_paragraph( 'Lower on the glycaemic index than ripe plantain, and a good source of potassium. Boiled or roasted (not deep-fried) is the better preparation for blood pressure specifically.' )
			. hg_seed_heading( '4. Tiger nuts (aya)' )
			. hg_seed_paragraph( 'A good source of potassium and healthy fats, tiger nuts (often blended into a milk-like drink) are a useful snack alternative to processed, salt-heavy options.' )
			. hg_seed_heading( '5. Hibiscus (zobo)' )
			. hg_seed_paragraph( 'Multiple studies have linked hibiscus tea to modest reductions in blood pressure. The catch: many commercial zobo preparations are loaded with sugar, which undermines the benefit. Homemade, lightly sweetened zobo is the better version.' )
			. hg_seed_heading( 'What to watch, alongside what to add' )
			. hg_seed_paragraph( "Adding these foods matters less if sodium intake from bouillon cubes, processed seasoning, and salt-cured proteins stays high. Blood pressure management works best as a combination: more potassium-rich whole foods, less processed sodium, and a blood pressure check often enough to know whether it’s actually working." )
			. hg_seed_quote( 'Diet changes are most powerful when paired with a number to track against — get your blood pressure checked, adjust, and check again.' ),
	),
);

$post_ids = array();

foreach ( $posts as $slug => $data ) {
	$existing = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $existing ) {
		$post_ids[ $slug ] = $existing->ID;
		WP_CLI::log( "Post '{$slug}' already exists (#{$existing->ID}) — leaving it alone." );
		continue;
	}
	if ( empty( $category_ids[ $data['category'] ] ) ) {
		WP_CLI::warning( "Skipping post '{$slug}' — its category wasn't created." );
		continue;
	}
	$post_id = wp_insert_post( array(
		'post_type'     => 'post',
		'post_title'    => $data['title'],
		'post_name'     => $slug,
		'post_excerpt'  => $data['excerpt'],
		'post_content'  => $data['content'],
		'post_status'   => 'publish',
		'post_category' => array( $category_ids[ $data['category'] ] ),
	) );
	if ( is_wp_error( $post_id ) ) {
		WP_CLI::warning( "Could not create post '{$slug}': " . $post_id->get_error_message() );
		continue;
	}
	list( $image_id ) = hg_seed_get_or_import_image( $data['cover'], $theme_dir );
	if ( $image_id ) {
		set_post_thumbnail( $post_id, $image_id );
	}
	$post_ids[ $slug ] = $post_id;
	WP_CLI::success( "Created post '{$slug}' (#{$post_id})." );
}

/* ------------------------------------------------------------------ *
 * 3. Pages — Home, About, Contact, Blog.
 * ------------------------------------------------------------------ */

$pages_to_create = array(
	'home'    => 'Home',
	'about'   => 'About',
	'contact' => 'Contact',
	'blog'    => 'Blog',
);

$page_ids = array();

foreach ( $pages_to_create as $slug => $title ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		$page_ids[ $slug ] = $existing->ID;
		WP_CLI::log( "Page '{$slug}' already exists (#{$existing->ID}) — leaving it alone." );
		continue;
	}
	$id = wp_insert_post( array(
		'post_type'   => 'page',
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
	) );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "Could not create page '{$slug}': " . $id->get_error_message() );
		continue;
	}
	$page_ids[ $slug ] = $id;
	WP_CLI::success( "Created page '{$slug}' (#{$id})." );
}

/* ------------------------------------------------------------------ *
 * 3b. Featured images for About/Contact — the page-hero banner at the
 *     top of page.php/page-contact.php falls back to a plain dark band
 *     without one.
 * ------------------------------------------------------------------ */

$page_hero_images = array(
	'about'   => 'pills-photo.jpg',
	'contact' => 'checkup-photo.jpg',
);
foreach ( $page_hero_images as $slug => $filename ) {
	if ( empty( $page_ids[ $slug ] ) || has_post_thumbnail( $page_ids[ $slug ] ) ) {
		continue;
	}
	list( $hero_img_id ) = hg_seed_get_or_import_image( $filename, $theme_dir );
	if ( $hero_img_id ) {
		set_post_thumbnail( $page_ids[ $slug ], $hero_img_id );
	}
}

if ( ! empty( $page_ids['contact'] ) ) {
	$hg_contact_post = get_post( $page_ids['contact'] );
	if ( $hg_contact_post && ! $hg_contact_post->post_excerpt ) {
		wp_update_post( array(
			'ID'           => $page_ids['contact'],
			'post_excerpt' => "Story tip, correction, partnership enquiry, or just feedback — we read everything that comes through.",
		) );
	}
}

/* ------------------------------------------------------------------ *
 * 4. Reading settings: Home = front page, Blog = posts page.
 * ------------------------------------------------------------------ */

if ( ! empty( $page_ids['home'] ) && ! empty( $page_ids['blog'] ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_ids['home'] );
	update_option( 'page_for_posts', $page_ids['blog'] );
	WP_CLI::success( 'Reading settings set: Home = front page, Blog = posts page.' );
}

/* ------------------------------------------------------------------ *
 * 5. Home content — Hero Slider (1 brand slide + 2 posts) + Category
 *    Showcase (all 4) + Value Prop Grid. Overwrites every run, same as
 *    phf-ogun's Home/About/Donate seeding.
 * ------------------------------------------------------------------ */

if ( ! empty( $page_ids['home'] ) ) {
	list( $hero_image_id, $hero_image_url ) = hg_seed_get_or_import_image( 'checkup-photo.jpg', $theme_dir );

	$hero_slides = array(
		hg_seed_hero_slide_manual(
			'Trusted health journalism',
			'Health news and insights you can actually act on.',
			'Evidence-based articles, wellness tips, and medical news — curated by professionals, written for real life.',
			$hero_image_id,
			$hero_image_url
		),
	);
	// The other two hero slides feature real posts — mirrors the static
	// prototype's hero_posts = [POSTS[2], POSTS[1]] (mental health, genotype).
	foreach ( array( 'why-mental-health-checkins-matter', 'genotype-and-marriage-what-nigerian-couples-should-know' ) as $slug ) {
		if ( ! empty( $post_ids[ $slug ] ) ) {
			$hero_slides[] = hg_seed_hero_slide_linked( $post_ids[ $slug ] );
		}
	}

	$category_cards = array();
	foreach ( $category_ids as $term_id ) {
		$category_cards[] = hg_seed_category_card( $term_id );
	}

	$home_content = hg_seed_hero_slider( $hero_slides )
		. hg_seed_category_showcase( $category_cards, array(
			'eyebrow' => 'Explore',
			'heading' => 'Browse by Topic',
			'variant' => 'light',
		) )
		. hg_seed_value_prop_grid( array(
			hg_seed_value_prop_item( 'check', 'Evidence-based', 'Every article is grounded in established medical understanding, not speculation or trends.', 'green' ),
			hg_seed_value_prop_item( 'globe', 'Locally relevant', 'Written with the Nigerian context in mind — our food, our healthcare access, our realities.', 'blue' ),
			hg_seed_value_prop_item( 'spark', 'Actionable', 'We favour practical takeaways over alarm — what to actually do with the information.', 'gold' ),
		), array(
			'eyebrow' => 'Editorial standards',
			'heading' => 'Why readers trust Healthgists',
			'variant' => 'dark',
		) );

	wp_update_post( array(
		'ID'           => $page_ids['home'],
		'post_content' => $home_content,
	) );
	WP_CLI::success( "Home page content set (#{$page_ids['home']})." );
}

/* ------------------------------------------------------------------ *
 * 6. About content — story paragraphs + Process Steps + Category
 *    Showcase. Overwrites every run.
 * ------------------------------------------------------------------ */

if ( ! empty( $page_ids['about'] ) ) {
	$category_cards = array();
	foreach ( $category_ids as $term_id ) {
		$category_cards[] = hg_seed_category_card( $term_id );
	}

	$about_content = hg_seed_heading( 'Our Story' )
		. hg_seed_paragraph( 'Too much health content online is either written for other doctors, or written to go viral — neither actually helps the person trying to decide whether that headache is worth a hospital visit, or what their genotype result actually means for their wedding plans.' )
		. hg_seed_paragraph( "Healthgists exists to close that gap: real medical understanding, translated into language that respects the reader's intelligence without assuming a medical degree — written with Nigeria's specific realities, food, and healthcare access in mind, not adapted from a US or UK publication after the fact." )
		. hg_seed_heading( 'How We Work' )
		. hg_seed_process_steps( array(
			hg_seed_process_step( '01', 'Grounded in evidence', 'Every claim traces back to established medical understanding — not a trending headline or a single study.' ),
			hg_seed_process_step( '02', 'Written in plain language', 'If a sentence needs a medical dictionary to parse, it gets rewritten. Clarity is not optional.' ),
			hg_seed_process_step( '03', 'Reviewed before publishing', 'Every piece passes through the Healthgists editorial desk before it goes live — nothing ships half-checked.' ),
		) )
		. hg_seed_category_showcase( $category_cards, array(
			'eyebrow'  => 'What We Cover',
			'heading'  => 'Four topics, one standard.',
			'variant'  => 'dark',
			'linkText' => 'Browse all articles →',
			'linkUrl'  => get_permalink( $page_ids['blog'] ),
		) );

	wp_update_post( array(
		'ID'           => $page_ids['about'],
		'post_content' => $about_content,
		'post_excerpt' => 'Healthgists exists to make evidence-based health information accessible, accurate, and easy to act on.',
	) );
	WP_CLI::success( "About page content set (#{$page_ids['about']})." );
}

/* ------------------------------------------------------------------ *
 * 7. What's left to do manually.
 * ------------------------------------------------------------------ */

WP_CLI::log( "\nDone. Still manual:" );
WP_CLI::log( '- Contact page: add an intro paragraph if you want one (the email address and form work without it).' );
WP_CLI::log( '- Real author bylines: posts are attributed to whichever user ran this script — reassign under Posts → Quick Edit if you want a named author.' );
WP_CLI::log( '- Swap the sample posts/photos for real ones any time — everything seeded here is normal, editable WordPress content.' );
WP_CLI::log( '- Menus: optional, Appearance → Menus — the theme works fine without one (see header.php).' );
