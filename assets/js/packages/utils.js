/**
 * 将对象序列化为 URL 参数字符串
 */
function serialize(data) {
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

export default { serialize };