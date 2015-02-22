<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TomasVodrazka\Forms;

/**
 * Description of FormUtils
 *
 * @author Tomáš
 */
class FormUtils {

	/**
	 * 
	 * @param \Nette\Application\UI\Form $form
	 * @param string $label
	 * @param string $name
	 */
	public static function addEditorField($form, $label, $name = null) {
		if ($name == null) {
			$name = self::getFieldName($label);
		}
		$form->addTextArea($name, $label)
				->getControlPrototype()->class('editor');
	}

	/**
	 * 
	 * @param string $label
	 * @return string
	 */
	public static function getFieldName($label) {
		return preg_replace("@-@", "_", \Nette\Utils\Strings::webalize($label));
	}

	/**
	 * 
	 * @param \Nette\Application\UI\Form $form
	 * @param array $row
	 */
	public static function setupDefaults($form, $row, $omit = array()) {
		foreach ($form->getControls() as $value) {
			if (!in_array($value->getName(), $omit)) {
				if (isset($row[$value->getName()])) {
					$value->setDefaultValue($row[$value->getName()]);
				}
			}
		}
	}

	/**
	 * 
	 * @param \Nette\Application\UI\Form $form
	 */
	public static function setupFormRenderingBootstrap($form) {
// setup form rendering
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = NULL;
		$renderer->wrappers['label']['container'] = NULL;
		$renderer->wrappers['control']['description'] = 'p class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'p class=help-block';

		$renderer->wrappers['label']['requiredsuffix'] = "*";
		// make form and controls compatible with Twitter Bootstrap
//		$form->getElementPrototype()->class('form-horizontal');
		foreach ($form->getControls() as $control) {

			if ($control instanceof \Nette\Forms\Controls\Button) {
				$control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
				$usedPrimary = TRUE;
			} elseif ($control instanceof \Nette\Forms\Controls\TextBase || $control instanceof \Nette\Forms\Controls\SelectBox || $control instanceof \Nette\Forms\Controls\MultiSelectBox) {
				$control->getControlPrototype()->addClass('form-control');
			} elseif ($control instanceof \Nette\Forms\Controls\Checkbox || $control instanceof \Nette\Forms\Controls\CheckboxList || $control instanceof \Nette\Forms\Controls\RadioList) {
				$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
			}
//			\Tracy\Debugger::dump($control);
		}
	}

}
