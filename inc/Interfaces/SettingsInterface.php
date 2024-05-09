<?php

namespace LaunchpadOptions\Interfaces;

use LaunchpadOptions\Interfaces\Actions\DeleteInterface;
use LaunchpadOptions\Interfaces\Actions\FetchInterface;
use LaunchpadOptions\Interfaces\Actions\SetInterface;

interface SettingsInterface extends DeleteInterface, FetchInterface, SetInterface {}