<?php

namespace fortytwostudio\colourpalettes;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use fortytwostudio\colourpalettes\fields\ColourPalette;
use yii\base\Event;

class ColourPalettes extends BasePlugin
{
    public function init(): void
    {
        parent::init();

		Craft::setAlias('@colourPalettes', __DIR__);

		// Functions
		$this->createField();
    }

	// Public Functions
	// _______________________________________

	/**
	 * @return mixed
	 */
	public function createField(): void {
		Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function (RegisterComponentTypesEvent $event): void {
                $event->types[] = ColourPalette::class;
            }
        );
	}
}
