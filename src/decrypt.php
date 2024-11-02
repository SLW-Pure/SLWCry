<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Çözme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Şifre Çözme</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="accessKey" class="form-label">Çözme Anahtarı:</label>
                <input type="text" id="accessKey" name="accessKey" class="form-control" placeholder="SLW-ED-xxxxxx" required>
            </div>
            <button type="submit" name="decrypt" class="btn btn-primary w-100">Şifreyi Çöz</button>
        </form>

        <?php
        if (isset($_POST['decrypt'])) {
            $accessKey = $_POST['accessKey'];

            // Dosya adı, erişim anahtarının kısa hash değeri ile belirleniyor.
            $fileName = 'data_' . md5($accessKey) . '.txt';

            if (!file_exists($fileName)) {
                echo "<div class='alert alert-danger mt-4'>Şifrelenmiş veri bulunamadı veya anahtar geçersiz.</div>";
            } else {
                // Dosyadan verileri al
                list($secondEncryptedText, $aesKey1, $aesIv1, $rsaEncryptedKey, $rsaEncryptedIv2, $aesIv2, $privateKey, $expiration, $hash) = explode('::', file_get_contents($fileName));
                $secondEncryptedText = base64_decode($secondEncryptedText);
                $aesKey1 = base64_decode($aesKey1);
                $aesIv1 = base64_decode($aesIv1);
                $rsaEncryptedKey = base64_decode($rsaEncryptedKey);
                $rsaEncryptedIv2 = base64_decode($rsaEncryptedIv2);
                $aesIv2 = base64_decode($aesIv2);
                $privateKey = base64_decode($privateKey);
                
                // Son kullanma tarihi kontrolü
                if (time() > $expiration) {
                    echo "<div class='alert alert-danger mt-4'>Anahtarın süresi dolmuş. Erişim sağlanamıyor.</div>";
                    exit;
                }

                // Hash doğrulaması
                $computedHash = hash('sha256', $secondEncryptedText . $aesKey1 . $aesIv1 . $rsaEncryptedKey . $rsaEncryptedIv2 . $aesIv2 . $privateKey . $expiration);
                if (!hash_equals($computedHash, $hash)) {
                    echo "<div class='alert alert-danger mt-4'>Veri bütünlüğü doğrulaması başarısız. Dosya bozulmuş olabilir.</div>";
                    exit;
                }

                // 1. Aşama: RSA ile ikinci AES anahtarını çözme
                openssl_private_decrypt($rsaEncryptedKey, $aesKey2, $privateKey);
                
                // 2. Aşama: RSA ile ikinci AES IV'sini çözme
                openssl_private_decrypt($rsaEncryptedIv2, $aesIv2Decrypted, $privateKey);

                // 3. Aşama: İkinci AES-256 çözme işlemi
                $firstEncryptedText = openssl_decrypt($secondEncryptedText, 'AES-256-CBC', $aesKey2, 0, $aesIv2Decrypted);

                // 4. Aşama: İlk AES-256 çözme işlemi
                $decryptedText = openssl_decrypt($firstEncryptedText, 'AES-256-CBC', $aesKey1, 0, $aesIv1);

                if ($decryptedText === false) {
                    echo "<div class='alert alert-danger mt-4'>Şifre çözme başarısız. Anahtar veya IV hatalı olabilir.</div>";
                } else {
                    echo "<div class='alert alert-success mt-4'>";
                    echo "<h5>Şifre Çözme Başarılı!</h5>";
                    echo "<p><strong>Çözülmüş Metin:</strong></p><pre class='bg-light p-3'>" . htmlspecialchars($decryptedText) . "</pre>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
