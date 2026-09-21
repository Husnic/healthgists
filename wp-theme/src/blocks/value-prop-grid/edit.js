import { __ } from "@wordpress/i18n";
import { useBlockProps, InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";

const TEMPLATE = [
	["healthgists/value-prop-item", { icon: "check", title: "Evidence-based", body: "Describe it here.", tint: "green" }],
	["healthgists/value-prop-item", { icon: "globe", title: "Locally relevant", body: "Describe it here.", tint: "blue" }],
	["healthgists/value-prop-item", { icon: "spark", title: "Actionable", body: "Describe it here.", tint: "gold" }],
];

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, heading, variant } = attributes;
	const isDark = variant === "dark";
	const blockProps = useBlockProps({ className: `block p-6 ${isDark ? "bg-ink" : ""}` });

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Heading (optional)", "healthgists")}>
					<SelectControl
						label={__("Background", "healthgists")}
						value={variant}
						options={[
							{ label: "Light (plain)", value: "light" },
							{ label: "Dark (ink)", value: "dark" },
						]}
						onChange={(value) => setAttributes({ variant: value })}
					/>
					<TextControl label={__("Eyebrow", "healthgists")} value={eyebrow} onChange={(value) => setAttributes({ eyebrow: value })} />
					<TextControl label={__("Heading", "healthgists")} value={heading} onChange={(value) => setAttributes({ heading: value })} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				{(eyebrow || heading) && (
					<div className="mb-10 max-w-lg">
						{eyebrow && <p className={`text-xs font-semibold uppercase tracking-[0.2em] ${isDark ? "text-white/50" : "text-green"}`}>{eyebrow}</p>}
						{heading && <h2 className={`mt-2 text-2xl font-bold ${isDark ? "text-white" : "text-ink"}`}>{heading}</h2>}
					</div>
				)}
				<div className="grid grid-cols-1 gap-10 sm:grid-cols-3">
					<InnerBlocks allowedBlocks={["healthgists/value-prop-item"]} template={TEMPLATE} templateInsertUpdatesSelection={false} />
				</div>
			</div>
		</>
	);
}
