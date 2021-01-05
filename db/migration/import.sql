-- 删除表
DROP TABLE IF EXISTS `user`;
-- 创建表
CREATE TABLE `user`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT              NOT NULL COMMENT '主键',
    `username`          VARCHAR(64)   DEFAULT ''                    NOT NULL COMMENT '用户名',
    `password`          CHAR(128)     DEFAULT ''                    NOT NULL COMMENT '密码',
    `nickname`          VARCHAR(16)   DEFAULT ''                    NOT NULL COMMENT '昵称',
    `avatar`            VARCHAR(255)  DEFAULT ''                    NOT NULL COMMENT '头像',
    `phone_area_code`   VARCHAR(16)   DEFAULT ''                    NOT NULL COMMENT '手机号地区码',
    `phone_number`      VARCHAR(32)   DEFAULT ''                    NOT NULL COMMENT '手机号码',
    `phone_verified_at` DATETIME      DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '手机验证时间',
    `email`             VARCHAR(255)  DEFAULT ''                    NOT NULL COMMENT '邮箱',
    `email_verified_at` DATETIME      DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '邮箱验证时间',
    `extra`             VARCHAR(2048) DEFAULT ''                    NOT NULL COMMENT '用户字段补全',
    `remark`            VARCHAR(2048) DEFAULT ''                    NOT NULL COMMENT '数据说明性',
    `updated_at`        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP     NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `created_at`        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP     NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_phone_area_code_phone_number` (`phone_area_code`, `phone_number`),
    INDEX `idx_email` (`email`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='用户表';
-- 删除表
DROP TABLE IF EXISTS `user_token`;
-- 创建表
CREATE TABLE `user_token`
(
    `id`         BIGINT UNSIGNED AUTO_INCREMENT            NOT NULL COMMENT '主键',
    `user_id`    BIGINT UNSIGNED DEFAULT 0                 NOT NULL COMMENT '用户 ID',
    `token`      VARCHAR(128)    DEFAULT ''                NOT NULL COMMENT 'Token',
    `ua`         VARCHAR(255)    DEFAULT ''                NOT NULL COMMENT 'user agent',
    `ip`         VARCHAR(255)    DEFAULT ''                NOT NULL COMMENT 'IP 地址',
    `longitude`  DECIMAL(11, 8)  DEFAULT 0                 NOT NULL COMMENT '经度',
    `latitude`   DECIMAL(10, 8)  DEFAULT 0                 NOT NULL COMMENT '纬度',
    `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_token` (`token`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='用户 Token 表';
