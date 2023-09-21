<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c3c6443177b612c6699a7a8f892b138
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Oksydan\\IsProductExtraTabs\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Oksydan\\IsProductExtraTabs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Is_productextratabs' => __DIR__ . '/../..' . '/is_productextratabs.php',
        'Oksydan\\IsProductExtraTabs\\Cache\\TemplateCache' => __DIR__ . '/../..' . '/src/Cache/TemplateCache.php',
        'Oksydan\\IsProductExtraTabs\\Controller\\ProductExtraTabController' => __DIR__ . '/../..' . '/src/Controller/ProductExtraTabController.php',
        'Oksydan\\IsProductExtraTabs\\Entity\\ProductExtraTab' => __DIR__ . '/../..' . '/src/Entity/ProductExtraTab.php',
        'Oksydan\\IsProductExtraTabs\\Entity\\ProductExtraTabDefaultLang' => __DIR__ . '/../..' . '/src/Entity/ProductExtraTabDefaultLang.php',
        'Oksydan\\IsProductExtraTabs\\Entity\\ProductExtraTabProduct' => __DIR__ . '/../..' . '/src/Entity/ProductExtraTabProduct.php',
        'Oksydan\\IsProductExtraTabs\\Entity\\ProductExtraTabProductLang' => __DIR__ . '/../..' . '/src/Entity/ProductExtraTabProductLang.php',
        'Oksydan\\IsProductExtraTabs\\Exceptions\\DatabaseYamlFileNotExistsException' => __DIR__ . '/../..' . '/src/Exceptions/DatabaseYamlFileNotExistsException.php',
        'Oksydan\\IsProductExtraTabs\\Filter\\ProductExtraTabFilters' => __DIR__ . '/../..' . '/src/Filter/ProductExtraTabFilters.php',
        'Oksydan\\IsProductExtraTabs\\Form\\DataHandler\\ProductExtraTabFormDataHandler' => __DIR__ . '/../..' . '/src/Form/DataHandler/ProductExtraTabFormDataHandler.php',
        'Oksydan\\IsProductExtraTabs\\Form\\DataHandler\\ProductExtraTabProductFormDataHandler' => __DIR__ . '/../..' . '/src/Form/DataHandler/ProductExtraTabProductFormDataHandler.php',
        'Oksydan\\IsProductExtraTabs\\Form\\ProductExtraTabProductType' => __DIR__ . '/../..' . '/src/Form/ProductExtraTabProductType.php',
        'Oksydan\\IsProductExtraTabs\\Form\\ProductExtraTabType' => __DIR__ . '/../..' . '/src/Form/ProductExtraTabType.php',
        'Oksydan\\IsProductExtraTabs\\Form\\Provider\\ProductExtraTabFormDataProvider' => __DIR__ . '/../..' . '/src/Form/Provider/ProductExtraTabFormDataProvider.php',
        'Oksydan\\IsProductExtraTabs\\Form\\Provider\\ProductExtraTabProductFormDataProvider' => __DIR__ . '/../..' . '/src/Form/Provider/ProductExtraTabProductFormDataProvider.php',
        'Oksydan\\IsProductExtraTabs\\Grid\\Data\\Factory\\ProductExtraTabGridDataFactory' => __DIR__ . '/../..' . '/src/Grid/Data/Factory/ProductExtraTabGridDataFactory.php',
        'Oksydan\\IsProductExtraTabs\\Grid\\Definition\\Factory\\ProductExtraTabGridDefinitionFactory' => __DIR__ . '/../..' . '/src/Grid/Definition/Factory/ProductExtraTabGridDefinitionFactory.php',
        'Oksydan\\IsProductExtraTabs\\Grid\\Query\\ProductExtraTabQueryBuilder' => __DIR__ . '/../..' . '/src/Grid/Query/ProductExtraTabQueryBuilder.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\AbstractCacheableDisplayHook' => __DIR__ . '/../..' . '/src/Hook/AbstractCacheableDisplayHook.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\AbstractDisplayHook' => __DIR__ . '/../..' . '/src/Hook/AbstractDisplayHook.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\AbstractHook' => __DIR__ . '/../..' . '/src/Hook/AbstractHook.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\DisplayAdminProductsExtra' => __DIR__ . '/../..' . '/src/Hook/DisplayAdminProductsExtra.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\DisplayProductExtraContent' => __DIR__ . '/../..' . '/src/Hook/DisplayProductExtraContent.php',
        'Oksydan\\IsProductExtraTabs\\Hook\\HookInterface' => __DIR__ . '/../..' . '/src/Hook/HookInterface.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseAbstract' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseAbstract.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseAddColumn' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseAddColumn.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseCrateTable' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseCrateTable.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseDropTable' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseDropTable.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseInterface' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseInterface.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ActionDatabaseModifyColumn' => __DIR__ . '/../..' . '/src/Installer/ActionDatabaseModifyColumn.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\DatabaseYamlParser' => __DIR__ . '/../..' . '/src/Installer/DatabaseYamlParser.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\ProductExtraTabInstaller' => __DIR__ . '/../..' . '/src/Installer/ProductExtraTabInstaller.php',
        'Oksydan\\IsProductExtraTabs\\Installer\\Provider\\DatabaseYamlProvider' => __DIR__ . '/../..' . '/src/Installer/Provider/DatabaseYamlProvider.php',
        'Oksydan\\IsProductExtraTabs\\Repository\\HookModuleRepository' => __DIR__ . '/../..' . '/src/Repository/HookModuleRepository.php',
        'Oksydan\\IsProductExtraTabs\\Repository\\ProductExtraTabProductRepository' => __DIR__ . '/../..' . '/src/Repository/ProductExtraTabProductRepository.php',
        'Oksydan\\IsProductExtraTabs\\Repository\\ProductExtraTabRepository' => __DIR__ . '/../..' . '/src/Repository/ProductExtraTabRepository.php',
        'Oksydan\\IsProductExtraTabs\\Translations\\TranslationDomains' => __DIR__ . '/../..' . '/src/Translations/TranslationDomains.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c3c6443177b612c6699a7a8f892b138::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c3c6443177b612c6699a7a8f892b138::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3c3c6443177b612c6699a7a8f892b138::$classMap;

        }, null, ClassLoader::class);
    }
}
