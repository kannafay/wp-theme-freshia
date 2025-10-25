import { useBlockProps, RichText } from '@wordpress/block-editor'

export default function save({ attributes }) {
	const { status, content } = attributes;
	const colors = {
		'tip-info': 'var(--bg-tip-info)',
		'tip-success': 'var(--bg-tip-success)',
		'tip-warning': 'var(--bg-tip-warning)',
		'tip-error': 'var(--bg-tip-error)',
	};

	const blockProps = useBlockProps.save({
		className: `${status} p-4`,
		style: {
			backgroundColor: colors[status] || colors['tip-info'],
		},
	});
	return (
		<div {...blockProps}>
			<RichText.Content
				tagName="p"
				value={content}
			/>
		</div>
	);
}
