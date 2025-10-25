<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * 过滤头像URL，优先使用用户自定义头像
 */
add_filter('get_avatar_url', function ($url, $id_or_email, $args) {
    $user_id = 0;
    $email = '';
    $avatar_url = get_template_directory_uri() . '/assets/images/default-avatar.webp';

    switch ($id_or_email) {
        // 用户id
        case is_numeric($id_or_email):
            $user_id = (int) $id_or_email;
            break;

        // 用户邮箱
        case (is_string($id_or_email) && is_email($id_or_email)):
            $user = get_user_by('email', $id_or_email);
            if ($user) {
                $user_id = $user->ID;
            } else {
                $email = $id_or_email;
            }
            break;

        // WP_User对象
        case ($id_or_email instanceof WP_User):
            $user_id = $id_or_email->ID;
            break;

        // WP_Post对象
        case ($id_or_email instanceof WP_Post):
            $user_id = $id_or_email->post_author;
            break;

        // WP_Comment对象
        case ($id_or_email instanceof WP_Comment):
            $email = $id_or_email->comment_author_email;
            $user = get_user_by('email', $email);
            if ($user) {
                $user_id = $user->ID;
            }
            break;
    }

    if ($user_id) {
        //已登录用户，优先使用用户自定义头像
        $avatar = get_user_meta($user_id, 'avatar', true);
        if ($avatar && is_object($avatar)) {
            if ($avatar->id > 0 && wp_attachment_is_image($avatar->id) || $avatar->id <= 0 && !empty($avatar->url)) {
                $avatar_url = $avatar->url;
            }
        }

    } elseif ($email) {
        //未登录用户，尝试通过邮箱获取用户自定义头像
        if ($email && preg_match('/^\d+@qq\.com$/', $email)) {
            // QQ邮箱使用QQ头像
            $avatar_url = 'https://q1.qlogo.cn/g?b=qq&nk=' . explode('@', $email)[0] . '&s=640';

        } else {
            // 其他邮箱使用Cravatar自动生成头像
            $address = strtolower(trim($email));
            $hash = md5($address);
            $avatar_url = "https://cravatar.cn/avatar/{$hash}?s=400&d=mp";
        }
    }

    return $avatar_url;
}, 10, 3);

if (!function_exists('update_avatar')) {
    /**
     * 更新用户头像
     * @param int $user_id 用户ID
     * @param string|int|bool $attachment_id_or_url_or_default 头像的附件ID、URL，或布尔值true表示删除自定义头像
     * @return bool 操作是否成功
     */
    function update_avatar(int $user_id, string|int|bool $attachment_id_or_url_or_default): bool {
        $param = trim($attachment_id_or_url_or_default);

        if (is_bool($param) && $param === true) {
            // 删除自定义头像
            delete_user_meta($user_id, 'avatar');
            return true;

        } elseif (is_numeric($param) && (int) $param > 0) {
            // 附件ID
            $attachment_id = (int) $param;
            if (!wp_attachment_is_image($attachment_id)) {
                return false;
            }
            $avatar_url = wp_get_attachment_url($attachment_id);

        } elseif (is_string($param) && filter_var($param, FILTER_VALIDATE_URL)) {
            // 头像URL
            $avatar_url = esc_url($param);

        } else {
            return false;
        }

        $avatar = new stdClass();
        $avatar->id = $attachment_id ?? 0;
        $avatar->url = $avatar_url;
        update_user_meta($user_id, 'avatar', $avatar);
        return true;
    }
}
