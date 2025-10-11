<?php

if (!defined('ABSPATH')) {
    exit;
}

class Orders
{
    private $table;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'freshia_orders';
    }

    /**
     * 新增订单
     */
    public function create(array $data): int
    {
        $this->wpdb->insert(
            $this->table,
            [
                'order_id' => $data['order_id'],
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'amount' => $data['amount'],
                'status' => $data['status'] ?? 'pending',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
                'deleted_at' => null,
            ],
            [
                '%s', // order_id
                '%d', // user_id
                '%s', // name
                '%f', // amount
                '%s', // status
                '%s', // created_at
                '%s', // updated_at
                '%s', // deleted_at
            ]
        );

        return (int) $this->wpdb->insert_id;
    }

    /**
     * 通过订单ID获取订单
     */
    public function getByOrderID($order_id): ?array
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE order_id=%s AND deleted_at IS NULL", $order_id),
            ARRAY_A
        );
    }

    /**
     * 通过用户ID获取订单
     */
    public function getByUserID($user_id): array
    {
        $sql = "
            SELECT * 
            FROM {$this->table} 
            WHERE user_id = %d AND deleted_at IS NULL
            ORDER BY 
                CASE status 
                    WHEN 'pending' THEN 0 
                    ELSE 1 
                END ASC, 
                created_at DESC
        ";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, $user_id),
            ARRAY_A
        );
    }

    /**
     * 更新订单
     */
    public function update($order_id, array $data): bool
    {
        $data['updated_at'] = current_time('mysql');
        return (bool) $this->wpdb->update(
            $this->table,
            $data,
            ['order_id' => $order_id],
            null,
            ['%s'] // WHERE order_id
        );
    }

    /**
     * 删除订单（软删除）
     */
    public function delete($order_id): bool
    {
        return (bool) $this->wpdb->update(
            $this->table,
            ['deleted_at' => current_time('mysql')],
            ['order_id' => $order_id],
            ['%s'],
            ['%s']
        );
    }

    /**
     * 查询订单列表
     */
    public function all(int $limit = 50, int $offset = 0): array
    {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT %d OFFSET %d",
                $limit,
                $offset
            ),
            ARRAY_A
        );
    }
}