// 测试函数
export default function test() {
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', () => {
            let count = parseInt(button.textContent) || 0;
            count++;
            button.textContent = count.toString();
        });
    });
}