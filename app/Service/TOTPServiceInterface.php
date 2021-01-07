<?php

namespace App\Service;

interface TOTPServiceInterface
{
    /**
     * @return string 获取 secret
     */
    public function getSecret(): string;

    /**
     * @param string   $otp       验证码
     * @param int|null $timestamp 验证时间
     * @param int|null $window    允许的窗口
     *
     * @return bool 验证结果
     */
    public function verify(string $otp, ?int $timestamp = null, ?int $window = null): bool;

    /**
     * @param string      $label  标签
     * @param string|null $issuer 发布方
     *
     * @return string 链接
     */
    public function getUri(string $label, ?string $issuer = null): string;
}
