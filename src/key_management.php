<?php
// key_management.php

function generateAESKey() {
    return openssl_random_pseudo_bytes(32); // 256-bit AES anahtarı
}

function generateAESIv() {
    return openssl_random_pseudo_bytes(16); // 16-byte IV
}

function generateAccessKey() {
    // Kullanıcı dostu kısa bir erişim anahtarı (SLW-ED- formatında)
    return 'SLW-ED-' . bin2hex(random_bytes(6));
}

function generateRSAKeys() {
    // RSA anahtar çifti
    $rsaResource = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    openssl_pkey_export($rsaResource, $privateKey);
    $publicKey = openssl_pkey_get_details($rsaResource)['key'];

    return ['privateKey' => $privateKey, 'publicKey' => $publicKey];
}

function rsaEncrypt($data, $publicKey) {
    // RSA ile veri şifreleme (ör. IV'ler ve AES anahtarları)
    openssl_public_encrypt($data, $encryptedData, $publicKey);
    return $encryptedData;
}

function rsaDecrypt($data, $privateKey) {
    // RSA ile veri çözme
    openssl_private_decrypt($data, $decryptedData, $privateKey);
    return $decryptedData;
}

function generateDataHash($data, $expiration) {
    // Verinin bütünlüğünü sağlamak için hash oluşturma
    return hash('sha256', $data . $expiration);
}
?>
