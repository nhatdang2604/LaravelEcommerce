<?php
namespace App\Utilities;

class ToastUtils {

    private static function buildToastInfo($type, $message) {
        return [
            'type' => $type,
            'message' => $message,
        ];
    }

    public static function buildSuccessToast($message) {
        return ToastUtils::buildToastInfo('success', $message);
    }

    public static function buildErrorToast($message) {
        return ToastUtils::buildToastInfo('error', $message);
    }

}
