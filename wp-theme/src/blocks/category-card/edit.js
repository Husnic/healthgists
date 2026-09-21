import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

export default function Edit({ attributes, setAttributes }) {
	const { termId } = attributes;
	const blockProps = useBlockProps({
		className: "flex h-40 flex-col justify-end rounded-2xl bg-ink p-4",
	});

	const categories = useSelect(
		(select) => select("core").getEntityRecords("taxonomy", "category", { per_page: -1, orderby: "name", order: "asc" }),
		[]
	);
	const selected = (categories || []).find((c) => c.id === termId);

	const options = [
		{ label: __("— Choose a category —", "healthgists"), value: 0 },
		...((categories || []).map((c) => ({ label: c.name, value: c.id }))),
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Category", "healthgists")}>
					<SelectControl
						label={__("Which category?", "healthgists")}
						help={__("The card's photo and description come from that category — set them under Posts → Categories.", "healthgists")}
						value={termId}
						options={options}
						onChange={(value) => setAttributes({ termId: parseInt(value, 10) || 0 })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<p style={{ color: "#fff", fontWeight: 700, margin: 0 }}>
					{selected ? selected.name : __("No category selected", "healthgists")}
				</p>
			</div>
		</>
	);
}
