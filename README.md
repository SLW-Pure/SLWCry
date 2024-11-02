# SLWCry - Şifreleme ve Şifre Çözme Sistemi

SLWCry, kullanıcıların metinleri güvenli bir şekilde şifreleyip çözmelerini sağlayan bir PHP tabanlı şifreleme sistemidir. Sistem, AES-256 ve RSA şifreleme algoritmalarını kullanarak çok katmanlı bir güvenlik sağlar. Bu projede, Bootstrap kullanılarak basit ve kullanıcı dostu bir arayüz oluşturulmuştur.

## Özellikler

- **AES-256 ve RSA Şifreleme:** Sistem, iki aşamalı AES-256 şifreleme ile RSA şifreleme kombinasyonu kullanır.
- **Erişim Anahtarı:** Kullanıcıya, şifreli veriye erişmek için özel bir anahtar sağlanır.
- **Hash Kontrolü:** Şifrelenmiş verinin bütünlüğünü doğrulamak için SHA-256 hash kullanılır.
- **Son Kullanma Tarihi:** Erişim anahtarının belirli bir süre sonra geçersiz olması sağlanır.
- **Bootstrap Tabanlı Arayüz:** Kullanıcı dostu bir arayüz ile şifreleme ve şifre çözme işlemleri basit bir şekilde yapılabilir.

## Dosya Yapısı

- `index.php` - Kullanıcıya şifreleme veya şifre çözme işlemlerini seçebileceği ana sayfa.
- `encrypt.php` - Kullanıcının metin şifrelemesi yapmasını sağlayan sayfa.
- `decrypt.php` - Kullanıcının erişim anahtarını kullanarak metnin şifresini çözmesini sağlayan sayfa.
- `key_management.php` - AES anahtarları, IV, RSA anahtarları ve erişim anahtarını yönetmek için kullanılan fonksiyonların bulunduğu dosya.

## Kurulum

1. Bu proje dosyalarını web sunucunuza yükleyin.
2. PHP'nin OpenSSL modülünün etkin olduğundan emin olun.
3. `encrypt.php` veya `decrypt.php` dosyalarını çalıştırarak sistemin çalıştığını doğrulayın.

## Kullanım

1. **Ana Sayfa:** `index.php` dosyasını açarak şifreleme veya şifre çözme işlemini seçin.
2. **Metin Şifreleme:** 
   - `encrypt.php` sayfasına gidin.
   - Şifrelemek istediğiniz metni girin ve "Şifrele" butonuna tıklayın.
   - Sistem, metni şifreler ve size bir erişim anahtarı sağlar. Bu anahtarı, şifre çözme işlemi için saklayın.
3. **Metin Şifre Çözme:** 
   - `decrypt.php` sayfasına gidin.
   - Size verilen erişim anahtarını girin ve "Şifreyi Çöz" butonuna tıklayın.
   - Sistem, metnin şifresini çözer ve size orijinal metni gösterir.

## Güvenlik Detayları

- **Çok Katmanlı Şifreleme:** Metin, iki aşamalı AES-256 şifreleme ve RSA ile korunan anahtarlarla şifrelenir.
- **Veri Bütünlüğü Kontrolü:** SHA-256 hash kullanılarak verinin bütünlüğü doğrulanır.
- **Zaman Kısıtlaması:** Erişim anahtarı belirli bir süre sonra geçersiz hale gelir.
  
## Gereksinimler

- PHP 7.4 veya üzeri
- OpenSSL PHP eklentisi
- Bootstrap 5.3.0 (CDN üzerinden yüklenmektedir)

## Lisans

Bu proje MIT Lisansı ile lisanslanmıştır.
