<?php

namespace fortytwostudio\colourpalettes\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use fortytwostudio\colourpalettes\models\PaletteOption;

class ColourPalette extends Field
{
    public string $palette = '';

    public static function displayName(): string
    {
        return 'Colour Palette';
    }

	public static function icon(): string
    {
        return '@colourPalettes/icon-mask.svg';
    }

	public static function valueType(): string
	{
		return PaletteOption::class . '|null';
	}

    private function getPalettes(): array
    {
        $config = Craft::$app->getConfig()
            ->getConfigFromFile('colour-palettes');

        return $config['palettes'] ?? [];
    }

	public function normalizeValue(
		mixed $value,
		?ElementInterface $element = null
	): ?PaletteOption {
		if ($value instanceof PaletteOption) {
			return $value;
		}

		$palettes = $this->getPalettes();
		$options = $palettes[$this->palette]['options'] ?? [];

		if ($value === null || $value === '') {
			foreach ($options as $option) {
				if ($option['default'] ?? false) {
					$value = $option['class'];
					break;
				}
			}
		}

		if ($value === null || $value === '') {
			return null;
		}

		foreach ($options as $option) {
			if ($option['class'] === $value) {
				return new PaletteOption([
					'label' => $option['label'],
					'class' => $option['class'],
					'color' => $option['color'],
					'default' => $option['default'] ?? false,
					'attributes' => $option['attributes'] ?? [],
				]);
			}
		}

		// Preserve a saved selection if its config option was removed.
		return new PaletteOption([
			'label' => (string)$value,
			'class' => (string)$value,
		]);
	}

	public function serializeValue(
		mixed $value,
		?ElementInterface $element = null
	): mixed {
		return $value instanceof PaletteOption
			? $value->class
			: $value;
	}

    public function getSettingsHtml(): ?string
    {
        $options = [
            [
                'label' => 'Choose a palette',
                'value' => '',
            ],
        ];

        foreach ($this->getPalettes() as $handle => $palette) {
            $options[] = [
                'label' => $palette['label'] ?? $handle,
                'value' => $handle,
            ];
        }

        return Craft::$app->getView()->renderTemplate(
			'colour-palettes/_field/settings',
			[
				'field' => $this,
				'options' => $options,
				'palettes' => $this->getPalettes(),
			]
		);
    }

    public function getInputHtml(
		mixed $value,
		?ElementInterface $element = null
	): string {
		$palettes = $this->getPalettes();
		$value = $this->normalizeValue($value, $element);

		return Craft::$app->getView()->renderTemplate(
			'colour-palettes/_field/input',
			[
				'field' => $this,
				'value' => $value?->class,
				'options' => $palettes[$this->palette]['options'] ?? [],
			]
		);
	}
}
