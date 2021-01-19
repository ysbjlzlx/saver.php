-- 删除表
DROP TABLE IF EXISTS `user`;
-- 创建表
CREATE TABLE `user`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT             NOT NULL COMMENT '主键',
    `username`          VARCHAR(64)   DEFAULT ''                   NOT NULL COMMENT '用户名',
    `password`          CHAR(128)     DEFAULT ''                   NOT NULL COMMENT '密码',
    `nickname`          VARCHAR(16)   DEFAULT ''                   NOT NULL COMMENT '昵称',
    `avatar`            VARCHAR(255)  DEFAULT ''                   NOT NULL COMMENT '头像',
    `phone_area_code`   VARCHAR(16)   DEFAULT ''                   NOT NULL COMMENT '手机号地区码',
    `phone_number`      VARCHAR(32)   DEFAULT ''                   NOT NULL COMMENT '手机号码',
    `phone_verified_at` DATETIME(6)                                NULL COMMENT '手机验证时间',
    `email`             VARCHAR(255)  DEFAULT ''                   NOT NULL COMMENT '邮箱',
    `email_verified_at` DATETIME(6)                                NULL COMMENT '邮箱验证时间',
    `totp_secret`       VARCHAR(128)  DEFAULT ''                   NOT NULL COMMENT 'TOTP',
    `extra`             VARCHAR(2048) DEFAULT ''                   NOT NULL COMMENT '用户字段补全',
    `remark`            VARCHAR(2048) DEFAULT ''                   NOT NULL COMMENT '数据说明性',
    `updated_at`        TIMESTAMP(6)  DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at`        TIMESTAMP(6)  DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
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
    `id`         BIGINT UNSIGNED AUTO_INCREMENT               NOT NULL COMMENT '主键',
    `user_id`    BIGINT UNSIGNED DEFAULT 0                    NOT NULL COMMENT '用户 ID',
    `token`      VARCHAR(128)    DEFAULT ''                   NOT NULL COMMENT 'Token',
    `ua`         VARCHAR(255)    DEFAULT ''                   NOT NULL COMMENT 'user agent',
    `ip`         VARCHAR(255)    DEFAULT ''                   NOT NULL COMMENT 'IP 地址',
    `longitude`  DECIMAL(11, 8)  DEFAULT 0                    NOT NULL COMMENT '经度',
    `latitude`   DECIMAL(10, 8)  DEFAULT 0                    NOT NULL COMMENT '纬度',
    `deleted_at` TIMESTAMP(6)                                 NULL COMMENT '',
    `updated_at` TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at` TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_token` (`token`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='用户 Token 表';
-- 删除表
DROP TABLE IF EXISTS `data_dict`;
-- 创建表
CREATE TABLE `data_dict`
(
    `id`         BIGINT UNSIGNED AUTO_INCREMENT             NOT NULL COMMENT '主键',
    `name`       VARCHAR(128)  DEFAULT ''                   NOT NULL COMMENT '名称',
    `key`        VARCHAR(128)  DEFAULT ''                   NOT NULL COMMENT 'key',
    `value`      VARCHAR(4096) DEFAULT ''                   NOT NULL COMMENT 'value',
    `memo`       VARCHAR(128)  DEFAULT ''                   NOT NULL COMMENT '备注',
    `version`    INT UNSIGNED  DEFAULT 0                    NOT NULL COMMENT '乐观锁',
    `updated_at` TIMESTAMP(6)  DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at` TIMESTAMP(6)  DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    CONSTRAINT `uk_key` UNIQUE (`key`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='数据字典表';
-- 删除表
DROP TABLE IF EXISTS `ad_publisher`;
-- 创建表
CREATE TABLE `ad_publisher`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT            NOT NULL COMMENT '主键',
    `username`          VARCHAR(16)  DEFAULT ''                   NOT NULL COMMENT '',
    `password`          VARCHAR(128) DEFAULT ''                   NOT NULL COMMENT '',
    `email`             VARCHAR(255) DEFAULT ''                   NOT NULL COMMENT '邮箱',
    `email_verified_at` DATETIME                                  NULL COMMENT '邮箱验证时间',
    `updated_at`        TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at`        TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='流量主';
-- 删除表
DROP TABLE IF EXISTS `ad_advertiser`;
-- 创建表
CREATE TABLE `ad_advertiser`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT            NOT NULL COMMENT '主键',
    `username`          VARCHAR(16)  DEFAULT ''                   NOT NULL COMMENT '',
    `password`          VARCHAR(128) DEFAULT ''                   NOT NULL COMMENT '',
    `email`             VARCHAR(255) DEFAULT ''                   NOT NULL COMMENT '邮箱',
    `email_verified_at` DATETIME                                  NULL COMMENT '邮箱验证时间',
    `updated_at`        TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at`        TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='广告主';
-- 删除表
DROP TABLE IF EXISTS `log`;
-- 创建表
CREATE TABLE `log`
(
    `id`         BIGINT UNSIGNED AUTO_INCREMENT            NOT NULL COMMENT '主键',
    `message`    VARCHAR(255) DEFAULT ''                   NOT NULL COMMENT '消息',
    `context`    TEXT                                      NOT NULL COMMENT '上下文',
    `level`      INT          DEFAULT 0                    NOT NULL COMMENT '日志级别',
    `level_name` VARCHAR(255) DEFAULT ''                   NOT NULL COMMENT '日志级别',
    `channel`    VARCHAR(255) DEFAULT ''                   NOT NULL COMMENT 'channel',
    `datetime`   VARCHAR(64)  DEFAULT ''                   NOT NULL COMMENT '时间',
    `extra`      TEXT                                      NOT NULL COMMENT '额外信息',
    `updated_at` TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at` TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='日志';
-- 删除表
DROP TABLE IF EXISTS `ad_application`;
-- 新建表
CREATE TABLE `ad_application`
(
    `id`              BIGINT UNSIGNED AUTO_INCREMENT               NOT NULL COMMENT '主键',
    `ad_publisher_id` BIGINT UNSIGNED DEFAULT 0                    NOT NULL COMMENT '流量主',
    `name`            VARCHAR(128)    DEFAULT ''                   NOT NULL COMMENT '内部名称',
    `title`           VARCHAR(128)    DEFAULT ''                   NOT NULL COMMENT '外部展示名称',
    `memo`            VARCHAR(255)    DEFAULT ''                   NOT NULL COMMENT '备注',
    `updated_at`      TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at`      TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_ad_publisher_id` (`ad_publisher_id`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='广告主应用';
-- 删除表
DROP TABLE IF EXISTS `ad_resource`;
-- 新建表
CREATE TABLE `ad_resource`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT               NOT NULL COMMENT '主键',
    `ad_publisher_id`   BIGINT UNSIGNED DEFAULT 0                    NOT NULL COMMENT '流量主',
    `ad_application_id` BIGINT UNSIGNED DEFAULT 0                    NOT NULL COMMENT 'APP ID',
    `name`              VARCHAR(128)    DEFAULT ''                   NOT NULL COMMENT '内部名称',
    `title`             VARCHAR(128)    DEFAULT ''                   NOT NULL COMMENT '外部展示名称',
    `description`       VARCHAR(255)    DEFAULT ''                   NOT NULL COMMENT '资源位描述',
    `memo`              VARCHAR(255)    DEFAULT ''                   NOT NULL COMMENT '备注',
    `updated_at`        TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL ON UPDATE CURRENT_TIMESTAMP(6) COMMENT '更新时间',
    `created_at`        TIMESTAMP(6)    DEFAULT CURRENT_TIMESTAMP(6) NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    INDEX `idx_ad_publisher_id` (`ad_publisher_id`),
    INDEX `idx_updated_at` (`updated_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci` COMMENT ='广告主资源位';

