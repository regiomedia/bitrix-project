<?php

AddEventHandler(
    '',
    'onAfterTwigTemplateEngineInited',
    function (\Twig_Environment $engine) {

        $engine->addExtension(new Kint\Twig\TwigExtension());

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, array($engine));
    }
);
