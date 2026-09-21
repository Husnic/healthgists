import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls, RichText, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

// The two brand-slide buttons ("Start Reading" / "About Healthgists") and
// the linked-post slide's "Read Article" button were never editable content
// in the static prototype this ports from — they're fixed navigation, not
// per-slide copy — so they're rendered server-side in inc/blocks.php rather
// than built here as InnerBlocks. Editors control which mode a slide is in
// (linked post vs. manual) and the manual slide's text/image; not the CTAs.

export default function Edit({ attributes, setAttributes }) {
	const { linkedPostId, eyebrow, title, subtitle, imageId, imageUrl } = attributes;

	const posts = useSelect(
		(select) => select("core").getEntityRecords("postType", "post", { per_page: -1, status: "publish", orderby: "title", order: "asc" }),
		[]
	);

	const linkedPost = useSelect(
		(select) => (linkedPostId ? select("core").getEntityRecord("postType", "post", linkedPostId) : null),
		[linkedPostId]
	);

	const postOptions = [
		{ label: __("— None (manual slide) —", "healthgists"), value: 0 },
		...((posts || []).map((p) => ({ label: p.title.rendered || __("(no title)", "healthgists"), value: p.id }))),
	];

	const blockProps = useBlockProps({
		className: "relative overflow-hidden rounded-2xl bg-ink min-h-[420px] flex flex-col justify-end p-8",
		style:
			linkedPostId && linkedPost && linkedPost.featured_media
				? undefined
				: imageUrl
				? { backgroundImage: `linear-gradient(to top, rgba(16,27,35,.9), rgba(16,27,35,.3)), url(${imageUrl})`, backgroundSize: "cover", backgroundPosition: "center" }
				: undefined,
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Slide Source", "healthgists")}>
					<SelectControl
						label={__("Link to a post", "healthgists")}
						help={__("Auto-fills the photo, title, category, and excerpt from that post. Choose None to fill in a manual slide instead.", "healthgists")}
						value={linkedPostId}
						options={postOptions}
						onChange={(value) => setAttributes({ linkedPostId: parseInt(value, 10) || 0 })}
					/>
				</PanelBody>
				{! linkedPostId && (
					<PanelBody title={__("Slide Image", "healthgists")}>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={(media) => setAttributes({ imageId: media.id, imageUrl: media.url })}
								allowedTypes={["image"]}
								value={imageId}
								render={({ open }) => (
									<Button variant="secondary" onClick={open}>
										{imageUrl ? __("Replace Image", "healthgists") : __("Choose Image", "healthgists")}
									</Button>
								)}
							/>
						</MediaUploadCheck>
					</PanelBody>
				)}
			</InspectorControls>
			<div {...blockProps}>
				{linkedPostId ? (
					<div style={{ color: "#fff" }}>
						<p style={{ textTransform: "uppercase", fontSize: 11, letterSpacing: "0.1em", opacity: 0.7 }}>
							{__("Linked post", "healthgists")}
						</p>
						<p style={{ fontSize: 22, fontWeight: 700, margin: "6px 0 0" }}>
							{linkedPost ? linkedPost.title.rendered : __("Loading…", "healthgists")}
						</p>
						<p style={{ fontSize: 13, opacity: 0.75, marginTop: 6 }}>
							{__("Photo, category, and excerpt come from the post automatically.", "healthgists")}
						</p>
					</div>
				) : (
					<>
						<RichText
							tagName="p"
							className="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-gold"
							placeholder={__("Eyebrow label…", "healthgists")}
							value={eyebrow}
							onChange={(value) => setAttributes({ eyebrow: value })}
							allowedFormats={[]}
						/>
						<RichText
							tagName="h2"
							className="max-w-xl text-3xl font-extrabold text-white"
							placeholder={__("Slide heading…", "healthgists")}
							value={title}
							onChange={(value) => setAttributes({ title: value })}
							allowedFormats={[]}
						/>
						<RichText
							tagName="p"
							className="mt-3 max-w-lg text-sm text-white/80"
							placeholder={__("Slide subtitle…", "healthgists")}
							value={subtitle}
							onChange={(value) => setAttributes({ subtitle: value })}
							allowedFormats={["core/bold", "core/italic"]}
						/>
					</>
				)}
			</div>
		</>
	);
}
