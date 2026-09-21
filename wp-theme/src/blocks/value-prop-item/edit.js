import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls, RichText } from "@wordpress/block-editor";
import { PanelBody, SelectControl } from "@wordpress/components";

const ICONS = window.hgBlockData?.icons || { spark: "Spark" };
const TINTS = [
	{ label: __("Green", "healthgists"), value: "green" },
	{ label: __("Blue", "healthgists"), value: "blue" },
	{ label: __("Gold", "healthgists"), value: "gold" },
];

export default function Edit({ attributes, setAttributes }) {
	const { icon, title, body, tint } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Icon & Color", "healthgists")}>
					<SelectControl
						label={__("Icon", "healthgists")}
						value={icon}
						options={Object.entries(ICONS).map(([value, label]) => ({ value, label }))}
						onChange={(value) => setAttributes({ icon: value })}
					/>
					<SelectControl
						label={__("Tint", "healthgists")}
						value={tint}
						options={TINTS}
						onChange={(value) => setAttributes({ tint: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className={`flex h-11 w-11 items-center justify-center rounded-xl bg-${tint}/15 text-${tint}`}>
					<span style={{ fontSize: 11, textTransform: "uppercase" }}>{icon}</span>
				</div>
				<RichText
					tagName="h3"
					className="mt-4 font-bold text-ink"
					placeholder={__("Title…", "healthgists")}
					value={title}
					onChange={(value) => setAttributes({ title: value })}
					allowedFormats={[]}
				/>
				<RichText
					tagName="p"
					className="mt-2 text-sm leading-relaxed text-ink/60"
					placeholder={__("Body text…", "healthgists")}
					value={body}
					onChange={(value) => setAttributes({ body: value })}
					allowedFormats={["core/bold", "core/italic"]}
				/>
			</div>
		</>
	);
}
