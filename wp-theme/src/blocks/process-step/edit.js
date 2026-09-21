import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
	const { number, title, body } = attributes;
	const blockProps = useBlockProps({ className: "rounded-2xl border border-ink/10 p-6" });

	return (
		<div {...blockProps}>
			<RichText
				tagName="span"
				className="text-xs font-bold text-green"
				placeholder="01"
				value={number}
				onChange={(value) => setAttributes({ number: value })}
				allowedFormats={[]}
			/>
			<RichText
				tagName="h3"
				className="mt-3 font-bold text-ink"
				placeholder={__("Step title…", "healthgists")}
				value={title}
				onChange={(value) => setAttributes({ title: value })}
				allowedFormats={[]}
			/>
			<RichText
				tagName="p"
				className="mt-2 text-sm leading-relaxed text-ink/60"
				placeholder={__("Step description…", "healthgists")}
				value={body}
				onChange={(value) => setAttributes({ body: value })}
				allowedFormats={["core/bold", "core/italic"]}
			/>
		</div>
	);
}
