<?php
use Kint\Twig\TwigExtension;
AddEventHandler(
    '',
    'onAfterTwigTemplateEngineInited',
    function (\Twig_Environment $engine) {

        $engine->addExtension(new TwigExtension());

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, array($engine));
    }
);
