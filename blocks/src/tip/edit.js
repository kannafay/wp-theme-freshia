import './editor.scss'
import { __ } from '@wordpress/i18n'
import {
	useBlockProps,
	BlockControls,
	RichText,
} from '@wordpress/block-editor'
import {
	ToolbarGroup,
	ToolbarDropdownMenu,
} from '@wordpress/components'
import { info, caution, error, check } from '@wordpress/icons'

export default function Edit({ attributes, setAttributes }) {
	const { status, content } = attributes
	const statuses = [
		{ label: '信息', value: 'tip-info', icon: info },
		{ label: '成功', value: 'tip-success', icon: check },
		{ label: '警告', value: 'tip-warning', icon: caution },
		{ label: '错误', value: 'tip-error', icon: error },
	]
	const blockProps = useBlockProps({
		className: status,
	})
	return (<>
		<BlockControls>
			<ToolbarGroup>
				<ToolbarDropdownMenu
					icon={statuses.find((s) => s.value === status)?.icon || info}
					label="提示状态"
					controls={statuses.map((s) => ({
						icon: s.icon,
						title: s.label,
						isActive: s.value === status,
						onClick: () => setAttributes({ status: s.value }),
					}))}
				/>
			</ToolbarGroup>
		</BlockControls>

		<div {...blockProps}>
			<RichText
				placeholder='请输入提示内容'
				value={content}
				onChange={(content) => setAttributes({ content })}
			/>
		</div>
	</>)
}
