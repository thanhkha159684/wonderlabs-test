# Installation 
Besure that you have php imagemagick extension (It is required to generate QR code)
For setup demo website, go to root website and running composer
```bash
composer install
```
Enable theme botstrap (Which have sidebar so we can display QR code)

Enable module wonderlabs_product
```bash
drush en -y wonderlabs_product
```
Enable block Wonderlabs QR code on Sidebar region

Then clear cache
```bash
drush cr
```
Add new product content and enjoy!

# Demonstration
http://13.229.107.4/products-page

Admin credential:

Username: admin

Password: Admin@12345678
