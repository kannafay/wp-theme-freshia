/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {Element} Element to render.
 */

export default function save({attributes}) {
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
		<div { ...blockProps }>
			<RichText.Content
				tagName="p"
				value={ content }
			/>
		</div>
	);
}
