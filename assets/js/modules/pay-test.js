function payTest() {
    const order_status = document.querySelector('#order-status');
    const order_list = document.querySelectorAll('#order-list li');

    if (window.appTimer) {
        clearInterval(window.appTimer);
        window.appTimer = null;
    }
    if (order_status && order_status.textContent === 'pending') {
        window.appTimer = setInterval(() => {
            wpRest.get('freshia/v1/order/status', {
                order_id: order_status.dataset.orderId,
                t: Date.now()
            }).then(res => {
                if (res.success && res.data.status === 'paid') {
                    clearInterval(window.appTimer);
                    order_status.textContent = res.data.status;
                    order_status.className = 'text-green-500';
                    order_list.forEach(li => {
                        if (li.dataset.orderId === order_status.dataset.orderId) {
                            li.querySelector('span').textContent = res.data.status;
                        }
                    });
                    const qrcode = document.querySelector('#qrcode')
                    if (qrcode) {
                        qrcode.remove();
                        alert('支付成功！');
                    }
                }
            })
        }, 2000);
    }
}

export default payTest;