import { __ } from "@wordpress/i18n";
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

const TEMPLATE = [["healthgists/hero-slide", {}]];

export default function Edit() {
	const blockProps = useBlockProps({ className: "bg-ink p-6 rounded-2xl" });

	return (
		<div {...blockProps}>
			<p style={{ color: "#fff", fontSize: 12, textTransform: "uppercase", letterSpacing: "0.1em", marginBottom: 12 }}>
				{__("Hero Slider — add, remove, or reorder slides below.", "healthgists")}
			</p>
			<InnerBlocks allowedBlocks={["healthgists/hero-slide"]} template={TEMPLATE} orientation="vertical" />
		</div>
	);
}
