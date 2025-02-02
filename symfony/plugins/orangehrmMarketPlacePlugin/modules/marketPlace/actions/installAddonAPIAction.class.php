<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301, USA
 */

/**
 * Class installAddonAPI
 */
class installAddonAPIAction extends baseAddonAction
{
    private $pluginName;
    private $licenseDownloaded = false;

    /**
     * @param sfRequest $request
     * @return mixed|string
     */
    public function execute($request)
    {
        try {
            if (ini_get('max_execution_time') < 600) {
                ini_set('max_execution_time', 600);
            }
            $addonList = $this->getAddons();
            $data = $request->getParameterHolder()->getAll();
            $addonId = $data['installAddonID'];
            $addonURL = null;
            $addonDetail = null;
            foreach ($addonList as $addon) {
                if ($addon['id'] == $addonId) {
                    $addonDetail = $addon;
                    $addonURL = $addon['links']['file'];
                }
            }
            $addonFilePath = $this->getAddonFile($addonURL, $addonDetail);
            $this->pluginName = $this->getMarcketplaceService()->extractAddonFile($addonFilePath);
            if ($addonDetail['type']=='paid') {
                $addonLicenseContent = $this->getApiManagerService()->getAddonLicense($addonId);
                if (is_string($addonLicenseContent) && strlen($addonLicenseContent) > 0) {
                    file_put_contents(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $this->pluginName . DIRECTORY_SEPARATOR . 'ohrm.license.php', $addonLicenseContent);
                } else {
                    chdir(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'plugins');
                    exec("rm -r " . $this->pluginName , $clearResponse, $clearStatus);
                    throw new Exception('Error when retrieving the license file');
                }
            }
            $this->licenseDownloaded = true;
            $result = $this->installAddon($addonFilePath, $addonDetail, $this->pluginName);
            echo json_encode($result);
            return sfView::NONE;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            if ($this->pluginName && !$this->licenseDownloaded) {
                chdir(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'plugins');
                exec("rm -r " . $this->pluginName , $clearResponse, $clearStatus);
            }
            Logger::getLogger("orangehrm")->error($e->getCode() . ' : ' . $e->getMessage());
            Logger::getLogger("orangehrm")->error($e->getTraceAsString());
            echo json_encode(self::ERROR_CODE_NO_CONNECTION);
            return sfView::NONE;
        } catch (Exception $e) {
            if ($this->pluginName && !$this->licenseDownloaded) {
                chdir(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'plugins');
                exec("rm -r " . $this->pluginName , $clearResponse, $clearStatus);
            }
            Logger::getLogger("orangehrm")->error($e->getCode() . ' : ' . $e->getMessage());
            Logger::getLogger("orangehrm")->error($e->getTraceAsString());
            echo json_encode($e->getCode());
            return sfView::NONE;
        }
    }

    /**
     * @param $addonURL
     * @param $addonDetail
     * @return string
     * @throws CoreServiceException
     */
    private function getAddonFile($addonURL, $addonDetail)
    {
        $addonFilePath = $this->getApiManagerService()->getAddonFile($addonURL, $addonDetail);
        return $addonFilePath;
    }

    /**
     * @param $addonFilePath
     * @param $addonDetail
     * @return bool
     * @throws DaoException
     * @throws Doctrine_Transaction_Exception
     */
    protected function installAddon($addonFilePath, $addonDetail, $pluginname)
    {
        try {
            $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
            $connection->beginTransaction();
            $symfonyPath = sfConfig::get('sf_root_dir');
            $pluginInstallFilePath = $symfonyPath . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $pluginname . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'plugin_install.php';
            chdir($symfonyPath);
            exec("php symfony cc", $symfonyCcResponse, $symfonyCcStatus);
            if ($symfonyCcStatus != 0) {
                throw new Exception('Running php symfony cc fails.', 1001);
            }

            $install = require_once($pluginInstallFilePath);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollback();
            throw new Exception('installation query fails', 1002);
        }
        if (!$install) {
            throw new Exception('install file excecution fails.', 1003);
        }
        chdir($symfonyPath);
        exec("php symfony o:publish-asset", $publishAssetResponse, $publishAssetStatus);
        if ($publishAssetStatus != 0) {
            throw new Exception('Running php symfony o:publish-asset fails.', 1004);
        }
        chdir($symfonyPath);
        exec("php symfony d:build-model", $buildModelResponse, $buildModelStatus);
        if ($buildModelStatus != 0) {
            throw new Exception('Running php symfony d:build-model fails.', 1005);
        }

        if ($addonDetail['type'] != "paid") {
            $data = array(
                'id' => $addonDetail['id'],
                'addonName' => $addonDetail['title'],
                'status' => MarketplaceDao::ADDON_STATUS_INSTALLED,
                'type' => $addonDetail['type'],
                'pluginName' => $pluginname
            );
            $result = $this->getMarcketplaceService()->installOrRequestAddon($data);
        } else {
            $data = array(
                'id' => $addonDetail['id'],
                'addonName' => $addonDetail['title'],
                'status' => MarketplaceDao::ADDON_STATUS_INSTALLED,
                'pluginName' => $pluginname
            );
            $result = $this->getMarcketplaceService()->updateAddon($data);
        }

        if (!$result) {
            throw new Exception('Can not add to OrangeHRM database. Uninstallation will cause errors. But plugin can used.', 1006);
        }
        // clearing menu item cache so that new menus will be added.
        $this->getUser()->getAttributeHolder()->remove(mainMenuComponent::MAIN_MENU_USER_ATTRIBUTE);
        return $result;
    }
}
