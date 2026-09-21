import { __ } from "@wordpress/i18n";
import { useBlockProps, InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RangeControl, TextControl, SelectControl } from "@wordpress/components";

const TEMPLATE = [["healthgists/category-card", {}]];
const COL_CLASS = { 2: "sm:grid-cols-2", 3: "sm:grid-cols-2 lg:grid-cols-3", 4: "sm:grid-cols-2 lg:grid-cols-4" };

export default function Edit({ attributes, setAttributes }) {
	const { columns, eyebrow, heading, variant, linkText, linkUrl } = attributes;
	const isDark = variant === "dark";
	const blockProps = useBlockProps({
		className: `block p-6 ${isDark ? "bg-ink" : "bg-paper-warm"}`,
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Layout", "healthgists")}>
					<RangeControl
						label={__("Columns (on desktop)", "healthgists")}
						value={columns}
						onChange={(value) => setAttributes({ columns: value })}
						min={2}
						max={4}
					/>
					<SelectControl
						label={__("Background", "healthgists")}
						value={variant}
						options={[
							{ label: "Light (paper)", value: "light" },
							{ label: "Dark (ink)", value: "dark" },
						]}
						onChange={(value) => setAttributes({ variant: value })}
					/>
				</PanelBody>
				<PanelBody title={__("Heading (optional)", "healthgists")} initialOpen={false}>
					<TextControl label={__("Eyebrow", "healthgists")} value={eyebrow} onChange={(value) => setAttributes({ eyebrow: value })} />
					<TextControl label={__("Heading", "healthgists")} value={heading} onChange={(value) => setAttributes({ heading: value })} />
					<TextControl label={__("Link text", "healthgists")} value={linkText} onChange={(value) => setAttributes({ linkText: value })} />
					<TextControl label={__("Link URL", "healthgists")} value={linkUrl} onChange={(value) => setAttributes({ linkUrl: value })} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				{(eyebrow || heading || linkText) && (
					<div className="mb-6 flex flex-wrap items-end justify-between gap-4">
						<div>
							{eyebrow && <p className={`text-xs font-semibold uppercase tracking-[0.2em] ${isDark ? "text-white/50" : "text-green"}`}>{eyebrow}</p>}
							{heading && <h2 className={`mt-2 text-2xl font-bold ${isDark ? "text-white" : "text-ink"}`}>{heading}</h2>}
						</div>
						{linkText && <span className={`text-sm font-semibold ${isDark ? "text-white/80" : "text-green"}`}>{linkText}</span>}
					</div>
				)}
				<div className={`grid grid-cols-1 gap-5 ${COL_CLASS[columns] || COL_CLASS[4]}`}>
					<InnerBlocks allowedBlocks={["healthgists/category-card"]} template={TEMPLATE} templateInsertUpdatesSelection={false} />
				</div>
			</div>
		</>
	);
}
