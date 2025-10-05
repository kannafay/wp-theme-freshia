<?php
// This file is generated. Do not modify it manually.
return array(
	'tip' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'freshia/tip',
		'version' => '0.1.0',
		'title' => 'Freshia-提示块',
		'category' => 'freshia',
		'icon' => 'info',
		'description' => '支持多种状态的提示信息块。',
		'example' => array(
			
		),
		'supports' => array(
			'html' => true
		),
		'attributes' => array(
			'status' => array(
				'type' => 'string',
				'default' => 'tip-info'
			),
			'content' => array(
				'type' => 'string'
			)
		),
		'textdomain' => 'tip',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	)
);
