<?php

namespace Goldfinch\Enchantment\Extensions;

use Composer\InstalledVersions;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Environment;
use SilverStripe\View\Requirements;
use SilverStripe\SiteConfig\SiteConfig;
use Goldfinch\Enchantment\Helpers\BuildHelper;
use SilverStripe\Core\Config\Config;

class EnchantmentAssetsExtension extends Extension
{
    public function init()
    {
        if (Environment::getEnv('SS_THEME_ENCHANTMENT')) {
            $cfg = SiteConfig::current_site_config();

            if ($cfg->ThemeEnchantment) {
                foreach (Config::forClass(__CLASS__)->get('block_packages') as $packageName) {
                    if (InstalledVersions::isInstalled($packageName)) {
                        Requirements::block(sprintf('%s:client/dist/styles/bundle.css', $packageName));
                        Requirements::block(sprintf('%s:client/dist/css/GroupedCmsMenu.css', $packageName));
                    }
                }

                if (BuildHelper::isProduction()) {
                    // silverstripe-admin
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-admin.css',
                    );

                    // silverstripe-cms
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-cms.css',
                    );

                    // silverstripe-session-manager
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-session-manager.css',
                    );

                    // silverstripe-versioned-admin
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-versioned-admin.css',
                    );

                    // silverstripe-asset-admin
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-asset-admin.css',
                    );

                    // silverstripe-campaign-admin
                    if (
                        InstalledVersions::isInstalled(
                            'silverstripe/campaign-admin',
                        ) &&
                        !InstalledVersions::isInstalled('goldfinch/cleaner')
                    ) {
                        Requirements::css(
                            'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-campaign-admin.css',
                        );
                    }

                    // silverstripe-mfa (for Security templates refer to _config.php)
                    if (InstalledVersions::isInstalled('silverstripe/mfa')) {
                        Requirements::css(
                            'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-mfa.css',
                        );

                        // silverstripe/totp-authenticator (for Security templates refer to _config.php)
                        if (
                            InstalledVersions::isInstalled(
                                'silverstripe/totp-authenticator',
                            )
                        ) {
                            Requirements::css(
                                'goldfinch/enchantment:client/dist/enchantment/assets/bundle-silverstripe-totp-authenticator.css',
                            );
                        }
                    }

                    // goldfinch/silverstripe-grouped-cms-menu
                    if (
                        InstalledVersions::isInstalled(
                            'goldfinch/silverstripe-grouped-cms-menu',
                        )
                    ) {
                        Requirements::css(
                            'goldfinch/enchantment:client/dist/enchantment/assets/GroupedCmsMenu.css',
                        );
                    }

                    // Enchantment
                    Requirements::css(
                        'goldfinch/enchantment:client/dist/enchantment/assets/enchantment-style.css',
                    );
                    Requirements::javascript(
                        'goldfinch/enchantment:client/dist/enchantment/assets/enchantment.js',
                    );
                }

                Requirements::insertHeadTags('
                <meta charset="utf-8" />
                ');

                // extra assets
                Requirements::css(
                    'goldfinch/extra-assets:client/dist/font-opensans.css',
                );
                Requirements::css(
                    'goldfinch/extra-assets:client/dist/bootstrap-icons-with-reset.css',
                );
            }
        }
    }
}
