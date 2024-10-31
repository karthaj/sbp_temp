# sbp_temp


table for checkout OTP
```
CREATE TABLE checkout_otp (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED NOT NULL,
    otp_code VARCHAR(5) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    attempts INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_checkout_otps_customer_id FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    CONSTRAINT fk_checkout_otps_cart_id FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE
);

```