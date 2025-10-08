/**
 * 将对象序列化为 URL 参数字符串
 */
function objectToURL(data) {
	const params = new URLSearchParams();
	// 递归处理对象和数组
	const process = (key, value) => {
		if (Array.isArray(value)) {
			// 数组处理：hobby[]=a&hobby[]=b
			value.forEach(v => params.append(`${key}[]`, v));
		} else if (typeof value === 'object' && value !== null) {
			// 对象处理：obj[name]=kanna&obj[age]=18
			Object.entries(value).forEach(([subKey, subVal]) => {
				process(`${key}[${subKey}]`, subVal);
			});
		} else {
			// 基础类型直接添加
			params.append(key, value);
		}
	};
	Object.entries(data).forEach(([key, value]) => {
		process(key, value);
	});
	return params.toString();
}

/**
 * 将对象转换为 FormData（支持嵌套对象和数组）
 */
function objectToFormData(obj, formData = new FormData(), parentKey = '') {
	for (const key in obj) {
		if (obj.hasOwnProperty(key)) {
			const value = obj[key];
			const formKey = parentKey ? `${parentKey}[${key}]` : key; // 处理嵌套字段名
			// 处理 null/undefined（跳过）
			if (value == null) continue;
			// 处理数组/对象（递归）
			if (typeof value === 'object' && !(value instanceof File)) {
				objectToFormData(value, formData, formKey);
			}
			// 处理文件（直接添加）
			else if (value instanceof File) {
				formData.append(formKey, value);
			}
			// 处理其他类型（转字符串）
			else {
				formData.append(formKey, String(value));
			}
		}
	}
	return formData;
}

export default {
	objectToURL,
	objectToFormData
};