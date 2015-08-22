<?php

namespace Browser;

class DeviceDetector implements DetectorInterface
{
    /**
     * @var Device
     */
    private $device;

    /**
     * @var UserAgent
     */
    private $userAgent;

    /**
     * @param null|Device $device
     *
     * @throws \Browser\InvalidArgumentException
     */
    public function detect(Device $device)
    {
        $this->device = $device;

        if (!$this->userAgent instanceof UserAgent) {
            $this->userAgent = new UserAgent();
            $this->userAgent->createUserAgentString();
        }

        $this->checkIpad() || $this->checkIphone();

        $this->device->setIsDetected(true);
    }

    /**
     * @return bool
     */
    public function checkIpad()
    {
        if (stripos($this->userAgent->getUserAgentString(), 'ipad') !== false) {
            $this->device->setName(Device::IPAD);

            return true;
        }

        return false;
    }

    /**
     * Determine if the device is Iphone.
     *
     * @return bool
     */
    public function checkIphone()
    {
        if (stripos($this->userAgent->getUserAgentString(), 'iphone;') !== false) {
            $this->device->setName(Device::IPHONE);

            return true;
        }

        return false;
    }

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param Device $device
     *
     * @return $this
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @return UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param UserAgent $userAgent
     *
     * @return $this
     */
    public function setUserAgent(UserAgent $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}
