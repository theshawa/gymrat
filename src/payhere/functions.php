<?php

require_once __DIR__ . "/config.php";

function get_checkout_fields(
    float $amount,
    string $order_id,
    string $item,
    string $fname,
    string $lname,
    string $email
): string {
    $hash = strtoupper(
        md5(
            payhere_config["merchant_id"] .
                $order_id .
                number_format($amount, 2, '.', '') .
                "LKR" .
                strtoupper(md5(payhere_config["merchant_secret"]))
        )
    );
    $get_field = fn(string $name, string $value) => "<input type='hidden' name='$name' value='$value'>";

    return join("", [
        $get_field("merchant_id", payhere_config["merchant_id"]),
        $get_field("return_url", payhere_config['return_url']),
        $get_field("cancel_url", payhere_config['cancel_url'] . "?order_id=$order_id"),
        $get_field("notify_url", payhere_config['notify_url']),
        $get_field("first_name", $fname),
        $get_field("last_name", $lname),
        $get_field("email", $email),
        $get_field("phone", "+94766743755"),
        $get_field("address", "University of Colombo School of Computing, Reid Avenue, Colombo 7"),
        $get_field("city", "Colombo 7"),
        $get_field("country", "Sri Lanka"),
        $get_field("order_id", $order_id),
        $get_field("items", $item),
        $get_field("currency", "LKR"),
        $get_field("amount", number_format($amount, 2, '.', '')),
        $get_field("hash", $hash),
    ]);
}
