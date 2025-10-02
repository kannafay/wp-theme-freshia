<?php

/**
 * 默认头像
 * @return string
 */
function default_avatar($avatar = false) {
    if (!$avatar) {
        $avatar = get_template_directory_uri() . '/assets/images/default-avatar.webp';
    }
    return $avatar;
}

/**
 * 更新用户头像
 * @param int $user_id 用户ID
 * @param int|string|false 附件ID或图片URL，传入false或空字符串删除头像
 * @return object|false|null 成功返回头像对象，失败返回false，删除成功返回null
 */
function update_avatar($user_id, $attachment_id_or_url) {
    if ($attachment_id_or_url === false || $attachment_id_or_url === '') {
        update_user_meta($user_id, 'avatar', '');
        return;
    }

    if (is_numeric($attachment_id_or_url) && (int)$attachment_id_or_url > 0) {
        $attachment_id = (int)$attachment_id_or_url;
        if (!wp_attachment_is_image($attachment_id)) {
            return false;
        }
        $avatar_url = wp_get_attachment_url($attachment_id);
    } else {
        $avatar_url = esc_url($attachment_id_or_url);
    }

    $avatar = new stdClass();
    $avatar->id = $attachment_id ?? 0;
    $avatar->url = $avatar_url;
    update_user_meta($user_id, 'avatar', $avatar);

    return $avatar;
}

/**
 * 过滤头像URL
 */
add_filter('get_avatar_url', 'freshia_avatar_url', 10, 3);
function freshia_avatar_url($url, $id_or_email, $args) {
    $user_id = 0;
    $email = '';
    $avatar_url = default_avatar();

    switch($id_or_email) {
        // 用户id
        case is_numeric($id_or_email):
            $user_id = (int)$id_or_email;
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

    // 已登录用户，优先使用用户自定义头像
    if ($user_id) {
        $avatar = get_user_meta($user_id, 'avatar', true);
        if ($avatar && is_object($avatar)) {
            if ($avatar->id > 0 && wp_attachment_is_image($avatar->id) || $avatar->id <= 0 && !empty($avatar->url)) {
                $avatar_url = $avatar->url;
            }
        }
    
    // 未登录用户，尝试通过邮箱获取用户自定义头像
    } elseif ($email) {
        // QQ邮箱使用QQ头像
        if ($email && preg_match('/^\d+@qq\.com$/', $email)) {
            $avatar_url = 'https://q1.qlogo.cn/g?b=qq&nk='.explode('@', $email)[0].'&s=640';

        // 其他邮箱使用Cravatar自动生成头像
        } else {
            $address = strtolower(trim($email));
            $hash = md5($address);
            $avatar_url = "https://cravatar.cn/avatar/{$hash}?s=400&d=mp";
        }
    }

    return $avatar_url;
}
