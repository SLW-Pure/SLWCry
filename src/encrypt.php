<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metin Şifreleme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Metin Şifreleme</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="inputText" class="form-label">Şifrelemek İstediğiniz Metin:</label>
                <textarea id="inputText" name="inputText" class="form-control" rows="8" required></textarea>
            </div>
            <button type="submit" name="encrypt" class="btn btn-primary w-100">Şifrele</button>
        </form>

        <?php
        require 'key_management.php';

        if (isset($_POST['encrypt'])) {
            // Kullanıcıdan alınan metin
            $inputText = $_POST['inputText'];

            // AES anahtarları ve IV'leri oluştur
            $aesKey1 = generateAESKey();
            $aesIv1 = generateAESIv();
            $aesKey2 = generateAESKey();
            $aesIv2 = generateAESIv();
            $accessKey = generateAccessKey();

            // RSA anahtar çifti oluştur
            $rsaKeys = generateRSAKeys();
            $publicKey = $rsaKeys['publicKey'];
            $privateKey = $rsaKeys['privateKey'];

            // 1. Aşama: İlk AES-256 şifreleme
            $firstEncryptedText = openssl_encrypt($inputText, 'AES-256-CBC', $aesKey1, 0, $aesIv1);

            // 2. Aşama: İkinci AES-256 şifreleme
            $secondEncryptedText = openssl_encrypt($firstEncryptedText, 'AES-256-CBC', $aesKey2, 0, $aesIv2);

            // 3. Aşama: İkinci AES anahtarı ve IV'yi RSA ile şifreleme
            openssl_public_encrypt($aesKey2, $rsaEncryptedKey, $publicKey);
            openssl_public_encrypt($aesIv2, $rsaEncryptedIv2, $publicKey);

            // Zaman damgası (örneğin, 1 saat geçerli)
            $expiration = time() + 3600;

            // Veri bütünlüğü için hash oluşturma
            $hash = hash('sha256', $secondEncryptedText . $aesKey1 . $aesIv1 . $rsaEncryptedKey . $rsaEncryptedIv2 . $aesIv2 . $privateKey . $expiration);

            // Şifrelenmiş veriyi ve anahtarları base64 formatında sakla
            $fileName = 'data_' . md5($accessKey) . '.txt';
            $dataToStore = base64_encode($secondEncryptedText) . '::' 
                         . base64_encode($aesKey1) . '::' 
                         . base64_encode($aesIv1) . '::' 
                         . base64_encode($rsaEncryptedKey) . '::' 
                         . base64_encode($rsaEncryptedIv2) . '::' 
                         . base64_encode($aesIv2) . '::' 
                         . base64_encode($privateKey) . '::'
                         . $expiration . '::'
                         . $hash;

            file_put_contents($fileName, $dataToStore);

            // Kullanıcıya yalnızca erişim anahtarı ver
            echo "<div class='alert alert-success mt-4'>";
            echo "<h5>Şifreleme Başarılı!</h5>";
            echo "<p>Metne erişmek için gerekli anahtar:</p>";
            echo "<p><strong>Anahtar:</strong> $accessKey</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
