# Magento 2 Extra Fee

<p>
<a href="https://packagist.org/packages/mageprince/magento2-extrafee"><img src="https://img.shields.io/packagist/v/mageprince/magento2-extrafee?style=for-the-badge&logo=packagist&logoColor=F28D1A&label=packagist&color=2F7FC1" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mageprince/magento2-extrafee/stats"><img src="https://img.shields.io/packagist/dt/mageprince/magento2-extrafee?style=for-the-badge&logo=packagist&logoColor=white&label=downloads&color=D4B53A" alt="Total Downloads"></a>
<a href="https://www.gnu.org/licenses/gpl-3.0"><img src="https://img.shields.io/badge/license-GPL--3.0-232F3E?style=for-the-badge" alt="License: GPL-3.0"></a>
<a href="https://business.adobe.com/products/magento/magento-commerce.html"><img src="https://img.shields.io/badge/magento-2.4-6CB83A?style=for-the-badge" alt="Magento 2.4"></a>
<a href="https://www.hyva.io/"><img src="https://img.shields.io/badge/hyv%C3%A4-compatible-0A1A3F?style=for-the-badge" alt="Hyvä compatible"></a>
<a href="https://mage-os.org/"><img src="https://img.shields.io/badge/mage--os-compatible-F26322?style=for-the-badge" alt="Mage-OS compatible"></a>
</p>

The Extra Fee extension enables store admins to add additional charge to customer order. It allows the creation of unlimited rules based on flexible conditions, ensuring that extra fee are applied only when specific criteria are met.

#### ⚠️ Note: This extension supports only a single extra fee. If you need to apply multiple extra fees based on flexible rules and conditions, check out the <a href="https://commercemarketplace.adobe.com/mageprince-module-extrafee-pro.html">Mageprince Extra Fee Pro</a> extension.

<img src="https://github.com/hyva-themes.png?size=40" height="20" align="top" alt="Hyvä"/> **Hyvä Theme compatible** – works out of the box on Hyvä, no compat module needed.<br/>
<img src="https://github.com/mage-os.png?size=40" height="20" align="top" alt="Mage-OS"/> **Mage-OS compatible** – supports Mage-OS as well as Magento Open Source and Adobe Commerce.

# ✨ Key Features

- Enable or disable the extension from admin configuration
- Apply extra fees based on specific store views and customer groups
- Option to enable or disable refunds for extra fees
- Apply extra fees during admin order creation
- Fully supports multi-currency and multi-store environments
- Supports tax calculation on extra fees with configurable tax class selection
- Choose how to display extra fees: Inclusive, Exclusive, or Both tax formats
- Define sort order for displaying multiple extra fees
- Create flexible conditions using cart and product attributes
- Assign extra fees to specific shipping methods
- Define rules based on postcode, region, state, or country
- Apply fees to specific products, categories, or product attributes
- Display extra fees in order summaries, invoices, credit memos, sales emails, and PDFs

# 🚀 Installation Instructions

### 1. Install via composer (Recommended)

Run the following Magento CLI commands:

```
composer require mageprince/magento2-extrafee
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

### 2. Manual Installation

Copy the content of the repo to the Magento 2 `app/code/Mageprince/Extrafee`

Run the following Magento CLI commands:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

# 🤝 Contribution

Want to contribute to this extension? The quickest way is to <a href="https://help.github.com/articles/about-pull-requests/">open a pull request</a> on GitHub.

# 🛠 Support

If you encounter any problems or bugs, please <a href="https://github.com/mageprince/magento2-extrafee/issues">open an issue</a> on GitHub.

# 📸 Screenshots

![1_checkout](https://github.com/user-attachments/assets/14f0dd39-ee15-4e6e-9b46-caa976a6fbbb)
![2_configuration_general](https://github.com/user-attachments/assets/c44062b8-3ee3-4911-a167-52af965737e8)
![3_configuration_fee](https://github.com/user-attachments/assets/44df80a2-e86c-4815-8a05-6a9267db075e)
![4_configuration_tax](https://github.com/user-attachments/assets/ba3d226c-545e-47b1-9df9-66719a88269f)
![5_menu](https://github.com/user-attachments/assets/4e4525b6-c7cc-4427-b0b6-394bb12c9324)
![6_conditions](https://github.com/user-attachments/assets/71c4062b-e3dc-486f-bd98-e1c9e1d87f16)
![7_condition_cart_attribute](https://github.com/user-attachments/assets/86000edc-9c0f-49eb-ab85-4fed4df36d34)
![8_condition_product_attributes](https://github.com/user-attachments/assets/fdf7b546-86f3-4570-a412-d157f3206b31)
![9_condition_product](https://github.com/user-attachments/assets/43ba4392-dec6-465c-a24e-9760ec4683fb)
![10_condition_category](https://github.com/user-attachments/assets/124e6516-afd1-45ea-8045-8c1ff8abfb91)










