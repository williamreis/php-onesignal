<?php
/**
 * Biblioteca para usar Push Notifications para o One Signal
 * @author Carlos W. Gama <carloswgama@gmail.com>
 * @category Library
 * @version 1.0.0
 * @license MIT
 * @link https://documentation.onesignal.com/v3.0/reference#add-a-device
 */

namespace CWG\OneSignal;

use CWG\OOneSignal\Exception\OneSignalException;
use CWG\OneSignal\Notification;

class Device
{

    // ENUMS //
    const IOS = 0;
    const ANDROID = 1;
    const AMAZON = 2;
    const WINDOWSPHONE = 3;
    const CHROMEAPP = 4;
    const CHROMEWEB = 5;
    const SAFARI = 7;
    const FIREFOX = 8;
    const MACOS = 9;

    /**
     * Objeto que irá enviar as requisições
     * @access private
     * @var CURL
     */
    private $curl;

    /**
     * ID da API
     * @access private
     * @var string
     */
    private $appID;

    private $campos = [];

    public function __construct($appID)
    {
        $this->curl = CURL::getInstance();
        $this->appID = $appID;
    }

    /**
     * Adiciona o UUID do dispotivo
     * @param $id string
     * @return Device
     */
    public function setIdentifier($id)
    {
        $this->campos['identifier'] = $id;
        return $this;
    }

    /**
     * Informa o Idioma padrão
     * @param $lang string
     * @return Device
     */
    public function setLanguage($lang)
    {
        $this->campos['language'] = $lang;
        return $this;
    }

    /**
     * Informa o tipo ed dispotivo
     * @param $type int IOS|ANDROID|WINDOWSPHONE
     * @return Device
     */
    public function setDevice($type)
    {
        $this->campos['device_type'] = $type;
        return $this;
    }

    /**
     * Informa o device_model do dispotivo
     * @param $device_model int
     * @return Device
     */
    public function setDeviceModel($device_model)
    {
        $this->campos['device_model'] = $device_model;
        return $this;
    }

    /**
     * Informa o device_os do dispotivo
     * @param $device_os int
     * @return Device
     */
    public function setDeviceVersionOS($device_os)
    {
        $this->campos['device_os'] = $device_os;
        return $this;
    }

    /**
     * Informa o lat do dispotivo
     * @param $lat int
     * @return Latitude
     */
    public function setLatitude($lat)
    {
        $this->campos['lat'] = $lat;
        return $this;
    }

    /**
     * Informa o long do dispotivo
     * @param $long int
     * @return Latitude
     */
    public function setLongitude($long)
    {
        $this->campos['long'] = $long;
        return $this;
    }

    /**
     * Adiciona uma tag ao dispositivo
     * @param $name o nome da tag
     * @param $value o valor da tag
     * @return Device
     */
    public function addTag($name, $value)
    {
        $this->campos['tags'][$name] = $value;
        return $this;
    }

    /**
     * Adiciona uma ecternal user_id ao dispositivo
     * @param $externa_user_id string
     * @return Device
     */
    public function addExternalUserId($externa_user_id)
    {
        $this->campos['external_user_id'] = $externa_user_id;
        return $this;
    }

    /**
     * Cria um novo dispositivo
     * @param $campos array;
     * @uses $api->device->addTag('info', 'teste')->create('69aeecc1-7b58-44d1-8000-7767de437adf');
     */
    public function create($campos = null)
    {
        if ($campos != null) $this->campos = $campos;

        $this->campos['app_id'] = $this->appID;

        if (empty($this->campos['identifier'])) throw new OneSignalException;
        if (empty($this->campos['language'])) $campos['language'] = 'pt';
        if (empty($this->campos['device_type'])) $campos['device_type'] = SELF::ANDROID;

        return $this->curl->post('players', $this->campos);
    }

    /**
     * Atualiza uma dispositivo
     * @param $deviceID string
     * @param $campos array;
     * @return array
     * @uses $api->device->addTag('info', 'teste')->update('69aeecc1-7b58-44d1-8000-7767de437adf');
     */
    public function update($deviceID, $campos = null)
    {
        if ($campos != null) $this->campos = $campos;
        $this->campos['app_id'] = $this->appID;

        return $this->curl->put('players/' . $deviceID, $this->campos);
    }

    /**
     * Busca um dispositivo
     * @param $deviceID string
     * @return array
     * @uses $api->device->getDevice('69aeecc1-7b58-44d1-8000-7767de437adf');
     */
    public function getDevice($deviceID)
    {
        return $this->curl->put('players/' . $deviceID . '?app_id=' . $this->appID);
    }

    /**
     * O ID da APP
     * @param $appID string
     */
    private function setAppID($appID)
    {
        $this->appID = $appID;
    }

}