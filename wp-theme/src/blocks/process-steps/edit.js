import { __ } from "@wordpress/i18n";
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

const TEMPLATE = [
	["healthgists/process-step", { number: "01", title: "Grounded in evidence", body: "Describe it here." }],
	["healthgists/process-step", { number: "02", title: "Written in plain language", body: "Describe it here." }],
	["healthgists/process-step", { number: "03", title: "Reviewed before publishing", body: "Describe it here." }],
];

export default function Edit() {
	const blockProps = useBlockProps({ className: "grid grid-cols-1 gap-8 sm:grid-cols-3" });

	return (
		<div {...blockProps}>
			<InnerBlocks allowedBlocks={["healthgists/process-step"]} template={TEMPLATE} templateInsertUpdatesSelection={false} />
		</div>
	);
}
